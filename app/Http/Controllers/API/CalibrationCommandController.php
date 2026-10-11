<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calibration;
use App\Models\CalibrationCommand;
use App\Models\Sensor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CalibrationCommandController extends Controller
{
    /**
     * ============================================================
     * GET PENDING CALIBRATION COMMAND
     * ============================================================
     */
    public function pending(Request $request)
    {
        $data = $request->validate([
            'sensor_code' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        try {
            $sensor = Sensor::query()
                ->where(
                    'sensor_code',
                    $data['sensor_code']
                )
                ->first();

            if (!$sensor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sensor not found.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | Heartbeat
            |--------------------------------------------------------------------------
            */
            $sensor->forceFill([
                'last_seen_at' => now(),
            ])->save();

            /*
            |--------------------------------------------------------------------------
            | Expire old commands
            |--------------------------------------------------------------------------
            |
            | Keep your existing 20-second UI behaviour.
            */
            CalibrationCommand::query()
                ->where(
                    'sensor_code',
                    $sensor->sensor_code
                )
                ->where('status', 'pending')
                ->where(
                    'created_at',
                    '<',
                    now()->subSeconds(20)
                )
                ->get()
                ->each(function (
                    CalibrationCommand $command
                ) {
                    $command->update([
                        'status' => 'failed',

                        'result' => [
                            'message' =>
                                'Command expired because the calibration device did not respond in time.',
                        ],

                        'completed_at' => now(),
                    ]);
                });

            /*
            |--------------------------------------------------------------------------
            | Important reliability change
            |--------------------------------------------------------------------------
            |
            | If ESP32 previously received a command but failed to receive
            | Laravel's completion response, the command may remain
            | "in_progress".
            |
            | We do NOT send a second physical calibration command here.
            | Completion retry is handled by the ESP32 using the same
            | command ID.
            */
            $command = CalibrationCommand::query()
                ->where(
                    'sensor_code',
                    $sensor->sensor_code
                )
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->orderBy('id')
                ->first();

            if (!$command) {
                return response()->noContent();
            }

            $command->update([
                'status' => 'in_progress',
            ]);

            return response()
                ->json([
                    'success' => true,

                    'id' =>
                        $command->id,

                    'type' =>
                        $command->type,

                    'reference_weight' =>
                        $command->reference_weight,
                ])
                ->header(
                    'Cache-Control',
                    'no-store, no-cache, must-revalidate, max-age=0'
                );

        } catch (Throwable $exception) {

            Log::error(
                'Calibration command polling failed.',
                [
                    'sensor_code' =>
                        $data['sensor_code'] ?? null,

                    'exception' =>
                        $exception->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Server could not retrieve calibration command.',
            ], 500);
        }
    }


    /**
     * ============================================================
     * COMPLETE CALIBRATION COMMAND
     * ============================================================
     *
     * Idempotent:
     * ESP32 may safely retry this request.
     */
    public function complete(
        Request $request,
        CalibrationCommand $command
    ): JsonResponse {
        $data = $request->validate([
            'status' => [
                'required',
                'in:completed,failed',
            ],

            'raw_reading' => [
                'nullable',
                'numeric',
            ],

            'calibration_factor' => [
                'nullable',
                'numeric',
            ],

            'measured_weight' => [
                'nullable',
                'numeric',
            ],

            'message' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        try {
            /*
            |--------------------------------------------------------------------------
            | Idempotency
            |--------------------------------------------------------------------------
            |
            | ESP32 can retry completion if HTTP response was lost.
            */
            if ($command->status === 'completed') {
                return response()->json([
                    'success' => true,
                    'already_completed' => true,
                    'message' =>
                        'Command was already completed successfully.',
                ]);
            }

            /*
             * Expired/failed commands must not overwrite
             * current calibration.
             */
            if (
                $command->status === 'failed' &&
                $data['status'] === 'completed'
            ) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'This calibration command has already expired or failed.',
                ], 409);
            }

            DB::transaction(function () use (
                $command,
                $data
            ) {
                /*
                 * Lock command to protect against duplicate
                 * completion requests.
                 */
                $lockedCommand =
                    CalibrationCommand::query()
                        ->whereKey($command->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                /*
                 * Another request completed it while this
                 * request was waiting for the lock.
                 */
                if (
                    $lockedCommand->status ===
                    'completed'
                ) {
                    return;
                }

                /*
                 * ESP32 reported failure.
                 */
                if ($data['status'] === 'failed') {
                    $lockedCommand->update([
                        'status' => 'failed',
                        'result' => $data,
                        'completed_at' => now(),
                    ]);

                    return;
                }

                $sensor = Sensor::query()
                    ->where(
                        'sensor_code',
                        $lockedCommand->sensor_code
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$sensor) {
                    throw new \RuntimeException(
                        'Sensor for calibration command was not found.'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Scale calibration
                |--------------------------------------------------------------------------
                */
                if (
                    $lockedCommand->type ===
                    'calibrate_scale'
                ) {
                    $factor =
                        (float) (
                            $data['calibration_factor']
                            ?? 0
                        );

                    if ($factor <= 0) {
                        throw new \RuntimeException(
                            'ESP32 returned an invalid calibration factor.'
                        );
                    }

                    $sensor->update([
                        'calibration_factor' =>
                            $factor,

                        'last_calibrated_at' =>
                            now(),

                        'last_seen_at' =>
                            now(),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Set unit weight
                |--------------------------------------------------------------------------
                */
                if (
                    $lockedCommand->type ===
                    'set_unit_weight'
                ) {
                    $weight =
                        (float) (
                            $data['measured_weight']
                            ?? 0
                        );

                    if ($weight < 0.50) {
                        throw new \RuntimeException(
                            'ESP32 returned a unit weight below the 0.50 g minimum threshold.'
                        );
                    }

                    Calibration::updateOrCreate(
                        [
                            'item_id' =>
                                $lockedCommand->item_id,
                        ],
                        [
                            'user_id' =>
                                $lockedCommand->created_by,

                            'reference_quantity' =>
                                1,

                            'reference_weight' =>
                                $weight,

                            'item_weight_per_unit' =>
                                $weight,

                            'calibration_factor' =>
                                $sensor->calibration_factor
                                ?: 1,

                            'calibrated_at' =>
                                now(),
                        ]
                    );

                    $sensor->update([
                        'last_seen_at' => now(),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Tare
                |--------------------------------------------------------------------------
                */
                if (
                    $lockedCommand->type === 'tare'
                ) {
                    $sensor->update([
                        'last_seen_at' => now(),
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Mark completed LAST
                |--------------------------------------------------------------------------
                |
                | Only after all related DB changes succeeded.
                */
                $lockedCommand->update([
                    'status' => 'completed',
                    'result' => $data,
                    'completed_at' => now(),
                ]);
            }, 3);

            /*
             * Refresh after transaction.
             */
            $command->refresh();

            if ($command->status === 'failed') {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Command marked as failed. No calibration changes were saved.',
                ]);
            }

            return response()->json([
                'success' => true,
                'already_completed' => false,
                'message' =>
                    'Command completed and calibration data saved.',
            ]);

        } catch (Throwable $exception) {

            Log::error(
                'Calibration command completion failed.',
                [
                    'command_id' =>
                        $command->id,

                    'sensor_code' =>
                        $command->sensor_code,

                    'type' =>
                        $command->type,

                    'exception' =>
                        $exception->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'Server could not save calibration result.',
            ], 500);
        }
    }
}