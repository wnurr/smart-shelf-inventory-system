@extends('layouts.app')

@section('content')

@php

    $reportNumber =
        'HR-' .
        $generatedAt->format('Ymd-His');

    $increases = 0;
    $decreases = 0;
    $unchanged = 0;

    foreach ($histories as $record) {

        $previous = $record->previous_quantity;
        $new = $record->new_quantity;

        if ($previous !== null && $new !== null) {

            if ($new > $previous) {
                $increases++;
            } elseif ($new < $previous) {
                $decreases++;
            } else {
                $unchanged++;
            }

        }

    }
$historyPages = $histories->chunk(15);

@endphp


<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .report-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px;
    }


    /* =========================================================
       TOOLBAR
    ========================================================= */

    .report-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;

        margin-bottom: 25px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 8px;

        color: #64748b;

        font-size: 14px;
        text-decoration: none;
    }

    .back-link:hover {
        color: #1f4f68;
    }

    .page-title {
        margin: 0;

        color: #111827;

        font-size: 24px;
        font-weight: 700;
    }

    .page-description {
        margin-top: 5px;

        color: #6b7280;

        font-size: 14px;
    }


    /* =========================================================
       BUTTONS
    ========================================================= */

    .report-actions {
        display: flex;
        gap: 10px;
    }

    .report-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        padding: 10px 18px;

        border-radius: 8px;

        font-size: 14px;
        font-weight: 600;

        cursor: pointer;
        text-decoration: none;

        transition: 0.2s;
    }

    .print-button {
        border: 1px solid #d1d5db;

        background: white;
        color: #111827;
    }

    .print-button:hover {
        background: #f9fafb;
    }

    .download-button {
        border: 1px solid #214f68;

        background: #214f68;
        color: white;
    }

    .download-button:hover {
        background: #173e53;
    }


    /* =========================================================
       PREVIEW
    ========================================================= */

    .preview-background {
        padding: 45px 20px;

        border-radius: 12px;

        background: #d1d5db;

        overflow-x: auto;
    }


    /* =========================================================
       A4 PAPER
    ========================================================= */

    .a4-paper {
        width: 794px;
        min-height: 1123px;

        margin: 0 auto;
        padding: 70px 65px;

        background: white;
        color: #111111;

        box-shadow:
            0 4px 18px
            rgba(0, 0, 0, 0.18);

        font-family:
            Arial,
            Helvetica,
            sans-serif;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .document-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        margin-bottom: 42px;
    }

    .document-title {
        margin: 0;

        font-size: 43px;
        font-weight: 400;
        letter-spacing: -1px;
    }

    .company-block {
        min-width: 235px;

        text-align: right;
    }

    .company-name {
        margin-bottom: 7px;

        font-size: 17px;
        font-weight: 700;
    }

    .company-subtitle {
        margin-bottom: 14px;

        color: #555555;

        font-size: 11px;
        letter-spacing: 1px;
    }

    .report-meta {
        font-size: 11px;
        line-height: 1.7;
    }

    .meta-label {
        font-weight: 700;
    }


    /* =========================================================
       INFORMATION
    ========================================================= */

    .information-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 70px;

        margin-bottom: 42px;
    }

    .information-title {
        margin-bottom: 10px;
        padding-bottom: 6px;

        border-bottom: 1px solid #111111;

        font-size: 12px;
        font-weight: 700;
    }

    .information-row {
        margin-bottom: 5px;

        font-size: 11px;
        line-height: 1.5;
    }

    .information-main {
        font-weight: 700;
    }

    .information-muted {
        color: #555555;
    }


    /* =========================================================
       HISTORY TABLE
    ========================================================= */

    .history-table {
        width: 100%;

        border-collapse: collapse;

        font-size: 9px;
    }

    .history-table thead {
        border-top: 1px solid #111111;
        border-bottom: 1px solid #111111;
    }

    .history-table th {
        padding: 9px 4px;

        text-align: left;
        font-weight: 700;
    }

    .history-table td {
        padding: 11px 4px;

        border-bottom: 1px solid #eeeeee;

        vertical-align: middle;
    }

    .history-table tbody tr:last-child td {
        border-bottom: 1px solid #111111;
    }

    .center {
        text-align: center !important;
    }

    .quantity-change {
        white-space: nowrap;
        font-weight: 600;
    }

    .increase {
        color: #166534;
    }

    .decrease {
        color: #b91c1c;
    }

    .unchanged {
        color: #6b7280;
    }


    /* =========================================================
       BOTTOM
    ========================================================= */

    .bottom-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;

        gap: 70px;

        margin-top: 35px;
    }

    .report-note {
        width: 52%;

        color: #555555;

        font-size: 10px;
        line-height: 1.7;
    }

    .note-title {
        margin-bottom: 8px;

        color: #111111;

        font-size: 11px;
        font-weight: 700;
    }


    /* =========================================================
       SUMMARY
    ========================================================= */

    .summary-box {
        width: 260px;

        font-size: 11px;
    }

    .summary-title {
        margin-bottom: 8px;
        padding-bottom: 7px;

        border-bottom: 1px solid #111111;

        font-size: 12px;
        font-weight: 700;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;

        padding: 5px 0;
    }

    .summary-total {
        margin-top: 5px;
        padding-top: 8px;

        border-top: 1px solid #111111;
        border-bottom: 2px solid #111111;

        font-weight: 700;
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .document-footer {
        margin-top: 70px;

        color: #9ca3af;

        font-size: 10px;
        text-align: center;
    }



    [x-cloak] {
        display: none !important;
    }

    /* =========================================================
       PREVIEW PAGINATION
    ========================================================= */

    .preview-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;

        margin-top: 22px;
    }

    .pagination-button {
        min-width: 110px;
        padding: 9px 14px;

        border: 1px solid #cbd5e1;
        border-radius: 8px;

        background: #ffffff;
        color: #334155;

        font-size: 13px;
        font-weight: 600;

        cursor: pointer;
        transition: 0.2s;
    }

    .pagination-button:hover:not(:disabled) {
        border-color: #284B63;
        color: #284B63;
    }

    .pagination-button:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    .pagination-info {
        min-width: 100px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }


    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

    @page {
        size: A4 portrait;
        margin: 0;
    }

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;

        background: white !important;
    }

    /*
    |--------------------------------------------------------------------------
    | Hide Laravel Interface
    |--------------------------------------------------------------------------
    */

    body * {
        visibility: hidden;
    }

    .preview-background,
    .preview-background * {
        visibility: visible;
    }


    /*
    |--------------------------------------------------------------------------
    | Report Container
    |--------------------------------------------------------------------------
    */

