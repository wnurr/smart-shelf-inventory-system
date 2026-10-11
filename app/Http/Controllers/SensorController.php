<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SensorController extends Controller
{
    /**
     * ============================================================
     * SUPPORTED PHYSICAL SENSOR SLOTS
     * ============================================================
     *
     * Current FYP prototype:
     *
     * ESP32
     * ├── SENSOR-1
     * └── SENSOR-2
     *
     * Only these two physical sensor identities are supported.
     */
    private const SUPPORTED_SENSOR_NUMBERS = [1, 2];


    /**
     * ============================================================
     * SENSOR INDEX
     * ============================================================
     *
     * Display:
     * - Registered sensors
     * - Item assignment
     * - Online / Offline status
     * - Last reading time
     *
     * Connection status is derived from sensors.last_seen_at,
     * refreshed whenever ESP32 polls Laravel for commands.
     */
    public function index(Request $request): View
    {
        $search = trim(
            (string) $request->input('search', '')
        );


        // ========================================================
        // SUPPORTED SENSOR CODES
        // ========================================================

        $supportedCodes = collect(
            self::SUPPORTED_SENSOR_NUMBERS
        )
            ->map(
                fn (int $number) =>
                    $this->sensorCode($number)
            )
            ->all();


        // ========================================================
        // SUMMARY COUNTS
        // ========================================================

        $registeredSupportedCount = Sensor::whereIn(
            'sensor_code',
            $supportedCodes
        )->count();


        $assignedCount = Sensor::whereIn(
            'sensor_code',
            $supportedCodes
        )
            ->whereNotNull('item_id')
            ->count();


        $availableCount = Sensor::whereIn(
            'sensor_code',
            $supportedCodes
        )
            ->whereNull('item_id')
            ->count();


        $legacyCount = Sensor::whereNotIn(
            'sensor_code',
            $supportedCodes
        )->count();


        // ========================================================
        // SENSOR LIST
        // ========================================================
        //
        // Load the current item assignment. Connection state is
        // read directly from sensors.last_seen_at.
        // ========================================================

        $sensors = Sensor::with('item')
            ->when(
                $search !== '',

                fn ($query) =>
                    $query->where(
                        'sensor_code',
                        'like',
                        '%' . $search . '%'
                    )
            )

            ->orderByRaw(
                "CASE
                    WHEN sensor_code = 'SENSOR-1' THEN 1
                    WHEN sensor_code = 'SENSOR-2' THEN 2
                    ELSE 3
                 END"
            )

            ->orderBy('sensor_code')

            ->paginate(10)

            ->withQueryString();


        // ========================================================
        // RETURN VIEW
        // ========================================================

        // ========================================================
// AVAILABLE PHYSICAL SENSOR SLOTS
// ========================================================
//
// Required by the Add Sensor modal.
// Only unregistered physical slots will be displayed.
//

$availableNumbers = $this->availableSensorNumbers();


// ========================================================
// RETURN VIEW
// ========================================================

return view(
    'sensors.index',

    compact(
        'sensors',
        'search',
        'supportedCodes',
        'registeredSupportedCount',
        'assignedCount',
        'availableCount',
        'legacyCount',
        'availableNumbers'
    )
);
    }


    /**
     * ============================================================
     * CREATE SENSOR
     * ============================================================
     *
     * Show the registration form for one of the two physical
     * sensor slots.
     */
    public function create(): View|RedirectResponse
    {
        $availableNumbers =
            $this->availableSensorNumbers();


        if ($availableNumbers->isEmpty()) {

            return redirect()
                ->route('sensors.index')
                ->with(
                    'error',
                    'Both physical sensor slots are already registered.'
                );
        }


        return view(
            'sensors.create',
            compact('availableNumbers')
        );
    }


    /**
     * ============================================================
     * STORE SENSOR
     * ============================================================
     *
     * User only selects sensor number:
     *
     * 1 → SENSOR-1
     * 2 → SENSOR-2
     *
     * The final sensor code is generated by Laravel.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        // ========================================================
        // VALIDATION
        // ========================================================

        $data = $request->validate(
            [
                'sensor_number' => [
                    'required',
                    'integer',

                    Rule::in(
                        self::SUPPORTED_SENSOR_NUMBERS
                    ),
                ],
            ],

            [
                'sensor_number.in' =>
                    'Only sensor number 1 or 2 is supported by the current prototype.',
            ]
        );


        // ========================================================
        // GENERATE SENSOR CODE
        // ========================================================

        $sensorCode = $this->sensorCode(
            (int) $data['sensor_number']
        );


        // ========================================================
        // PREVENT DUPLICATE SENSOR
        // ========================================================

        if (
            Sensor::where(
                'sensor_code',
                $sensorCode
            )->exists()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'sensor_number' =>
                        $sensorCode .
                        ' is already registered.',
                ]);
        }


        // ========================================================
        // CREATE SENSOR
        // ========================================================

        Sensor::create([
            'sensor_code' =>
                $sensorCode,

            'item_id' =>
                null,
        ]);


        return redirect()
            ->route('sensors.index')
            ->with(
                'success',
                $sensorCode .
                ' registered successfully.'
            );
    }


    /**
     * ============================================================
     * DELETE SENSOR
     * ============================================================
     *
     * Assigned sensors cannot be deleted.
     *
     * User must first remove the sensor assignment from the item.
     */
    public function destroy(
        Sensor $sensor
    ): RedirectResponse {

        if ($sensor->item_id !== null) {

            return back()->with(
                'error',
                'This sensor cannot be deleted because it is currently assigned to an item. Unassign it from the item first.'
            );
        }


        $sensorCode =
            $sensor->sensor_code;


        $sensor->delete();


        return redirect()
            ->route('sensors.index')
            ->with(
                'success',
                $sensorCode .
                ' deleted successfully.'
            );
    }


    /**
     * ============================================================
     * GENERATE SENSOR CODE
     * ============================================================
     *
     * Example:
     *
     * 1 → SENSOR-1
     * 2 → SENSOR-2
     */
    private function sensorCode(
        int $number
    ): string {

        return 'SENSOR-' . $number;
    }


    /**
     * ============================================================
     * GET AVAILABLE SENSOR NUMBERS
     * ============================================================
     *
     * Return physical sensor slots that have not yet been
     * registered.
     */
    private function availableSensorNumbers()
    {
        $supportedCodes = collect(
            self::SUPPORTED_SENSOR_NUMBERS
        )
            ->map(
                fn (int $number) =>
                    $this->sensorCode($number)
            )
            ->all();


        $registeredCodes = Sensor::whereIn(
            'sensor_code',
            $supportedCodes
        )
            ->pluck('sensor_code')
            ->all();


        return collect(
            self::SUPPORTED_SENSOR_NUMBERS
        )
            ->reject(
                fn (int $number) =>
                    in_array(
                        $this->sensorCode($number),
                        $registeredCodes,
                        true
                    )
            )
            ->values();
    }
}