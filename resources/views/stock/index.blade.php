@extends('layouts.app')

@section('content')

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8"
    x-data="{
        viewMode: localStorage.getItem('stockInventoryView') || 'list',
        setView(mode) {
            this.viewMode = mode;
            localStorage.setItem('stockInventoryView', mode);
        }
    }"
>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col lg:flex-row
                lg:items-center lg:justify-between
                gap-4 mb-7">

        <div>

            <div class="ui-eyebrow"><span></span>LIVE INVENTORY</div>
            <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">
                Stock Inventory
            </h1>

            <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">
                Monitor the latest inventory levels detected
                by each smart shelf.
            </p>

        </div>


        @if(auth()->user()->hasPermission('generate_reports'))

            <a
                href="{{ route('reports.stock', [
                    'search' => $search !== '' ? $search : null,
                    'status' => $status !== 'all' ? $status : null,
                ]) }}"
                class="inline-flex items-center
                       justify-center gap-2
                       px-4 py-2.5
                       rounded-lg
                       text-sm font-medium
                       text-white
                       transition-colors"
                style="background-color: #243B53;"
            >

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
</svg>

                Generate Report

            </a>

        @endif

    </div>



    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div data-live-region="stock-summary"
         class="grid grid-cols-1
                sm:grid-cols-2
                xl:grid-cols-4
                gap-4 mb-6">


        {{-- Total Items --}}
        <div class="stock-summary-card ui-summary">

            <div class="flex items-center justify-between">

                <div>

                    <p class="summary-label">
                        Total Items
                    </p>

                    <p class="summary-number">
                        {{ $totalItems }}
                    </p>

                </div>


                <div class="summary-icon summary-icon-primary">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0
                               l-8 4m8-4v10l-8 4
                               m0-10L4 7m8 4v10
                               M4 7v10l8 4"
                        />
                    </svg>

                </div>

            </div>

        </div>



        {{-- In Stock --}}
        <div class="stock-summary-card ui-summary">

            <div class="flex items-center justify-between">

                <div>

                    <p class="summary-label">
                        In Stock
                    </p>

                    <p
                        class="summary-number"
                        style="color: #15803D;"
                    >
                        {{ $inStockCount }}
                    </p>

                </div>


                <div class="summary-icon summary-icon-success">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

            </div>

        </div>



        {{-- Low Stock --}}
        <div class="stock-summary-card ui-summary">

            <div class="flex items-center justify-between">

                <div>

                    <p class="summary-label">
                        Low Stock
                    </p>

                    <p
                        class="summary-number"
                        style="color: #B45309;"
                    >
                        {{ $lowStockCount }}
                    </p>

                </div>


                <div class="summary-icon summary-icon-warning">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01
                               M5.07 19h13.86
                               c1.54 0 2.5-1.667
                               1.73-3L13.73 4
                               c-.77-1.333-2.69-1.333-3.46 0
                               L3.34 16
                               c-.77 1.333.19 3 1.73 3z"
                        />
                    </svg>

                </div>

            </div>

        </div>



        {{-- Out of Stock --}}
        <div class="stock-summary-card ui-summary">

            <div class="flex items-center justify-between">

                <div>

                    <p class="summary-label">
                        Out of Stock
                    </p>

                    <p
                        class="summary-number"
                        style="color: #DC2626;"
                    >
                        {{ $outOfStockCount }}
                    </p>

                </div>


                <div class="summary-icon summary-icon-danger">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- FILTER BAR --}}
    {{-- ========================================================= --}}

    <x-search-filter
        :action="route('stock.index')"
        :search="$search"
        placeholder="Search item, code, sensor or location..."
        :clear-url="route('stock.index')"
        :show-clear="$search !== '' || $status !== 'all'"
        submit-label="Search"
        class="mb-5"
    >

        <select
            name="status"
            class="h-10
                   lg:w-48
                   rounded-lg
                   border-gray-300
                   text-sm
                   focus:border-[#F59E0B]
                   focus:ring-[#F59E0B]"
        >
            <option value="all" @selected($status === 'all')>
                All Status
            </option>

            <option value="in_stock" @selected($status === 'in_stock')>
                In Stock
            </option>

            <option value="low_stock" @selected($status === 'low_stock')>
                Low Stock
            </option>

            <option value="out_of_stock" @selected($status === 'out_of_stock')>
                Out of Stock
            </option>

            <option value="no_data" @selected($status === 'no_data')>
                No Data
            </option>
        </select>

    </x-search-filter>


    {{-- ========================================================= --}}
    {{-- STOCK TABLE --}}
    {{-- ========================================================= --}}

    <div data-live-region="stock-table"
         class="bg-white
                border border-gray-200
                rounded-xl
                overflow-hidden">


        {{-- Table Header --}}
        <div class="px-5 py-4
                    border-b border-gray-100
                    flex items-center
                    justify-between">

            <div>

                <h2 class="text-sm font-semibold text-gray-900">
                    Current Stock
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Showing {{ $items->count() }}
                    of {{ $totalItems }} items
                </p>

            </div>

            <x-view-switcher
                x-model="viewMode"
                :options="[
                    ['value' => 'list', 'label' => 'List'],
                    ['value' => 'cards', 'label' => 'Cards'],
                ]"
            />

        </div>


        {{-- LIST VIEW --}}
        <div x-show="viewMode === 'list'" x-cloak class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr
                        class="bg-gray-50
                               border-b border-gray-100
                               text-left
                               text-xs font-semibold
                               text-gray-500
                               uppercase tracking-wide"
                    >

                        <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3">
                            Item
                        </th>

                        <th class="px-5 py-3">
                            Sensor
                        </th>

                        <th class="px-5 py-3">
                            Location
                        </th>

                        <th class="px-5 py-3">
                            Stock Level
                        </th>

                        <th class="px-5 py-3">
                            Status
                        </th>

                        <th class="px-5 py-3">
                            Last Reading
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($items as $item)

                        @php
                            $reading =
                                $item->latestReading;

                            $quantity =
                                $reading?->quantity;

                            $minimum =
                                $item->min_stock;

                            // Current status must be derived from the latest
                            // quantity + CURRENT minimum stock setting.
                            $currentStatus =
                                $item->stockStatus();

                            $percentage =
                                $reading
                                    ? min(
                                        100,
                                        max(
                                            0,
                                            ($quantity / max($minimum, 1))
                                            * 100
                                        )
                                    )
                                    : 0;
                        @endphp


                        <tr class="stock-row ui-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" /></td>


                            {{-- Item --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="item-icon">

                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 7l-8-4-8 4
                                                   m16 0l-8 4
                                                   m8-4v10l-8 4
                                                   m0-10L4 7
                                                   m8 4v10
                                                   M4 7v10l8 4"
                                            />
                                        </svg>

                                    </div>


                                    <div>

                                        <p class="font-semibold text-gray-900">
                                            {{ $item->item_name }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $item->item_code }}
                                        </p>

                                    </div>

                                </div>

                            </td>



                            {{-- Sensor --}}
                            <td class="px-5 py-4">

                                @if($item->sensor)

                                    <span class="sensor-badge">
                                        {{ $item->sensor->sensor_code }}
                                    </span>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>



                            {{-- Location --}}
                            <td class="px-5 py-4 text-gray-600">

                                {{ $item->location?->label() ?? '—' }}

                            </td>



                            {{-- Stock --}}
                            <td class="px-5 py-4">

                                @if($reading)

                                    <div class="min-w-[150px]">

                                        <div
                                            class="flex items-center
                                                   justify-between
                                                   gap-4 mb-1.5"
                                        >

                                            <span
                                                class="font-semibold
                                                       text-gray-900"
                                            >
                                                {{ $quantity }}
                                                units
                                            </span>

                                            <span
                                                class="text-xs
                                                       text-gray-400"
                                            >
                                                Min {{ $minimum }}
                                            </span>

                                        </div>


                                        <div class="stock-progress">

                                            <div
                                                class="stock-progress-fill
                                                @if(
                                                    $currentStatus ===
                                                    'out_of_stock'
                                                )
                                                    stock-progress-danger
                                                @elseif(
                                                    $currentStatus ===
                                                    'low_stock'
                                                )
                                                    stock-progress-warning
                                                @else
                                                    stock-progress-success
                                                @endif"
                                                style="width:
                                                    {{ $percentage }}%;"
                                            ></div>

                                        </div>

                                    </div>

                                @else

                                    <span class="text-gray-400">
                                        No reading
                                    </span>

                                @endif

                            </td>



                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if(!$reading)

                                    <span class="status-badge status-none">
                                        <span class="status-dot"></span>
                                        No Data
                                    </span>

                                @elseif(
                                    $currentStatus ===
                                    'out_of_stock'
                                )

                                    <span class="status-badge status-danger">
                                        <span class="status-dot"></span>
                                        Out of Stock
                                    </span>

                                @elseif(
                                    $currentStatus ===
                                    'low_stock'
                                )

                                    <span class="status-badge status-warning">
                                        <span class="status-dot"></span>
                                        Low Stock
                                    </span>

                                @else

                                    <span class="status-badge status-success">
                                        <span class="status-dot"></span>
                                        In Stock
                                    </span>

                                @endif

                            </td>



                            {{-- Last Reading --}}
                            <td class="px-5 py-4">

                                @if($reading)

                                    <p class="text-gray-700">
                                        {{ $reading->recorded_at
                                            ->format('d M Y') }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $reading->recorded_at
                                            ->format('h:i A') }}
                                    </p>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >

                                <div class="empty-icon">

                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 13V6
                                               a2 2 0 00-2-2H6
                                               a2 2 0 00-2 2v7
                                               m16 0v5
                                               a2 2 0 01-2 2H6
                                               a2 2 0 01-2-2v-5
                                               m16 0h-2.586
                                               a1 1 0 00-.707.293
                                               l-1.414 1.414
                                               a1 1 0 01-.707.293H9.414
                                               a1 1 0 01-.707-.293
                                               l-1.414-1.414
                                               A1 1 0 006.586 13H4"
                                        />
                                    </svg>

                                </div>


                                <h3
                                    class="text-sm font-semibold
                                           text-gray-700 mt-3"
                                >
                                    No stock records found
                                </h3>

                                <p
                                    class="text-xs
                                           text-gray-400 mt-1"
                                >

                                    @if(
                                        $search !== '' ||
                                        $status !== 'all'
                                    )

                                        Try changing your search
                                        or filter.

                                    @else

                                        Add items and sensor readings
                                        to view stock information.

                                    @endif

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- CARD VIEW --}}
        <div x-show="viewMode === 'cards'" x-cloak class="p-5 bg-gray-50/60">

            @if($items->isNotEmpty())

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

                    @foreach($items as $item)

                        @php
                            $reading = $item->latestReading;
                            $quantity = $reading?->quantity;
                            $minimum = $item->min_stock;
                            $currentStatus = $item->stockStatus();

                            $percentage = $reading
                                ? min(100, max(0, ($quantity / max($minimum, 1)) * 100))
                                : 0;

                            $cardState = !$reading
                                ? 'none'
                                : match($currentStatus) {
                                    'out_of_stock' => 'danger',
                                    'low_stock' => 'warning',
                                    default => 'success',
                                };

                            $statusLabel = !$reading
                                ? 'No Data'
                                : match($currentStatus) {
                                    'out_of_stock' => 'Out of Stock',
                                    'low_stock' => 'Low Stock',
                                    default => 'In Stock',
                                };
                        @endphp

                        <article class="stock-monitor-card stock-monitor-card-{{ $cardState }}">

                            <div class="stock-monitor-top">

                                <div class="min-w-0">
                                    <div class="flex items-center gap-2.5">
                                        <span class="stock-monitor-number">
                                            <x-table-number :loop="$loop"
                                            class="!text-white" />
                                        </span>

                                        <div class="min-w-0">
                                            <h3 class="stock-monitor-title">
                                                {{ $item->item_name }}
                                            </h3>

                                            <p class="stock-monitor-code">
                                                {{ $item->item_code }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <span class="stock-monitor-status">
                                    <span class="stock-monitor-status-dot"></span>
                                    {{ $statusLabel }}
                                </span>

                            </div>


                            @if($reading)

                                <div class="stock-monitor-main">

                                    <div>
                                        <p class="stock-monitor-quantity">
                                            {{ $quantity }}
                                        </p>

                                        <p class="stock-monitor-unit">
                                            units available
                                        </p>
                                    </div>

                                    <div class="stock-monitor-minimum">
                                        <p>Minimum stock</p>
                                        <strong>{{ $minimum }} units</strong>
                                    </div>

                                </div>


                                <div class="stock-monitor-progress">
                                    <div
                                        class="stock-monitor-progress-fill"
                                        style="width: {{ $percentage }}%;"
                                    ></div>
                                </div>

                            @else

                                <div class="stock-monitor-empty">
                                    <div class="stock-monitor-empty-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="font-semibold">No stock reading yet</p>
                                        <p>Waiting for sensor data.</p>
                                    </div>
                                </div>

                            @endif


                            <div class="stock-monitor-meta">

                                <div>
                                    <p class="stock-monitor-meta-label">Sensor</p>
                                    <p class="stock-monitor-meta-value">
                                        {{ $item->sensor?->sensor_code ?? '—' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="stock-monitor-meta-label">Location</p>
                                    <p class="stock-monitor-meta-value">
                                        {{ $item->location?->label() ?? '—' }}
                                    </p>
                                </div>

                                <div class="sm:text-right">
                                    <p class="stock-monitor-meta-label">Last Reading</p>

                                    @if($reading)
                                        <p class="stock-monitor-meta-value">
                                            {{ $reading->recorded_at->format('d M Y') }}
                                        </p>
                                        <p class="stock-monitor-meta-time">
                                            {{ $reading->recorded_at->format('h:i A') }}
                                        </p>
                                    @else
                                        <p class="stock-monitor-meta-value">—</p>
                                    @endif
                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @else

                <div class="px-6 py-16 text-center">
                    <div class="empty-icon">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5" />
                        </svg>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700 mt-3">
                        No stock records found
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        @if($search !== '' || $status !== 'all')
                            Try changing your search or filter.
                        @else
                            Add items and sensor readings to view stock information.
                        @endif
                    </p>
                </div>

            @endif

        </div>
    </div>

</div>



{{-- ========================================================= --}}
{{-- PAGE STYLES --}}
{{-- ========================================================= --}}

<style>

    .stock-summary-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
        padding: 1.25rem;
    }


    .summary-label {
        color: #6B7280;
        font-size: 0.75rem;
        font-weight: 500;
    }


    .summary-number {
        color: #111827;
        font-size: 1.75rem;
        line-height: 1;
        font-weight: 700;
        margin-top: 0.55rem;
    }


    .summary-icon {
        width: 2.5rem;
        height: 2.5rem;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 0.65rem;
    }


    .summary-icon-primary {
        color: #284B63;
        background: #EDF3F7;
    }


    .summary-icon-success {
        color: #15803D;
        background: #DCFCE7;
    }


    .summary-icon-warning {
        color: #B45309;
        background: #FEF3C7;
    }


    .summary-icon-danger {
        color: #DC2626;
        background: #FEE2E2;
    }


    .stock-row {
        transition: background-color 0.15s ease;
    }


    .stock-row:hover {
        background: #FAFAFA;
    }


    .item-icon {
        width: 2.25rem;
        height: 2.25rem;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 0.55rem;
    }


    .sensor-badge {
        display: inline-flex;

        padding: 0.3rem 0.55rem;

        border-radius: 0.4rem;

        color: #284B63;
        background: #EDF3F7;

        font-size: 0.75rem;
        font-weight: 600;
    }


    .stock-progress {
        width: 100%;
        height: 0.35rem;

        overflow: hidden;

        background: #F3F4F6;

        border-radius: 9999px;
    }


    .stock-progress-fill {
        height: 100%;
        border-radius: 9999px;
    }


    .stock-progress-success {
        background: #22C55E;
    }


    .stock-progress-warning {
        background: #F59E0B;
    }


    .stock-progress-danger {
        background: #DC2626;
    }


    .status-badge {
        display: inline-flex;
        align-items: center;

        gap: 0.4rem;

        padding: 0.3rem 0.6rem;

        border-radius: 9999px;

        font-size: 0.7rem;
        font-weight: 600;

        white-space: nowrap;
    }


    .status-dot {
        width: 0.4rem;
        height: 0.4rem;

        background: currentColor;

        border-radius: 9999px;
    }


    .status-success {
        color: #15803D;
        background: #DCFCE7;
    }


    .status-warning {
        color: #B45309;
        background: #FEF3C7;
    }


    .status-danger {
        color: #B91C1C;
        background: #FEE2E2;
    }


    .status-none {
        color: #6B7280;
        background: #F3F4F6;
    }


    .empty-icon {
        width: 3rem;
        height: 3rem;

        margin: 0 auto;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #9CA3AF;
        background: #F3F4F6;

        border-radius: 9999px;
    }

    [x-cloak] {
        display: none !important;
    }

    /* =========================================================
       QUICK STOCK MONITOR CARDS
       Strong status colour for instant scanning while keeping
       sufficient contrast for operational information.
       ========================================================= */

    .stock-monitor-card {
        --card-bg-1: #64748B;
        --card-bg-2: #475569;
        --card-soft: rgba(255, 255, 255, 0.14);
        --card-line: rgba(255, 255, 255, 0.24);

        position: relative;
        overflow: hidden;
        min-height: 270px;
        display: flex;
        flex-direction: column;
        padding: 1.15rem;
        color: #FFFFFF;
        background: linear-gradient(135deg, var(--card-bg-1), var(--card-bg-2));
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 0.9rem;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    .stock-monitor-card::after {
        content: "";
        position: absolute;
        width: 9rem;
        height: 9rem;
        right: -3.5rem;
        top: -4rem;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.09);
        pointer-events: none;
    }

    .stock-monitor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.13);
    }

    .stock-monitor-card-success {
        --card-bg-1: #16A34A;
        --card-bg-2: #15803D;
    }

    .stock-monitor-card-warning {
        --card-bg-1: #F59E0B;
        --card-bg-2: #D97706;
    }

    .stock-monitor-card-danger {
        --card-bg-1: #EF4444;
        --card-bg-2: #DC2626;
    }

    .stock-monitor-card-none {
        --card-bg-1: #64748B;
        --card-bg-2: #475569;
    }

    .stock-monitor-top {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .stock-monitor-number {
    width: 1.75rem;
    height: 1.75rem;
    flex-shrink: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.22);

    border: 1.5px solid rgba(255, 255, 255, 0.55);
    border-radius: 9999px;

    font-size: 0.72rem;
    font-weight: 700;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    backdrop-filter: blur(4px);
    }

    .stock-monitor-title {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #FFFFFF;
        font-size: 0.95rem;
        line-height: 1.2rem;
        font-weight: 700;
    }

    .stock-monitor-code {
        margin-top: 0.2rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.68rem;
        font-weight: 500;
    }

    .stock-monitor-status {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.38rem;
        padding: 0.32rem 0.6rem;
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 9999px;
        font-size: 0.67rem;
        line-height: 1rem;
        font-weight: 700;
        white-space: nowrap;
        backdrop-filter: blur(5px);
    }

    .stock-monitor-status-dot {
        width: 0.38rem;
        height: 0.38rem;
        flex-shrink: 0;
        border-radius: 9999px;
        background: #FFFFFF;
    }

    .stock-monitor-main {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.65rem;
    }

    .stock-monitor-quantity {
        color: #FFFFFF;
        font-size: 2.35rem;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -0.035em;
    }

    .stock-monitor-unit {
        margin-top: 0.32rem;
        color: rgba(255, 255, 255, 0.72);
        font-size: 0.7rem;
        font-weight: 500;
    }

    .stock-monitor-minimum {
        text-align: right;
        color: rgba(255, 255, 255, 0.68);
        font-size: 0.66rem;
    }

    .stock-monitor-minimum strong {
        display: block;
        margin-top: 0.22rem;
        color: #FFFFFF;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .stock-monitor-progress {
        position: relative;
        z-index: 1;
        width: 100%;
        height: 0.38rem;
        margin-top: 1rem;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 9999px;
    }

    .stock-monitor-progress-fill {
        height: 100%;
        min-width: 0;
        background: #FFFFFF;
        border-radius: 9999px;
        opacity: 0.92;
    }

    .stock-monitor-empty {
        position: relative;
        z-index: 1;
        min-height: 6.4rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-top: 1.4rem;
        padding: 1rem;
        color: rgba(255, 255, 255, 0.78);
        background: rgba(255, 255, 255, 0.1);
        border: 1px dashed rgba(255, 255, 255, 0.28);
        border-radius: 0.7rem;
        font-size: 0.7rem;
    }

    .stock-monitor-empty .font-semibold {
        color: #FFFFFF;
        font-size: 0.78rem;
    }

    .stock-monitor-empty-icon {
        width: 2.2rem;
        height: 2.2rem;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFFFFF;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 0.6rem;
    }

    .stock-monitor-meta {
        position: relative;
        z-index: 1;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid var(--card-line);
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.75rem;
    }

    .stock-monitor-meta-label {
        color: rgba(255, 255, 255, 0.58);
        font-size: 0.59rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.055em;
    }

    .stock-monitor-meta-value {
        margin-top: 0.25rem;
        color: #FFFFFF;
        font-size: 0.7rem;
        line-height: 1rem;
        font-weight: 650;
        word-break: break-word;
    }

    .stock-monitor-meta-time {
        margin-top: 0.08rem;
        color: rgba(255, 255, 255, 0.64);
        font-size: 0.62rem;
    }

    @media (max-width: 640px) {
        .stock-monitor-card {
            min-height: 255px;
        }

        .stock-monitor-meta {
            grid-template-columns: 1fr 1fr;
        }

        .stock-monitor-main {
            align-items: flex-start;
        }
    }


    /* =========================================================
       INDUSTRIAL UI REFRESH — presentation only
       Deep Steel #243B53 · Steel Navy #334E68 · Safety Amber #F59E0B
       ========================================================= */
    .ui-eyebrow{display:flex;align-items:center;gap:.5rem;color:#B96B00;font-size:.68rem;font-weight:800;letter-spacing:.12em}
    .ui-eyebrow span{width:.42rem;height:.42rem;border-radius:9999px;background:#F59E0B;box-shadow:0 0 0 4px rgba(245,158,11,.10)}
    .ui-page-title{color:#0F172A!important;letter-spacing:-.025em}
    .ui-page-subtitle{color:#64748B!important;max-width:44rem}
    .ui-surface{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}
    .ui-primary{background:#243B53!important;border-color:#243B53!important;border-radius:.65rem!important;box-shadow:0 4px 12px rgba(36,59,83,.12)!important}
    .ui-primary:hover{background:#334E68!important;border-color:#334E68!important;transform:translateY(-1px);box-shadow:0 7px 18px rgba(36,59,83,.16)!important}
    .ui-summary{position:relative;overflow:hidden;min-height:7.35rem;padding:1.3rem!important;background:linear-gradient(180deg,#fff 0%,#FCFDFE 100%)!important;border:1px solid #E2E8F0!important;border-radius:1rem!important;box-shadow:0 2px 8px rgba(15,23,42,.035)!important;transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}
    .ui-summary:before{content:"";position:absolute;left:0;top:1.1rem;bottom:1.1rem;width:3px;border-radius:0 999px 999px 0;background:#CBD5E1}
    .ui-summary:nth-child(1):before{background:#F59E0B}.ui-summary:nth-child(2):before{background:#2563EB}.ui-summary:nth-child(3):before{background:#059669}.ui-summary:nth-child(4):before{background:#DC2626}
    .ui-summary:hover{transform:translateY(-2px);border-color:#CBD5E1!important;box-shadow:0 10px 24px rgba(15,23,42,.07)!important}
    .ui-table-head{background:#F8FAFC!important;color:#64748B!important;border-color:#E2E8F0!important;font-size:.66rem!important;letter-spacing:.065em!important}
    .ui-row{transition:background-color .15s ease}.ui-row:hover{background:#F8FAFC!important}
    .ui-control{border-color:#CBD5E1!important;border-radius:.65rem!important;background:#fff!important}
    .ui-control:hover{border-color:#94A3B8!important}.ui-control:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important;outline:none!important}
    .ui-modal-shell{border:1px solid rgba(255,255,255,.15)!important;border-radius:1rem!important;box-shadow:0 24px 70px rgba(15,23,42,.24)!important}
    @media(max-width:640px){.ui-summary{min-height:6.6rem}}

    .summary-label{color:#64748B!important;font-size:.7rem!important;letter-spacing:.055em!important}.summary-number{font-size:2rem!important;letter-spacing:-.035em}
    .summary-icon{width:2.8rem!important;height:2.8rem!important;border-radius:.8rem!important}.summary-icon-primary{background:#FFF7E6!important;color:#B96B00!important;border:1px solid #FDE3AE}.summary-icon-success{background:#ECFDF5!important;color:#059669!important;border:1px solid #D1FAE5}.summary-icon-warning{background:#FFF7E6!important;color:#B96B00!important;border:1px solid #FDE3AE}.summary-icon-danger{background:#FEF2F2!important;color:#DC2626!important;border:1px solid #FEE2E2}
    .stock-monitor-card{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 2px 8px rgba(15,23,42,.035)!important}.stock-monitor-card:hover{box-shadow:0 10px 24px rgba(15,23,42,.07)!important;transform:translateY(-2px)}

</style>

@endsection