.preview-background {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    background: white !important;
    border-radius: 0 !important;
    overflow: visible !important;
}


    /*
    |--------------------------------------------------------------------------
    | Individual A4 Page
    |--------------------------------------------------------------------------
    */

.a4-paper {
    display: block !important;
    position: relative;

    width: 210mm !important;
    height: 297mm !important;
    box-sizing: border-box !important;

    margin: 0 !important;
    padding: 15mm 15mm 18mm 15mm !important;

    background: white !important;
    box-shadow: none !important;

    overflow: hidden !important;

    page-break-after: always;
    break-after: page;
}


    /*
    |--------------------------------------------------------------------------
    | Last Page
    |--------------------------------------------------------------------------
    */

    .a4-paper:last-child {
        page-break-after: auto;
        break-after: auto;
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent Table Row Splitting
    |--------------------------------------------------------------------------
    */

    .history-table tr {
        page-break-inside: avoid;
        break-inside: avoid;
    }

    .preview-pagination {
        display: none !important;
    }

}

</style>


<div class="report-page">


    {{-- ========================================================= --}}
    {{-- TOOLBAR --}}
    {{-- ========================================================= --}}

    <div class="report-toolbar">

        <div>

            <a
                href="{{ route('history.index') }}"
                class="back-link"
            >
                ← Back to History
            </a>

            <h1 class="page-title">
                History Report Preview
            </h1>

            <div class="page-description">
                Review the report before downloading or printing.
            </div>

        </div>


        <div class="report-actions">

            <button
                type="button"
                onclick="window.print()"
                class="report-button print-button"
            >
                Print
            </button>

            <a
                href="{{ route('reports.history.download', request()->query()) }}"
                class="report-button download-button"
            >
                Download PDF
            </a>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- PREVIEW --}}
    {{-- ========================================================= --}}

