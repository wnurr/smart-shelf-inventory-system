<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Location;
use App\Models\Sensor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Support\TextFormatter;

class ItemController extends Controller
{
    /**
     * Display all items.
     */
    public function index(Request $request): View
{
    /*
    |--------------------------------------------------------------------------
    | Search & Sort
    |--------------------------------------------------------------------------
    */

    $search = trim(
        (string) $request->input('search', '')
    );

    $sort = $request->input('sort', 'asc');

    // Prevent invalid sort values
    if (! in_array($sort, ['asc', 'desc'], true)) {
        $sort = 'asc';
    }


    /*
    |--------------------------------------------------------------------------
    | Item Summary
    |--------------------------------------------------------------------------
    */

    $totalItems = Item::count();

    $linkedSensors = Item::whereHas('sensor')
        ->count();

    $unlinkedItems = Item::whereDoesntHave('sensor')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | Load Items
    |--------------------------------------------------------------------------
    */

    $items = Item::with([
        'location',
        'sensor',
        'latestReading',
    ])
        ->when(
            $search !== '',
            function ($query) use ($search) {

                $query->where(
                    function ($searchQuery) use ($search) {

                        $searchQuery
                            ->where(
                                'item_name',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'item_code',
                                'like',
                                '%' . $search . '%'
                            );
                    }
                );
            }
        )
        ->orderBy('item_name', $sort)
        ->paginate(5)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | Available Locations For Add Item
    |--------------------------------------------------------------------------
    */

    $locations = Location::whereDoesntHave('item')
        ->orderBy('aisle')
        ->orderBy('bay')
        ->orderBy('shelf')
        ->orderBy('bin')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Available Sensors For Add Item
    |--------------------------------------------------------------------------
    */

    $sensors = Sensor::whereNull('item_id')
        ->orderBy('sensor_code')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'items.index',
        compact(
            'items',
            'locations',
            'sensors',
            'search',
            'sort',
            'totalItems',
            'linkedSensors',
            'unlinkedItems'
        )
    );
}


    /**
     * Show Add Item form.
     */
    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Only available locations
        |--------------------------------------------------------------------------
        */

        $locations = Location::whereDoesntHave('item')
            ->orderBy('aisle')
            ->orderBy('bay')
            ->orderBy('shelf')
            ->orderBy('bin')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Only unassigned sensors
        |--------------------------------------------------------------------------
        */

        $sensors = Sensor::whereNull('item_id')
            ->orderBy('sensor_code')
            ->get();


        return view(
            'items.create',
            compact(
                'locations',
                'sensors'
            )
        );
    }


    /**
     * Store new Item.
     */
    public function store(
        Request $request
    ): RedirectResponse {

        $data = $request->validate([

            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'item_code' => [
                'required',
                'string',
                'max:255',
                'unique:items,item_code',
            ],

            'min_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'location_id' => [
                'required',
                'exists:locations,id',
            ],

            'sensor_id' => [
                'nullable',
                'exists:sensors,id',
            ],

        ]);

        $data['item_name'] = TextFormatter::titleCase(
    $data['item_name']
);

