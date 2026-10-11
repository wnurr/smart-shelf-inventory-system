<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        Stock Report
    </title>


    <style>

        @page {
            margin: 40px 45px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            color: #111111;

            font-family:
                DejaVu Sans,
                sans-serif;

            font-size: 10px;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header-table {
            width: 100%;

            margin-bottom: 38px;

            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .report-title {
            font-size: 31px;
            font-weight: normal;
        }

        .company-block {
            text-align: right;
        }

        .company-name {
            margin-bottom: 5px;

            font-size: 14px;
            font-weight: bold;
        }

        .company-subtitle {
            margin-bottom: 12px;

            color: #555555;

            font-size: 8px;
            letter-spacing: 1px;
        }

        .report-meta {
            font-size: 9px;
            line-height: 1.7;
        }


        /* =====================================================
           INFO
        ===================================================== */

        .info-table {
            width: 100%;

            margin-bottom: 35px;

            border-collapse: collapse;
        }

        .info-table td {
            width: 50%;

            vertical-align: top;
        }

        .info-left {
            padding-right: 35px;
        }

        .info-right {
            padding-left: 35px;
        }

        .info-title {
            margin-bottom: 8px;
            padding-bottom: 5px;

            border-bottom: 1px solid #222222;

            font-size: 10px;
            font-weight: bold;
        }

        .info-row {
            margin-bottom: 4px;

            font-size: 9px;
            line-height: 1.5;
        }

        .info-main {
            font-weight: bold;
        }

        .muted {
            color: #555555;
        }


        /* =====================================================
           STOCK TABLE
        ===================================================== */

        .stock-table {
            width: 100%;

            border-collapse: collapse;

            font-size: 8.5px;
        }

        .stock-table thead {
            border-top: 1px solid #111111;
            border-bottom: 1px solid #111111;
        }

        .stock-table th {
            padding: 7px 4px;

            text-align: left;
            font-weight: bold;
        }

        .stock-table td {
            padding: 9px 4px;

            border-bottom: 1px solid #e5e5e5;

            vertical-align: middle;
        }

        .stock-table tbody tr:last-child td {
            border-bottom: 1px solid #111111;
        }

        .center {
            text-align: center;
        }

        .status {
            font-size: 8px;
            font-weight: bold;
        }

        .out {
            color: #b91c1c;
        }

        .low {
            color: #b45309;
        }

        .in {
            color: #166534;
        }

        .none {
            color: #666666;
        }


        /* =====================================================
           BOTTOM
        ===================================================== */

        .bottom-table {
            width: 100%;

            margin-top: 28px;

            border-collapse: collapse;
        }

        .bottom-table td {
            vertical-align: top;
        }

        .note-cell {
            width: 55%;
            padding-right: 55px;
        }

        .summary-cell {
            width: 45%;
            padding-left: 30px;
        }

        .section-title {
            margin-bottom: 7px;

            font-size: 9px;
            font-weight: bold;
        }

        .note {
            color: #555555;

            font-size: 8px;
            line-height: 1.7;
        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary-title {
            padding-bottom: 6px;

            border-bottom: 1px solid #111111;

            font-size: 10px;
            font-weight: bold;
        }

        .summary-table {
            width: 100%;

            margin-top: 4px;

            border-collapse: collapse;
        }

        .summary-table td {
            padding: 5px 0;

            font-size: 9px;
        }

        .summary-value {
            text-align: right;
            font-weight: bold;
        }

        .total-row td {
            padding-top: 7px;
            padding-bottom: 7px;

            border-top: 1px solid #111111;
            border-bottom: 2px solid #111111;

            font-weight: bold;
        }


        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            margin-top: 60px;

            color: #999999;

            font-size: 8px;
            line-height: 1.6;
            text-align: center;
        }

    
        /* Smart Shelf report visual refresh — print/PDF safe */
        body { color:#243B53; }
        .header-table { margin-bottom:30px; border-bottom:3px solid #F59E0B; padding-bottom:14px; }
        .report-title { color:#243B53; font-size:27px; font-weight:bold; letter-spacing:.4px; }
        .company-name { color:#243B53; }
        .company-subtitle { color:#B96B00; font-weight:bold; letter-spacing:1.4px; }
        .report-meta { color:#64748B; }
        .info-table { margin-bottom:28px; }
        .info-title { color:#243B53; border-bottom:2px solid #E2E8F0; font-size:9px; letter-spacing:.7px; }
        .info-main { color:#243B53; }
        .muted { color:#64748B; }
        .history-table thead, .stock-table thead { color:#FFFFFF; background:#243B53; border:0; }
        .history-table th, .stock-table th { padding:8px 5px; font-size:7.5px; letter-spacing:.25px; }
        .history-table td, .stock-table td { border-bottom:1px solid #E2E8F0; padding:8px 5px; color:#334155; }
        .history-table tbody tr:nth-child(even), .stock-table tbody tr:nth-child(even) { background:#F8FAFC; }
        .history-table tbody tr:last-child td, .stock-table tbody tr:last-child td { border-bottom:2px solid #334E68; }
        .section-title,.summary-title { color:#243B53; font-weight:bold; letter-spacing:.45px; }
        .summary-title { border-bottom:2px solid #F59E0B; }
        .summary-table td { color:#475569; border-bottom:1px solid #F1F5F9; }
        .summary-value { color:#243B53!important; }
        .total-row td { color:#243B53; background:#F8FAFC; border-top:1px solid #CBD5E1; border-bottom:2px solid #243B53; }
        .note { color:#64748B; }
        .footer { color:#94A3B8; border-top:1px solid #E2E8F0; padding-top:12px; margin-top:42px; }
        .increase,.in { color:#047857!important; } .decrease,.out { color:#B91C1C!important; } .low { color:#B96B00!important; } .unchanged,.none { color:#64748B!important; }
        .bottom-table { page-break-inside:avoid; }

    </style>

</head>


<body>


@php

    $reportNumber =
        'SR-' .
        $generatedAt->format('Ymd-His');


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



{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<table class="header-table">

    <tr>

        <td style="width: 55%;">

            <div class="report-title">
                STOCK REPORT
            </div>

        </td>


        <td
            class="company-block"
            style="width: 45%;"
        >

            <div class="company-name">
                Smart Shelf Inventory System
            </div>

            <div class="company-subtitle">
                INVENTORY MANAGEMENT
            </div>


            <div class="report-meta">

                <div>
                    <strong>Report No.</strong>
                    {{ $reportNumber }}
                </div>

                <div>
                    <strong>Date:</strong>
                    {{ $generatedAt->format('d/m/Y') }}
                </div>

            </div>

        </td>

    </tr>

</table>



{{-- ========================================================= --}}
{{-- INFORMATION --}}
{{-- ========================================================= --}}

<table class="info-table">

    <tr>


        {{-- GENERATED BY --}}

        <td class="info-left">

            <div class="info-title">
                GENERATED BY
            </div>


            <div class="info-row info-main">
                {{ $generatedBy->name }}
            </div>


            <div class="info-row muted">
                Smart Shelf Inventory System
            </div>


            <div class="info-row muted">
                Stock Inventory Report
            </div>

        </td>



        {{-- REPORT DETAILS --}}

        <td class="info-right">

            <div class="info-title">
                REPORT DETAILS
            </div>


            <div class="info-row">

                <strong>
                    Report Type:
                </strong>

                Current Stock

            </div>


            <div class="info-row">

                <strong>
                    Generated:
                </strong>

                {{ $generatedAt->format(
                    'd M Y, h:i A'
                ) }}

            </div>


            <div class="info-row">

                <strong>
                    Total Items:
                </strong>

                {{ $items->count() }}

            </div>


            <div class="info-row">
                <strong>Status Filter:</strong>
                {{ $statusLabel }}
            </div>


            <div class="info-row">
                <strong>Search:</strong>
                {{ $search !== '' ? $search : 'None' }}
            </div>

        </td>

    </tr>

</table>



{{-- ========================================================= --}}
{{-- STOCK TABLE --}}
{{-- ========================================================= --}}

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
                class="center"
                style="width: 9%;"
            >
                Qty
            </th>

            <th
                class="center"
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


                <td>
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


                <td class="center">

                    {{
                        $reading?->quantity
                        ?? '—'
                    }}

                </td>


                <td class="center">
                    {{ $item->min_stock }}
                </td>


                <td>

                    @if(!$reading)

                        <span class="status none">
                            NO DATA
                        </span>


                    @elseif(
                        $reading->stock_status
                        === 'out_of_stock'
                    )

                        <span class="status out">
                            OUT OF STOCK
                        </span>


                    @elseif(
                        $reading->stock_status
                        === 'low_stock'
                    )

                        <span class="status low">
                            LOW STOCK
                        </span>


                    @else

                        <span class="status in">
                            IN STOCK
                        </span>

                    @endif

                </td>

            </tr>


        @empty

            <tr>

                <td
                    colspan="7"
                    class="center"
                >
                    No stock records available.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>



{{-- ========================================================= --}}
{{-- BOTTOM SECTION --}}
{{-- ========================================================= --}}

<table class="bottom-table">

    <tr>


        {{-- REPORT NOTE --}}

        <td class="note-cell">

            <div class="section-title">
                REPORT NOTE
            </div>


            <div class="note">

                This report represents the latest
                available stock reading for each item
                at the time the report was generated.

                <br><br>

                Items without a recorded inventory
                reading are marked as
                <strong>NO DATA</strong>.

            </div>

        </td>



        {{-- STOCK SUMMARY --}}

        <td class="summary-cell">

            <div class="summary-title">
                STOCK SUMMARY
            </div>


            <table class="summary-table">

                <tr>

                    <td>
                        In Stock
                    </td>

                    <td class="summary-value">
                        {{ $inStock }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Low Stock
                    </td>

                    <td class="summary-value">
                        {{ $lowStock }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Out of Stock
                    </td>

                    <td class="summary-value">
                        {{ $outOfStock }}
                    </td>

                </tr>


                <tr>

                    <td>
                        No Data
                    </td>

                    <td class="summary-value">
                        {{ $noData }}
                    </td>

                </tr>


                <tr class="total-row">

                    <td>
                        Total Items
                    </td>

                    <td class="summary-value">
                        {{ $items->count() }}
                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>



{{-- ========================================================= --}}
{{-- FOOTER --}}
{{-- ========================================================= --}}

<div class="footer">

    Generated by Smart Shelf Inventory System

    <br>

    {{ $generatedAt->format(
        'd M Y, h:i A'
    ) }}

</div>


</body>

</html>