<div
    class="preview-background"
    x-data="{
        currentPage: 0,
        totalPages: {{ max($historyPages->count(), 1) }}
    }"
>

    @forelse($historyPages as $pageIndex => $pageHistories)

        <div
            class="a4-paper"
            x-show="currentPage === {{ $pageIndex }}"
            x-cloak
        >


            {{-- ================================================= --}}
            {{-- DOCUMENT HEADER --}}
            {{-- ================================================= --}}

            <div class="document-header">

                <h2 class="document-title">
                    HISTORY REPORT
                </h2>


                <div class="company-block">

                    <div class="company-name">
                        Smart Shelf Inventory System
                    </div>

                    <div class="company-subtitle">
                        INVENTORY MANAGEMENT
                    </div>


                    <div class="report-meta">

                        <div>
                            <span class="meta-label">
                                Report No.
                            </span>

                            {{ $reportNumber }}
                        </div>

                        <div>
                            <span class="meta-label">
                                Date:
                            </span>

                            {{ $generatedAt->format('d/m/Y') }}
                        </div>

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- REPORT INFORMATION --}}
            {{-- ONLY SHOW ON FIRST PAGE --}}
            {{-- ================================================= --}}

            @if($pageIndex === 0)

                <div class="information-grid">


                    {{-- GENERATED BY --}}

                    <div>

                        <div class="information-title">
                            GENERATED BY
                        </div>

                        <div
                            class="
                                information-row
                                information-main
                            "
                        >
                            {{ $generatedBy->name }}
                        </div>

                        <div
                            class="
                                information-row
                                information-muted
                            "
                        >
                            Smart Shelf Inventory System
                        </div>

                        <div
                            class="
                                information-row
                                information-muted
                            "
                        >
                            Inventory History Report
                        </div>

                    </div>



                    {{-- REPORT DETAILS --}}

                    <div>

                        <div class="information-title">
                            REPORT DETAILS
                        </div>

                        <div class="information-row">
                            <strong>Report Type:</strong>
                            Stock History
                        </div>

                        <div class="information-row">
                            <strong>Item:</strong>
                            {{ $item->item_name }}
                        </div>

                        <div class="information-row">
                            <strong>Item Code:</strong>
                            {{ $item->item_code }}
                        </div>

                        <div class="information-row">
                            <strong>Date Filter:</strong>
                            @if($selectedDate)
                                {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                            @else
                                All Recorded Dates
                            @endif
                        </div>

                        <div class="information-row">
                            <strong>Generated:</strong>
                            {{ $generatedAt->format('d M Y, h:i A') }}
                        </div>

                        <div class="information-row">
                            <strong>Total Records:</strong>
                            {{ $histories->count() }}
                        </div>

                    </div>

                </div>

            @else

                {{-- SMALL CONTINUATION LABEL --}}

                <div class="continuation">

                    Stock History
                    &nbsp;•&nbsp;
                    Continued

                </div>

            @endif



            {{-- ================================================= --}}
            {{-- HISTORY TABLE --}}
            {{-- ================================================= --}}

            <table class="history-table">

                <thead>

                    <tr>

                        <th style="width:5%;">
                            No.
                        </th>

                        <th style="width:18%;">
                            Date / Time
                        </th>

                        <th style="width:19%;">
                            Item
                        </th>

                        <th style="width:18%;">
                            Action
                        </th>

                        <th
                            class="center"
                            style="width:12%;"
                        >
                            Previous
                        </th>

                        <th
                            class="center"
                            style="width:10%;"
                        >
                            New
                        </th>

                        <th style="width:18%;">
                            By
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach(
                        $pageHistories as $recordIndex => $record
                    )

                        @php

                            $previous =
                                $record->previous_quantity;

                            $new =
                                $record->new_quantity;

                            $number =
                                ($pageIndex * 15)
                                + $loop->iteration;

                        @endphp


                        <tr>

                            <td>
                                {{ $number }}
                            </td>


                            <td>

                                {{
                                    $record->created_at
                                        ->format('d M Y')
                                }}

                                <br>

                                {{
                                    $record->created_at
                                        ->format('h:i A')
                                }}

                            </td>


                            <td>

                                <strong>
                                    {{
                                        $record->item?->item_code
                                        ?? '—'
                                    }}
                                </strong>

                                <br>

                                {{
                                    $record->item?->item_name
                                    ?? 'Unknown Item'
                                }}

                            </td>


                            <td>

                                {{
                                    ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $record->action
                                        )
                                    )
                                }}

                            </td>


                            <td class="center">

                                {{ $previous ?? '—' }}

                            </td>


                            <td class="center">

                                @if(
                                    $previous !== null
                                    && $new !== null
                                )

                                    @if($new > $previous)

                                        <span class="increase">

                                            {{ $new }} ↑

                                        </span>

                                    @elseif($new < $previous)

                                        <span class="decrease">

                                            {{ $new }} ↓

                                        </span>

                                    @else

                                        <span class="unchanged">

                                            {{ $new }}

                                        </span>

                                    @endif

                                @else

                                    {{ $new ?? '—' }}

                                @endif

                            </td>


                            <td>

                                {{
                                    $record->user?->name
                                    ?? 'System'
                                }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>



            {{-- ================================================= --}}
            {{-- SUMMARY ONLY ON LAST PAGE --}}
            {{-- ================================================= --}}

            @if($loop->last)

                <div class="bottom-section">

                    <div class="report-note">

                        <div class="note-title">
                            REPORT NOTE
                        </div>

                        This report contains recorded
                        inventory quantity changes within
                        the selected reporting period.

                        <br><br>

                        An increase indicates stock was
                        added, while a decrease indicates
                        stock was removed from the shelf.

                    </div>


                    <div class="summary-box">

                        <div class="summary-title">
                            HISTORY SUMMARY
                        </div>


                        <div class="summary-row">

                            <span>
                                Stock In Events
                            </span>

                            <strong>
                                {{ $increases }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Stock Out Events
                            </span>

                            <strong>
                                {{ $decreases }}
                            </strong>

                        </div>


                        <div class="summary-row">

                            <span>
                                Unchanged
                            </span>

                            <strong>
                                {{ $unchanged }}
                            </strong>

                        </div>


                        <div
                            class="
                                summary-row
                                summary-total
                            "
                        >

                            <span>
                                Total Records
                            </span>

                            <strong>
                                {{ $histories->count() }}
                            </strong>

                        </div>

                    </div>

                </div>

            @endif



            {{-- ================================================= --}}
            {{-- PAGE FOOTER --}}
            {{-- ================================================= --}}

            <div class="page-footer font-size-5 text-gray-500 flex justify-between mt-3">

                <span>
                    Smart Shelf Inventory System
                </span>

                <span>
                    Page
                    {{ $pageIndex + 1 }}
                    of
                    {{ $historyPages->count() }}
                </span>

            </div>


        </div>

    @empty


        {{-- NO RECORD RESULT --}}

        <div class="a4-paper">

            <div class="document-header">

                <h2 class="document-title">
                    HISTORY REPORT
                </h2>

                <div class="company-block">

                    <div class="company-name">
                        Smart Shelf Inventory System
                    </div>

                    <div class="company-subtitle">
                        INVENTORY MANAGEMENT
                    </div>

                </div>

            </div>


            <div
                style="
                    text-align:center;
                    margin-top:100px;
                    color:#6b7280;
                "
            >
                No history records found.
            </div>

        </div>


    @endforelse


    @if($historyPages->count() > 1)

        <div class="preview-pagination">

            <button
                type="button"
                class="pagination-button"
                @click="if (currentPage > 0) currentPage--"
                :disabled="currentPage === 0"
            >
                < Previous
            </button>

            <div class="pagination-info">
                Page
                <span x-text="currentPage + 1"></span>
                of
                {{ $historyPages->count() }}
            </div>

            <button
                type="button"
                class="pagination-button"
                @click="if (currentPage < totalPages - 1) currentPage++"
                :disabled="currentPage >= totalPages - 1"
            >
                Next >
            </button>

        </div>

    @endif

</div>

</div>

@endsection