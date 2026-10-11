<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function index(Request $request): View
    {
        $items = Item::whereHas('sensor')
            ->orderBy('item_name')
            ->get();

        /*
         * Build a lightweight date map for the custom calendar.
         * Example:
         * [
         *     1 => ['2026-09-14', '2026-09-16'],
         *     2 => ['2026-09-15'],
         * ]
         *
         * This lets the calendar show a small dot immediately when the
         * selected item has history on a particular date.
         */
        $availableDatesByItem = History::query()
            ->select(
                'item_id',
                DB::raw('DATE(created_at) as history_date')
            )
            ->distinct()
            ->orderBy('history_date')
            ->get()
            ->groupBy('item_id')
            ->map(
                fn ($rows) => $rows
                    ->pluck('history_date')
                    ->values()
                    ->all()
            )
            ->all();

        // Keep the page empty until an item is selected.
        $histories = collect();

        if ($request->filled('item_id')) {
            $query = History::with('item')
                ->where('item_id', $request->item_id);

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            // Five rows per page keeps the History screen compact and
            // uses the same reusable pagination component as Items.
            $histories = $query
                ->orderByDesc('created_at')
                ->paginate(10)
                ->withQueryString();
        }

        return view('history.index', [
            'items' => $items,
            'histories' => $histories,
            'availableDatesByItem' => $availableDatesByItem,
        ]);
    }
}
