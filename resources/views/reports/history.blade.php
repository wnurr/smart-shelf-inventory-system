<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        History Report
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
           INFORMATION
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
           HISTORY TABLE
        ===================================================== */

        .history-table {
            width: 100%;

            border-collapse: collapse;

            font-size: 8px;
        }

        .history-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 8px;

    page-break-inside: auto;
}

.history-table thead {
    display: table-header-group;

    border-top: 1px solid #111111;
    border-bottom: 1px solid #111111;
}

.history-table tbody {
    display: table-row-group;
}

.history-table tr {
    page-break-inside: avoid;
    page-break-after: auto;
}

.history-table th,
.history-table td {
    page-break-inside: avoid;
}

.bottom-table {
    page-break-inside: avoid;
}

        .history-table thead {
            border-top: 1px solid #111111;
            border-bottom: 1px solid #111111;
        }

        .history-table th {
            padding: 7px 3px;

            text-align: left;
            font-weight: bold;
        }

        .history-table td {
            padding: 8px 3px;

            border-bottom: 1px solid #e5e5e5;

            vertical-align: middle;
        }

        .history-table tbody tr:last-child td {
            border-bottom: 1px solid #111111;
        }

        .center {
            text-align: center;
        }

        .increase {
            color: #166534;
            font-weight: bold;
        }

        .decrease {
            color: #b91c1c;
            font-weight: bold;
        }

        .unchanged {
            color: #666666;
            font-weight: bold;
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
            margin-top: 55px;

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
        'HR-' .
        $generatedAt->format('Ymd-His');


    $increases = 0;
    $decreases = 0;
    $unchanged = 0;


    foreach ($histories as $record) {

        $previous =
            $record->previous_quantity;

        $new =
            $record->new_quantity;


        if (
            $previous !== null
            && $new !== null
        ) {

            if ($new > $previous) {

                $increases++;

            } elseif ($new < $previous) {

                $decreases++;

            } else {

                $unchanged++;

            }

        }

    }
@endphp



{{-- ========================================================= --}}
{{-- HEADER --}}
{{-- ========================================================= --}}

<table class="header-table">

    <tr>

        <td style="width:55%;">

            <div class="report-title">
                HISTORY REPORT
            </div>

        </td>


        <td
            class="company-block"
            style="width:45%;"
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
                Inventory History Report
            </div>

        </td>


        <td class="info-right">

            <div class="info-title">
                REPORT DETAILS
            </div>


            <div class="info-row">
                <strong>Report Type:</strong>
                Stock History
            </div>

            <div class="info-row">
                <strong>Item:</strong>
                {{ $item->item_name }}
            </div>

            <div class="info-row">
                <strong>Item Code:</strong>
                {{ $item->item_code }}
            </div>

            <div class="info-row">
                <strong>Date Filter:</strong>
                @if($selectedDate)
                    {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                @else
                    All Recorded Dates
                @endif
            </div>

            <div class="info-row">
                <strong>Generated:</strong>
                {{ $generatedAt->format('d M Y, h:i A') }}
            </div>

            <div class="info-row">
                <strong>Total Records:</strong>
                {{ $histories->count() }}
            </div>

        </td>

    </tr>

</table>



{{-- ========================================================= --}}
{{-- HISTORY TABLE --}}
{{-- ========================================================= --}}

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

        @forelse($histories as $record)

            @php

                $previous =
                    $record->previous_quantity;

                $new =
                    $record->new_quantity;

            @endphp


            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>


                <td>

                    {{ $record->created_at
                        ->format('d M Y') }}

                    <br>

                    {{ $record->created_at
                        ->format('h:i A') }}

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

                    {{
                        $previous
                        ?? '—'
                    }}

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


        @empty

            <tr>

                <td
                    colspan="7"
                    class="center"
                >
                    No history records found.
                </td>

            </tr>

        @endforelse

    </tbody>

</table>



{{-- ========================================================= --}}
{{-- BOTTOM --}}
{{-- ========================================================= --}}

<table class="bottom-table">

    <tr>

        <td class="note-cell">

            <div class="section-title">
                REPORT NOTE
            </div>

            <div class="note">

                This report contains recorded inventory
                quantity changes within the selected
                reporting period.

                <br><br>

                An increase indicates stock was added,
                while a decrease indicates stock was
                removed from the shelf.

            </div>

        </td>


        <td class="summary-cell">

            <div class="summary-title">
                HISTORY SUMMARY
            </div>


            <table class="summary-table">

                <tr>

                    <td>
                        Stock In Events
                    </td>

                    <td class="summary-value">
                        {{ $increases }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Stock Out Events
                    </td>

                    <td class="summary-value">
                        {{ $decreases }}
                    </td>

                </tr>


                <tr>

                    <td>
                        Unchanged
                    </td>

                    <td class="summary-value">
                        {{ $unchanged }}
                    </td>

                </tr>


                <tr class="total-row">

                    <td>
                        Total Records
                    </td>

                    <td class="summary-value">
                        {{ $histories->count() }}
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