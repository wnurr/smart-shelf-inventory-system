<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sensor;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index(): View
    {
        return view('dashboard');
    }


    /**
     * Return latest dashboard statistics.
     */
    public function stats(): JsonResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Load Items With Latest Reading
        |--------------------------------------------------------------------------
        */
        $items = Item::with('latestReading')
            ->orderBy('item_name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Total Items
        |--------------------------------------------------------------------------
        */
        $totalItems = $items->count();


        /*
        |--------------------------------------------------------------------------
        | Low Stock
        |--------------------------------------------------------------------------
        |
        | Count items whose latest reading is:
        | - low_stock
        | - out_of_stock
        |
        | Items without readings are not counted.
        |
        */
        $lowStockItems = $items
    ->filter(fn ($item) => $item->isLowStock())
    ->values();

$lowStock = $lowStockItems->count();


$itemsWithReadings = $items->filter(
    fn ($item) => $item->latestReading !== null
)->count();

$itemsWithoutReadings = $totalItems - $itemsWithReadings;

$lastReading = $items
    ->pluck('latestReading')
    ->filter()
    ->sortByDesc('recorded_at')
    ->first();

$lastReadingAt = $lastReading
    ? $lastReading->recorded_at
    : null;


        /*
        |--------------------------------------------------------------------------
        | Total Sensors
        |--------------------------------------------------------------------------
        */
        $totalSensors = Sensor::count();


        /*
        |--------------------------------------------------------------------------
        | Current Stock Overview
        |--------------------------------------------------------------------------
        |
        | Use the latest inventory reading for each item.
        |
        | If an item does not have any reading yet,
        | its current quantity is displayed as 0.
        |
        */
       $stockOverview = $items->map(function ($item) {

    return [
        'id' => $item->id,

        'item_name' => $item->item_name,

        'item_code' => $item->item_code,

        'quantity' => $item->latestReading
            ? (int) $item->latestReading->quantity
            : null,

        'min_stock' => (int) $item->min_stock,

        'is_low_stock' => $item->isLowStock(),

        'has_reading' => $item->latestReading !== null,

        'url' => route('items.show', $item),
    ];

})->values();


        /*
        |--------------------------------------------------------------------------
        | JSON Response
        |--------------------------------------------------------------------------
        */
        return response()->json([

            'total_items' => $totalItems,

            'low_stock' => $lowStock,

            'total_sensors' => $totalSensors,

            'stock_overview' => $stockOverview,

            'updated_at' => now()->format('h:i:s A'),

            'items_with_readings' => $itemsWithReadings,

            'items_without_readings' => $itemsWithoutReadings,

            'last_reading_at' => $lastReadingAt,

        ]);
    }
}