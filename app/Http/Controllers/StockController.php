<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Display current stock levels.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'all');

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
        ->orderBy('item_name')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Summary Statistics
        |--------------------------------------------------------------------------
        |
        | Important:
        | No reading is NOT treated as out of stock.
        |
        */

        $totalItems = $items->count();

    $inStockCount = $items->filter(function ($item) {
    return $item->stockStatus() === 'in_stock';
    })->count();

    $lowStockCount = $items->filter(function ($item) {
    return $item->stockStatus() === 'low_stock';
    })->count();

    $outOfStockCount = $items->filter(function ($item) {
    return $item->stockStatus() === 'out_of_stock';
    })->count();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $searchLower = strtolower($search);

            $items = $items->filter(function ($item) use ($searchLower) {

                $sensorCode =
                    $item->sensor?->sensor_code ?? '';

                $location =
                    $item->location?->label() ?? '';

                return str_contains(
                    strtolower($item->item_name),
                    $searchLower
                )
                || str_contains(
                    strtolower($item->item_code),
                    $searchLower
                )
                || str_contains(
                    strtolower($sensorCode),
                    $searchLower
                )
                || str_contains(
                    strtolower($location),
                    $searchLower
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $status,
                [
                    'in_stock',
                    'low_stock',
                    'out_of_stock',
                    'no_data',
                ],
                true
            )
        ) {

            $items = $items->filter(
                function ($item) use ($status) {

                    if ($status === 'no_data') {
                        return $item->stockStatus() === 'no_data';
                    }

                    return $item->stockStatus() === $status;
                }
            );
        }


        return view(
            'stock.index',
            [
                'items' => $items->values(),

                'totalItems' =>
                    $totalItems,

                'inStockCount' =>
                    $inStockCount,

                'lowStockCount' =>
                    $lowStockCount,

                'outOfStockCount' =>
                    $outOfStockCount,

                'search' =>
                    $search,

                'status' =>
                    $status,
            ]
        );
    }
}