$data['item_code'] = TextFormatter::uppercase(
    $data['item_code']
);


        DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Lock Selected Location
            |--------------------------------------------------------------------------
            |
            | This protects against two users assigning the same location
            | at approximately the same time.
            |
            */

            $location = Location::lockForUpdate()
                ->findOrFail(
                    $data['location_id']
                );


            /*
            |--------------------------------------------------------------------------
            | Check Location Availability
            |--------------------------------------------------------------------------
            */

            if ($location->item()->exists()) {

                throw ValidationException::withMessages([
                    'location_id' =>
                        'This location is already occupied by another item.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Create Item
            |--------------------------------------------------------------------------
            */

            $item = Item::create([

                'item_name' =>
                    $data['item_name'],

                'item_code' =>
                    $data['item_code'],

                'date_added' =>
                    now(),

                'min_stock' =>
                    $data['min_stock'],

                'location_id' =>
                    $location->id,

            ]);


            /*
            |--------------------------------------------------------------------------
            | Assign Sensor
            |--------------------------------------------------------------------------
            */

            if (! empty(
                $data['sensor_id']
            )) {

                $sensor = Sensor::lockForUpdate()
                    ->findOrFail(
                        $data['sensor_id']
                    );


                /*
                |--------------------------------------------------------------------------
                | Sensor must still be available
                |--------------------------------------------------------------------------
                */

                if (
                    $sensor->item_id !== null
                ) {

                    throw ValidationException::withMessages([
                        'sensor_id' =>
                            'This sensor has already been assigned to another item.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Assign Sensor
                |--------------------------------------------------------------------------
                */

                $sensor->update([
                    'item_id' =>
                        $item->id,
                ]);
            }
        });


        return redirect()
            ->route('items.index')
            ->with(
                'success',
                'Item added successfully!'
            );
    }


    /**
     * Display Item details.
     */
    public function show(
        Item $item
    ): View {

        $item->load([
            'location',
            'sensor',

            'inventoryReadings' =>
                fn ($query) =>
                    $query
                        ->latest('recorded_at')
                        ->limit(20),
        ]);


        /*
        |--------------------------------------------------------------------------
        | Locations Available for Editing
        |--------------------------------------------------------------------------
        |
        | Show:
        | 1. Locations not assigned to another item
        | 2. This item's current location
        |
        */

        $locations = Location::where(function ($query) use ($item) {

            $query
                ->whereDoesntHave('item')
                ->orWhereHas(
                    'item',
                    function ($itemQuery) use ($item) {

                        $itemQuery->where(
                            'id',
                            $item->id
                        );
                    }
                );

        })
            ->orderBy('aisle')
            ->orderBy('bay')
            ->orderBy('shelf')
            ->orderBy('bin')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Available Sensors for Editing
        |--------------------------------------------------------------------------
        |
        | Show:
        | 1. Unassigned sensors
        | 2. Current item's sensor
        |
        */

        $sensors = Sensor::where(
            function ($query) use ($item) {

                $query
                    ->whereNull('item_id')
                    ->orWhere(
                        'item_id',
                        $item->id
                    );
            }
        )
            ->orderBy('sensor_code')
            ->get();


        return view(
            'items.show',
            compact(
                'item',
                'locations',
                'sensors'
            )
        );
    }


    /**
     * Show Edit Item form.
     */
    public function edit(
        Item $item
    ): View {

        $item->load([
            'location',
            'sensor',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Locations Available for Editing
        |--------------------------------------------------------------------------
        |
        | Show:
        |
        | 1. Locations not assigned to another item
        | 2. This item's current location
        |
        */

        $locations = Location::where(function ($query) use ($item) {

            $query
                ->whereDoesntHave('item')

                ->orWhereHas(
                    'item',
                    function ($itemQuery) use ($item) {

                        $itemQuery->where(
                            'id',
                            $item->id
                        );
                    }
                );

        })
            ->orderBy('aisle')
            ->orderBy('bay')
            ->orderBy('shelf')
            ->orderBy('bin')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Available Sensors
        |--------------------------------------------------------------------------
        |
        | Show:
        |
        | 1. Unassigned sensors
        | 2. Current item's sensor
        |
        */

        $sensors = Sensor::where(
            function ($query) use ($item) {

                $query
                    ->whereNull('item_id')

                    ->orWhere(
                        'item_id',
                        $item->id
                    );

            }
        )
            ->orderBy('sensor_code')
            ->get();


        return view(
            'items.edit',
            compact(
                'item',
                'locations',
                'sensors'
            )
        );
    }


    /**
     * Update Item.
     */
    public function update(
        Request $request,
        Item $item
    ): RedirectResponse {

        $data = $request->validate([

            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'item_code' => [
                'required',
                'string',
                'max:255',
                "unique:items,item_code,{$item->id}",
            ],

            'min_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'location_id' => [
                'required',
                'exists:locations,id',
            ],

            'sensor_id' => [
                'nullable',
                'exists:sensors,id',
            ],

        ]);


        $data['item_name'] = TextFormatter::titleCase(
            $data['item_name']
        );

        $data['item_code'] = TextFormatter::uppercase(
            $data['item_code']
        );


        DB::transaction(
            function () use (
                $data,
                $item
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock selected Location
                |--------------------------------------------------------------------------
                */

                $location = Location::lockForUpdate()
                    ->findOrFail(
                        $data['location_id']
                    );


                /*
                |--------------------------------------------------------------------------
                | Check whether another Item owns this Location
                |--------------------------------------------------------------------------
                */

                $locationUsedByAnotherItem =
                    Item::where(
                        'location_id',
                        $location->id
                    )
                        ->where(
                            'id',
                            '!=',
                            $item->id
                        )
                        ->exists();


                if (
                    $locationUsedByAnotherItem
                ) {

                    throw ValidationException::withMessages([
                        'location_id' =>
                            'This location is already occupied by another item.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Current Sensor
                |--------------------------------------------------------------------------
                */

                $currentSensor =
                    $item->sensor;


                $newSensorId =
                    $data['sensor_id']
                    ?? null;


                /*
                |--------------------------------------------------------------------------
                | Validate New Sensor
                |--------------------------------------------------------------------------
                */

                $newSensor = null;


                if ($newSensorId) {

                    $newSensor =
                        Sensor::lockForUpdate()
                            ->findOrFail(
                                $newSensorId
                            );


                    if (
                        $newSensor->item_id !== null
                        &&
                        $newSensor->item_id
                            !== $item->id
                    ) {

                        throw ValidationException::withMessages([
                            'sensor_id' =>
                                'This sensor is already assigned to another item.',
                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Update Item
                |--------------------------------------------------------------------------
                */

                $item->update([

                    'item_name' =>
                        $data['item_name'],

                    'item_code' =>
                        $data['item_code'],

                    'min_stock' =>
                        $data['min_stock'],

                    'location_id' =>
                        $location->id,

                ]);


                /*
                |--------------------------------------------------------------------------
                | Sensor selected
                |--------------------------------------------------------------------------
                */

                if ($newSensor) {

                    /*
                    |--------------------------------------------------------------------------
                    | Remove previous Sensor if changed
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $currentSensor
                        &&
                        $currentSensor->id
                            !== $newSensor->id
                    ) {

                        $currentSensor->update([
                            'item_id' => null,
                        ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Assign selected Sensor
                    |--------------------------------------------------------------------------
                    */

                    $newSensor->update([
                        'item_id' =>
                            $item->id,
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | No Sensor selected
                |--------------------------------------------------------------------------
                */

                else {

                    if ($currentSensor) {

                        $currentSensor->update([
                            'item_id' =>
                                null,
                        ]);
                    }
                }
            }
        );


        return redirect()
            ->route('items.index')
            ->with(
                'success',
                'Item updated successfully!'
            );
    }


    /**
     * Delete Item.
     */
    public function destroy(
        Item $item
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Release assigned Sensor
        |--------------------------------------------------------------------------
        */

        if ($item->sensor) {

            $item->sensor->update([
                'item_id' => null,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Item
        |--------------------------------------------------------------------------
        |
        | The Location becomes automatically available because
        | availability is determined through items.location_id.
        |
        */

        $item->delete();


        return redirect()
            ->route('items.index')
            ->with(
                'success',
                'Item removed.'
            );
    }
}