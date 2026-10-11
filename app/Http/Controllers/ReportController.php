<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Item;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Stock Report Preview
    |--------------------------------------------------------------------------
    */

    public function stock(Request $request)
    {
        return view(
            'reports.stock-preview',
            $this->getStockReportData($request)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Download Stock Report PDF
    |--------------------------------------------------------------------------
    */

    public function downloadStock(Request $request)
    {
        $reportData = $this->getStockReportData($request);

        $pdf = Pdf::loadView(
            'reports.stock',
            $reportData
        );

        $pdf->setPaper(
            'a4',
            'portrait'
        );

        return $pdf->download(
            'stock-report-' .
            $reportData['generatedAt']->format('Y-m-d-His') .
            '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | History Report Preview
    |--------------------------------------------------------------------------
    */

    public function history(Request $request)
    {
        return view(
            'reports.history-preview',
            $this->getHistoryReportData($request)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Download History Report PDF
    |--------------------------------------------------------------------------
    */

    public function downloadHistory(Request $request)
    {
        $reportData = $this->getHistoryReportData($request);

        $pdf = Pdf::loadView(
            'reports.history',
            $reportData
        );

        $pdf->setPaper(
            'a4',
            'portrait'
        );

        $filename =
            'history-report-' .
            $reportData['item']->item_code;

        if (!empty($reportData['selectedDate'])) {
            $filename .= '-' . $reportData['selectedDate'];
        }

        $filename .=
            '-' .
            $reportData['generatedAt']->format('His') .
            '.pdf';

        return $pdf->download($filename);
    }


    /*
    |--------------------------------------------------------------------------
    | Stock Report Data
    |--------------------------------------------------------------------------
    */

    private function getStockReportData(Request $request): array
    {
        $search = trim((string) $request->input('search', ''));
        $status = (string) $request->input('status', 'all');

        $allowedStatuses = [
            'all',
            'in_stock',
            'low_stock',
            'out_of_stock',
            'no_data',
        ];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'all';
        }

        $items = Item::with([
            'location',
            'sensor',
            'latestReading',
        ])
            ->orderBy('item_name')
            ->get();

        if ($search !== '') {
            $searchLower = strtolower($search);

            $items = $items->filter(function ($item) use ($searchLower) {
                $sensorCode = $item->sensor?->sensor_code ?? '';
                $location = $item->location?->label() ?? '';

                return str_contains(strtolower($item->item_name), $searchLower)
                    || str_contains(strtolower($item->item_code), $searchLower)
                    || str_contains(strtolower($sensorCode), $searchLower)
                    || str_contains(strtolower($location), $searchLower);
            });
        }

        if ($status !== 'all') {
            $items = $items->filter(
                fn ($item) => $item->stockStatus() === $status
            );
        }

        $items = $items->values();

        $statusLabels = [
            'all' => 'All Status',
            'in_stock' => 'In Stock',
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock',
            'no_data' => 'No Data',
        ];

        return [
            'items' => $items,
            'generatedBy' => Auth::user(),
            'generatedAt' => now(),
            'search' => $search,
            'status' => $status,
            'statusLabel' => $statusLabels[$status],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | History Report Data
    |--------------------------------------------------------------------------
    |
    | The History screen filters by:
    | - one required item
    | - one optional date
    |
    | The same dataset is reused by both preview and PDF so the
    | generated report always matches the user's current filter.
    |
    */

    private function getHistoryReportData(Request $request): array
    {
        $validated = $request->validate([
            'item_id' => [
                'required',
                'exists:items,id',
            ],

            'date' => [
                'nullable',
                'date',
            ],
        ]);

        $item = Item::findOrFail(
            $validated['item_id']
        );

        $selectedDate = $validated['date'] ?? null;

        $query = History::with([
            'item',
            'user',
        ])
            ->where(
                'item_id',
                $item->id
            );

        if ($selectedDate) {
            $query->whereDate(
                'created_at',
                $selectedDate
            );
        }

        $histories = $query
            ->orderByDesc('created_at')
            ->get();

        return [
            'item' => $item,
            'histories' => $histories,
            'selectedDate' => $selectedDate,
            'generatedBy' => Auth::user(),
            'generatedAt' => now(),
        ];
    }
}
