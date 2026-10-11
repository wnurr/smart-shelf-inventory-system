<?php

namespace App\Http\Controllers;

use App\Models\Calibration;
use App\Models\CalibrationCommand;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalibrationController extends Controller
{
    /**
     * Display calibration records for every item, newest first, plus the
     * live calibration control panel (item picker + trigger buttons).
     */
    public function index(): View
    {
        // Calibration is current configuration, not an audit log.
        // One item therefore shows only its latest/current calibration.
        $items = Item::with(['sensor', 'latestCalibration.user.role'])
            ->whereHas('sensor')
            ->orderBy('item_name')
            ->get();

        return view('calibration.index', compact('items'));
    }

    /**
     * Queue a "tare" command for the item's sensor.
     */
    public function tare(Request $request): JsonResponse
    {
        return $this->queueCommand($request, 'tare');
    }

    /**
     * Queue a "calibrate_scale" command: ESP32 will wait for a known
     * reference weight to be placed on the shelf and compute the
     * raw-to-gram factor.
     */
    public function calibrateScale(Request $request): JsonResponse
    {
        $request->validate(['reference_weight' => 'required|numeric|min:0.01']);

        return $this->queueCommand($request, 'calibrate_scale', [
            'reference_weight' => $request->input('reference_weight'),
        ]);
    }

    /**
     * Queue a unit-weight command for exactly ONE physical item.
     * Quantity is intentionally fixed to 1 so the operator cannot enter
     * an inconsistent reference quantity from the UI.
     */
    public function setUnitWeight(Request $request): JsonResponse
    {
        return $this->queueCommand($request, 'set_unit_weight', [
            'reference_quantity' => 1,
        ]);
    }

    private function queueCommand(Request $request, string $type, array $extra = []): JsonResponse
    {
        $request->validate(['item_id' => 'required|exists:items,id']);

        $item = Item::with('sensor')->findOrFail($request->input('item_id'));

        if (!$item->sensor) {
            return response()->json(['message' => 'This item has no sensor attached'], 422);
        }

        // Never queue two physical calibration operations for the same sensor.
        // This prevents the ESP32 from executing stale commands out of order.
        $activeCommand = CalibrationCommand::query()
            ->where('sensor_code', $item->sensor->sensor_code)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByDesc('id')
            ->first();

        if ($activeCommand) {
            return response()->json([
                'message' => 'This sensor already has an active calibration command.',
                'command_id' => $activeCommand->id,
                'status' => $activeCommand->status,
            ], 409);
        }

        $command = CalibrationCommand::create(array_merge([
            'item_id' => $item->id,
            'sensor_code' => $item->sensor->sensor_code,
            'type' => $type,
            'status' => 'pending',
            'created_by' => $request->user()?->id,
            'created_at' => now(),
        ], $extra));

        return response()->json([
            'command_id' => $command->id,
            'status' => $command->status,
        ]);
    }

    /**
     * Polled by the calibration page (every ~1.5s) to see whether the
     * ESP32 has picked up and finished a command yet.
     */
    public function commandStatus(CalibrationCommand $command): JsonResponse
{
    $command->refresh();

    return response()
        ->json([
            'status' => $command->status,
            'result' => $command->result,
            'completed_at' => $command->completed_at,
        ])
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
}

public function expireCommand(
    CalibrationCommand $command
): JsonResponse {

    if (
        in_array(
            $command->status,
            ['pending', 'in_progress'],
            true
        )
    ) {
        $command->update([
            'status' => 'failed',

            'result' => [
                'message' =>
                    'Command expired because the calibration device did not respond in time.',
            ],

            'completed_at' => now(),
        ]);
    }

    return response()->json([
        'message' =>
            'Calibration command expired.',
    ]);
}
}