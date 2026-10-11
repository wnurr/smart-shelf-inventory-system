@extends('layouts.app')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Report Number
    |--------------------------------------------------------------------------
    */

    $reportNumber =
        'SR-' .
        $generatedAt->format('Ymd-His');


    /*
    |--------------------------------------------------------------------------
    | Stock Summary
    |--------------------------------------------------------------------------
    */

    $inStock = 0;
    $lowStock = 0;
    $outOfStock = 0;
    $noData = 0;


    foreach ($items as $summaryItem) {

        $summaryStatus = $summaryItem->stockStatus();

        if ($summaryStatus === 'no_data') {
            $noData++;
        } elseif ($summaryStatus === 'out_of_stock') {
            $outOfStock++;
        } elseif ($summaryStatus === 'low_stock') {
            $lowStock++;
        } else {
            $inStock++;
        }

    }

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
       TOP BAR
    ========================================================= */

    .report-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
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

        background: #ffffff;
        color: #111827;
    }

    .print-button:hover {
        background: #f9fafb;
    }

    .download-button {
        border: 1px solid #214f68;

        background: #214f68;
        color: #ffffff;
    }

    .download-button:hover {
        background: #173e53;
    }


    /* =========================================================
       PREVIEW BACKGROUND
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

        background: #ffffff;
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
       DOCUMENT HEADER
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
       INFORMATION SECTION
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
       STOCK TABLE
    ========================================================= */

    .stock-table {
        width: 100%;

        border-collapse: collapse;

        font-size: 10px;
    }

    .stock-table thead {
        border-top: 1px solid #111111;
        border-bottom: 1px solid #111111;
    }

    .stock-table th {
        padding: 9px 5px;

        text-align: left;
        font-weight: 700;
    }

    .stock-table td {
        padding: 12px 5px;

        border-bottom: 1px solid #eeeeee;

        vertical-align: middle;
    }

    .stock-table tbody tr:last-child td {
        border-bottom: 1px solid #111111;
    }

    .text-center {
        text-align: center !important;
    }

    .nowrap {
        white-space: nowrap;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .stock-status {
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-out {
        color: #b91c1c;
    }

    .status-low {
        color: #b45309;
    }

    .status-in {
        color: #166534;
    }

    .status-none {
        color: #6b7280;
    }


    /* =========================================================
       BOTTOM SECTION
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
        width: 100% !important;
        height: auto !important;
        background: #fff !important;
    }

    /* Remove normal application layout from print flow */
    body * {
        visibility: hidden !important;
    }

    .a4-paper,
    .a4-paper * {
        visibility: visible !important;
    }

    /* Detach report completely from dashboard layout */
    .a4-paper {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;

        width: 210mm !important;
        height: 297mm !important;
        min-height: 0 !important;

        margin: 0 !important;
        padding: 15mm !important;

        box-sizing: border-box !important;
        box-shadow: none !important;
        background: #fff !important;

        overflow: hidden !important;
        transform: none !important;
    }

    .document-header,
    .information-grid,
    .stock-table,
    .bottom-section,
    .document-footer {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .stock-table {
        table-layout: fixed !important;
    }
}


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 768px) {

        .report-toolbar {
            flex-direction: column;
        }

        .report-actions {
            width: 100%;
        }

        .report-button {
            flex: 1;
        }

        .preview-background {
            padding: 20px;
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
                href="{{ route('stock.index', [
                    'search' => $search !== '' ? $search : null,
                    'status' => $status !== 'all' ? $status : null,
                ]) }}"
                class="back-link"
            >
                ← Back to Stock
            </a>

            <h1 class="page-title">
                Stock Report Preview
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
                href="{{ route('reports.stock.download', [
                    'search' => $search !== '' ? $search : null,
                    'status' => $status !== 'all' ? $status : null,
                ]) }}"
                class="report-button download-button"
            >
                Download PDF
            </a>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- A4 PREVIEW --}}
    {{-- ========================================================= --}}

    <div class="preview-background">

        <div class="a4-paper">


            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div class="document-header">

                <div>

                    <h2 class="document-title">
                        STOCK REPORT
                    </h2>

                </div>


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
            {{-- INFORMATION --}}
            {{-- ================================================= --}}

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
                        Stock Inventory Report
                    </div>

                </div>



                {{-- REPORT DETAILS --}}

                <div>

                    <div class="information-title">
                        REPORT DETAILS
                    </div>


                    <div class="information-row">
                        <strong>Report Type:</strong>
                        Current Stock
                    </div>


                    <div class="information-row">

                        <strong>Generated:</strong>

                        {{ $generatedAt->format(
                            'd M Y, h:i A'
                        ) }}

                    </div>


                    <div class="information-row">

                        <strong>Total Items:</strong>

                        {{ $items->count() }}

                    </div>


                    <div class="information-row">
                        <strong>Status Filter:</strong>
                        {{ $statusLabel }}
                    </div>


                    <div class="information-row">
                        <strong>Search:</strong>
                        {{ $search !== '' ? $search : 'None' }}
                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- STOCK TABLE --}}
            {{-- ================================================= --}}

            <table class="stock-table">

                <thead>

                    <tr>

                        <th style="width: 6%;">
                            No.
                        </th>

                        <th style="width: 13%;">
                            Item Code
                        </th>

                        <th style="width: 21%;">
                            Item Description
                        </th>

                        <th style="width: 17%;">
                            Location
                        </th>

                        <th
                            class="text-center"
                            style="width: 9%;"
                        >
                            Qty
                        </th>

                        <th
                            class="text-center"
                            style="width: 10%;"
                        >
                            Min.
                        </th>

                        <th style="width: 24%;">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($items as $item)

                        @php
                            $reading =
                                $item->latestReading;
                        @endphp


                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <td class="nowrap">
                                {{ $item->item_code }}
                            </td>


                            <td>
                                {{ $item->item_name }}
                            </td>


                            <td>
                                {{
                                    $item->location?->label()
                                    ?? '—'
                                }}
                            </td>


                            <td class="text-center">

                                {{
                                    $reading?->quantity
                                    ?? '—'
                                }}

                            </td>


                            <td class="text-center">
                                {{ $item->min_stock }}
                            </td>


                            <td>

                                @if(!$reading)

                                    <span
                                        class="
                                            stock-status
                                            status-none
                                        "
                                    >
                                        NO DATA
                                    </span>


                                @elseif(
                                    $reading->stock_status
                                    === 'out_of_stock'
                                )

                                    <span
                                        class="
                                            stock-status
                                            status-out
                                        "
                                    >
                                        OUT OF STOCK
                                    </span>


                                @elseif(
                                    $reading->stock_status
                                    === 'low_stock'
                                )

                                    <span
                                        class="
                                            stock-status
                                            status-low
                                        "
                                    >
                                        LOW STOCK
                                    </span>


                                @else

                                    <span
                                        class="
                                            stock-status
                                            status-in
                                        "
                                    >
                                        IN STOCK
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center"
                            >
                                No stock records available.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>



            {{-- ================================================= --}}
            {{-- BOTTOM --}}
            {{-- ================================================= --}}

            <div class="bottom-section">


                {{-- NOTE --}}

                <div class="report-note">

                    <div class="note-title">
                        REPORT NOTE
                    </div>

                    This report represents the latest
                    available stock reading for each item
                    at the time the report was generated.

                    Items without a recorded inventory
                    reading are marked as
                    <strong>NO DATA</strong>.

                </div>



                {{-- SUMMARY --}}

                <div class="summary-box">

                    <div class="summary-title">
                        STOCK SUMMARY
                    </div>


                    <div class="summary-row">

                        <span>
                            In Stock
                        </span>

                        <strong>
                            {{ $inStock }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Low Stock
                        </span>

                        <strong>
                            {{ $lowStock }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            Out of Stock
                        </span>

                        <strong>
                            {{ $outOfStock }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>
                            No Data
                        </span>

                        <strong>
                            {{ $noData }}
                        </strong>

                    </div>


                    <div
                        class="
                            summary-row
                            summary-total
                        "
                    >

                        <span>
                            Total Items
                        </span>

                        <strong>
                            {{ $items->count() }}
                        </strong>

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}

            <div class="document-footer">

                Generated by Smart Shelf Inventory System

                <br>

                {{ $generatedAt->format(
                    'd M Y, h:i A'
                ) }}

            </div>

        </div>

    </div>

</div>

@endsection