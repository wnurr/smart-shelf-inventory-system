<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\History;
use App\Models\InventoryReading;
use App\Models\Sensor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReadingController extends Controller
{
    public function sensorConfig(string $sensorCode): JsonResponse
    {
        try {
            $sensor = Sensor::query()
                ->where('sensor_code', $sensorCode)
                ->with('item.latestCalibration')
                ->first();

            if (!$sensor) {
                return response()->json([
                    'success' => false,
                    'error' => 'SENSOR_NOT_FOUND',
                    'message' => 'Sensor not found.',
                ], 404);
            }

            $sensor->forceFill(['last_seen_at' => now()])->save();
            $item = $sensor->item;
            $calibration = $item?->latestCalibration;
            $unitWeight = $calibration ? (float) $calibration->item_weight_per_unit : 0.0;

            return response()->json([
                'success' => true,
                'sensor_code' => $sensor->sensor_code,
                'assigned' => $item ? 1 : 0,
                'calibrated' => ($item && $unitWeight > 0) ? 1 : 0,
                'item_id' => $item?->id,
                'item_name' => $item?->item_name,
                'unit_weight' => $unitWeight,
                'calibration_factor' => $sensor->calibration_factor,
                'server_time' => now()->toIso8601String(),
            ])->header('Cache-Control', 'no-store');
        } catch (Throwable $exception) {
            Log::error('Sensor config sync failed.', [
                'sensor_code' => $sensorCode,
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'INTERNAL_ERROR',
                'message' => 'Server could not provide sensor configuration.',
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sensor_id' => ['required', 'string', 'max:100'],
            'weight' => ['required', 'numeric', 'min:0'],
            'event_id' => ['nullable', 'string', 'max:120'],
        ]);

        try {
            $sensor = Sensor::query()
                ->where('sensor_code', $data['sensor_id'])
                ->with('item.latestCalibration')
                ->first();

            if (!$sensor) {
                return response()->json([
                    'success' => false,
                    'error' => 'SENSOR_NOT_FOUND',
                    'message' => 'Sensor not found.',
                ], 404);
            }

            $sensor->forceFill(['last_seen_at' => now()])->save();

            if (!$sensor->item) {
                return response()->json([
                    'success' => false,
                    'error' => 'SENSOR_NOT_ASSIGNED',
                    'message' => 'No item linked to this sensor.',
                    'sensor_code' => $sensor->sensor_code,
                ], 422);
            }

            $item = $sensor->item;
            $calibration = $item->latestCalibration;

            if (!$calibration || (float) $calibration->item_weight_per_unit <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'ITEM_NOT_CALIBRATED',
                    'message' => 'Item has not been calibrated yet.',
                    'sensor_code' => $sensor->sensor_code,
                    'item_id' => $item->id,
                ], 422);
            }

            $unitWeight = (float) $calibration->item_weight_per_unit;
            $weight = max(0, (float) $data['weight']);
            $quantity = max(0, (int) round($weight / $unitWeight));
            $stockStatus = $this->determineStockStatus($quantity, (int) $item->min_stock);
            $eventId = $data['event_id'] ?? null;

            if ($eventId) {
                $duplicate = InventoryReading::query()->where('event_id', $eventId)->first();
                if ($duplicate) {
                    return response()->json([
                        'success' => true,
                        'duplicate' => true,
                        'saved' => false,
                        'event_id' => $eventId,
                        'sensor_code' => $sensor->sensor_code,
                        'quantity' => (int) $duplicate->quantity,
                        'message' => 'Event was already processed.',
                    ])->header('Cache-Control', 'no-store');
                }
            }

            $result = DB::transaction(function () use ($item, $weight, $quantity, $stockStatus, $eventId) {
                if ($eventId) {
                    $duplicate = InventoryReading::query()->where('event_id', $eventId)->lockForUpdate()->first();
                    if ($duplicate) {
                        return [
                            'saved' => false,
                            'duplicate' => true,
                            'action' => null,
                            'previous_quantity' => (int) $duplicate->quantity,
                        ];
                    }
                }

                $latestReading = InventoryReading::query()
                    ->where('item_id', $item->id)
                    ->orderByDesc('recorded_at')
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->first();

                if (!$latestReading) {
                    InventoryReading::create([
                        'item_id' => $item->id,
                        'event_id' => $eventId,
                        'weight' => $weight,
                        'quantity' => $quantity,
                        'stock_status' => $stockStatus,
                        'recorded_at' => now(),
                    ]);

                    return [
                        'saved' => true,
                        'duplicate' => false,
                        'action' => 'initial',
                        'previous_quantity' => null,
                    ];
                }

                $previousQuantity = (int) $latestReading->quantity;

                if ($previousQuantity === $quantity) {
                    return [
                        'saved' => false,
                        'duplicate' => false,
                        'action' => null,
                        'previous_quantity' => $previousQuantity,
                    ];
                }

                InventoryReading::create([
                    'item_id' => $item->id,
                    'event_id' => $eventId,
                    'weight' => $weight,
                    'quantity' => $quantity,
                    'stock_status' => $stockStatus,
                    'recorded_at' => now(),
                ]);

                $action = $quantity > $previousQuantity ? 'stock_in' : 'stock_out';

                History::create([
                    'item_id' => $item->id,
                    'user_id' => null,
                    'action' => $action,
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $quantity,
                    'created_at' => now(),
                ]);

                return [
                    'saved' => true,
                    'duplicate' => false,
                    'action' => $action,
                    'previous_quantity' => $previousQuantity,
                ];
            }, 3);

            return response()->json([
                'success' => true,
                'duplicate' => $result['duplicate'],
                'saved' => $result['saved'],
                'event_id' => $eventId,
                'sensor_code' => $sensor->sensor_code,
                'item_id' => $item->id,
                'item_name' => $item->item_name,
                'weight' => round($weight, 3),
                'unit_weight' => round($unitWeight, 4),
                'previous_quantity' => $result['previous_quantity'],
                'quantity' => $quantity,
                'min_stock' => (int) $item->min_stock,
                'stock_status' => $stockStatus,
                'action' => $result['action'],
                'server_time' => now()->toIso8601String(),
                'message' => $result['saved'] ? 'Stable inventory reading processed.' : 'Quantity unchanged. Nothing saved.',
            ])->header('Cache-Control', 'no-store');
        } catch (Throwable $exception) {
            Log::error('Stable sensor reading failed.', [
                'sensor_id' => $data['sensor_id'] ?? null,
                'event_id' => $data['event_id'] ?? null,
                'weight' => $data['weight'] ?? null,
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'INTERNAL_ERROR',
                'message' => 'Server could not process the sensor reading.',
            ], 500);
        }
    }

    public function storeLive(Request $request): JsonResponse
    {
        $data = $request->validate([
            'sensor_id' => ['required', 'string', 'max:100'],
            'weight' => ['required', 'numeric'],
        ]);

        try {
            $sensor = Sensor::query()
                ->where('sensor_code', $data['sensor_id'])
                ->with('item.latestCalibration')
                ->first();

            if (!$sensor) {
                return response()->json([
                    'success' => false,
                    'error' => 'SENSOR_NOT_FOUND',
                    'message' => 'Sensor not found.',
                ], 404);
            }

            $sensor->forceFill(['last_seen_at' => now()])->save();
            $weight = max(0, (float) $data['weight']);
            $quantity = null;
            $unitWeight = null;
            $calibrated = false;

            if ($sensor->item && $sensor->item->latestCalibration) {
                $unitWeight = (float) $sensor->item->latestCalibration->item_weight_per_unit;
                if ($unitWeight > 0) {
                    $calibrated = true;
                    $quantity = max(0, (int) round($weight / $unitWeight));
                }
            }

            $liveData = [
                'sensor_code' => $sensor->sensor_code,
                'item_id' => $sensor->item?->id,
                'item_name' => $sensor->item?->item_name,
                'weight' => round($weight, 3),
                'quantity' => $quantity,
                'unit_weight' => $unitWeight,
                'calibrated' => $calibrated,
                'recorded_at' => now()->format('Y-m-d H:i:s'),
            ];

            Cache::put('smart_shelf_live_' . $sensor->sensor_code, $liveData, now()->addSeconds(15));

            return response()->json([
                'success' => true,
                'message' => 'Live reading received.',
                'data' => $liveData,
            ])->header('Cache-Control', 'no-store');
        } catch (Throwable $exception) {
            Log::error('Live sensor reading failed.', [
                'sensor_id' => $data['sensor_id'] ?? null,
                'weight' => $data['weight'] ?? null,
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'INTERNAL_ERROR',
                'message' => 'Server could not process the live reading.',
            ], 500);
        }
    }

    public function live(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);

        $sensor = Sensor::query()->where('item_id', $data['item_id'])->first();
        if (!$sensor) return $this->emptyLiveResponse();

        $liveData = Cache::get('smart_shelf_live_' . $sensor->sensor_code);
        if (!$liveData) return $this->emptyLiveResponse();

        if (isset($liveData['item_id']) && $liveData['item_id'] !== null && (int) $liveData['item_id'] !== (int) $data['item_id']) {
            return $this->emptyLiveResponse();
        }

        return response()->json([
            'weight' => isset($liveData['weight']) ? (float) $liveData['weight'] : null,
            'quantity' => isset($liveData['quantity']) ? (int) $liveData['quantity'] : null,
            'recorded_at' => $liveData['recorded_at'] ?? null,
        ])->header('Cache-Control', 'no-store');
    }

    public function latest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);

        $reading = InventoryReading::query()
            ->where('item_id', $data['item_id'])
            ->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->first();

        if (!$reading) {
            return response()->json([
                'weight' => null,
                'quantity' => null,
                'stock_status' => null,
                'recorded_at' => null,
            ]);
        }

        return response()->json([
            'weight' => (float) $reading->weight,
            'quantity' => (int) $reading->quantity,
            'stock_status' => $reading->stock_status,
            'recorded_at' => $reading->recorded_at?->format('Y-m-d H:i:s'),
        ])->header('Cache-Control', 'no-store');
    }

    private function determineStockStatus(int $quantity, int $minimumStock): string
    {
        if ($quantity <= 0) return 'out_of_stock';
        if ($quantity <= $minimumStock) return 'low_stock';
        return 'in_stock';
    }

    private function emptyLiveResponse(): JsonResponse
    {
        return response()->json([
            'weight' => null,
            'quantity' => null,
            'recorded_at' => null,
        ])->header('Cache-Control', 'no-store');
    }
}
