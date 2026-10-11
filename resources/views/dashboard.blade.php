@extends('layouts.app')

@section('content')

<div class="dashboard-page max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="ui-eyebrow"><span></span>DASHBOARD</div>

            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-[28px]">
                Welcome back, {{ auth()->user()->name }}
            </h1>

            <p class="mt-1.5 text-sm text-slate-500">
                Here's an overview of your smart shelf inventory.
            </p>
        </div>

        <div>
            <span class="role-badge">
                <span class="role-badge-dot"></span>
                {{ ucwords(
                    str_replace(
                        '_',
                        ' ',
                        auth()->user()->role?->position ?? 'No Role'
                    )
                ) }}
            </span>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 sm:gap-6">

        {{-- Total Items --}}
        <a
            href="{{ route('items.index') }}"
            class="dashboard-summary-card block cursor-pointer"
            title="View items"
        >
            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="dashboard-card-label">Total Items</p>

                    <p id="total-items" class="dashboard-card-number">—</p>

                    <p class="dashboard-card-description">Inventory items</p>
                </div>

                <div class="dashboard-icon-blue">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25M21 7.5v9l-9 5.25m0-9L3 7.5m9 5.25v9M3 7.5v9l9 5.25" />
                    </svg>
                </div>

            </div>

            <div class="summary-card-footer">
                <span>View items</span>
                <svg class="summary-card-chevron h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </a>


        {{-- Total Sensors --}}
        <a
            href="{{ route('sensors.index') }}"
            class="dashboard-summary-card block cursor-pointer"
            title="View sensors"
        >
            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="dashboard-card-label">Total Sensors</p>

                    <p id="total-sensors" class="dashboard-card-number">—</p>

                    <p class="dashboard-card-description">Registered sensors</p>
                </div>

                <div class="dashboard-icon-blue">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                    </svg>
                </div>

            </div>

            <div class="summary-card-footer">
                <span>View sensors</span>
                <svg class="summary-card-chevron h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </a>


        {{-- Low Stock --}}
        <a
            id="low-stock-card"
            href="{{ route('stock.index') }}"
            class="dashboard-summary-card low-stock-summary-card block cursor-pointer"
            title="View stock"
        >
            <div class="flex items-start justify-between gap-4">

                <div>
                    <p class="dashboard-card-label">Low Stock</p>

                    <p id="low-stock" class="dashboard-card-number">—</p>

                    <p id="low-stock-description" class="dashboard-card-description">
                        Checking stock levels...
                    </p>
                </div>

                <div id="low-stock-icon" class="dashboard-icon-danger">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>

            </div>

            <div id="low-stock-footer" class="summary-card-footer">
                <span>View stock</span>
                <svg class="summary-card-chevron h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- CURRENT STOCK OVERVIEW --}}
    {{-- ========================================================= --}}

    <div class="mt-6 sm:mt-7">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="flex items-start gap-3">

                    <div class="dashboard-small-icon-blue">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-slate-900">
                            Current Stock Overview
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Compare current quantity with the minimum stock level.
                        </p>
                    </div>

                </div>


                {{-- Live Indicator --}}
                <div class="flex-shrink-0 text-right">

                    <span class="live-pill">
                        <span class="live-dot-wrapper">
                            <span class="live-dot-pulse"></span>
                            <span class="live-dot"></span>
                        </span>

                        <span class="text-xs font-semibold text-emerald-700">Live</span>
                    </span>

                    <p id="dashboard-updated-at" class="mt-1.5 text-xs font-medium text-slate-500">
                        —
                    </p>

                </div>

            </div>


            <div class="p-5 sm:p-6">

                {{-- Empty State --}}
                <div
                    id="stock-chart-empty"
                    class="hidden min-h-[320px] items-center justify-center text-center"
                >
                    <div>

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50">
                            <svg class="h-6 w-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 16l4-4 3 3 5-7" />
                            </svg>
                        </div>

                        <p class="mt-3 text-sm font-medium text-slate-600">
                            No inventory data available
                        </p>

                        <p class="mt-1 text-xs text-slate-400">
                            Stock data will appear once inventory readings are available.
                        </p>

                    </div>
                </div>


                {{-- Chart --}}
                <div id="stock-chart-container" class="relative w-full" style="height: 340px;">
                    <canvas id="stockOverviewChart"></canvas>
                </div>


                {{-- Custom Legend --}}
                <div
                    id="stock-chart-legend"
                    class="mt-5 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 border-t border-slate-100 pt-4"
                >

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: #284B63;"></span>
                        <span class="text-xs text-slate-500">Normal Stock</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full" style="background-color: #DC2626;"></span>
                        <span class="text-xs text-slate-500">Low Stock</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="minimum-legend-line"></span>
                        <span class="text-xs text-slate-500">Minimum Stock</span>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LOWER DASHBOARD --}}
    {{-- ========================================================= --}}

    <div class="mt-6 grid grid-cols-1 items-start gap-6 sm:mt-7 lg:grid-cols-3">


        {{-- ===================================================== --}}
        {{-- STOCK ATTENTION --}}
        {{-- ===================================================== --}}

        <div class="dashboard-panel lg:col-span-2">

            <div class="dashboard-panel-header">

                <div class="flex items-start gap-3">

                    <div id="stock-attention-header-icon" class="dashboard-small-danger-icon">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-semibold text-slate-900">
                                Stock Attention
                            </h2>

                            <span id="stock-attention-count" class="hidden stock-attention-count">
                                0
                            </span>
                        </div>

                        <p class="mt-1 text-sm text-slate-500">
                            Items that have reached or fallen below their minimum stock level.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-5 sm:p-6">

                <div id="stock-attention-list" class="space-y-3">
                    <div class="py-8 text-center text-sm text-slate-400">
                        Loading stock information...
                    </div>
                </div>


                <div class="mt-5 border-t border-slate-100 pt-4">

                    <a
                        href="{{ route('items.index') }}"
                        class="dashboard-link inline-flex items-center gap-1.5 text-sm font-semibold dashboard-text-primary"
                    >
                        View all inventory

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- INVENTORY MONITORING --}}
        {{-- ===================================================== --}}

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div class="flex items-start gap-3">

                    <div class="dashboard-small-icon-blue">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.981-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-slate-900">
                            Inventory Monitoring
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Latest inventory reading coverage.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-5 sm:p-6">

                <div class="grid grid-cols-2 gap-3">

                    {{-- Items with readings --}}
                    <div class="monitor-tile">
                        <span id="items-with-readings" class="monitor-tile-number" style="color: #284B63;">
                            —
                        </span>

                        <p class="mt-2 text-sm font-medium text-slate-700">
                            Items with Readings
                        </p>

                        <p class="mt-0.5 text-xs leading-4 text-slate-500">
                            Items with recorded stock data
                        </p>
                    </div>

                    {{-- Items without readings --}}
                    <div class="monitor-tile monitor-tile-warn">
                        <span id="items-without-readings" class="monitor-tile-number text-amber-600">
                            —
                        </span>

                        <p class="mt-2 text-sm font-medium text-slate-700">
                            Awaiting First Reading
                        </p>

                        <p class="mt-0.5 text-xs leading-4 text-slate-500">
                            Items without recorded data
                        </p>
                    </div>

                </div>


                {{-- Last reading --}}
                <div class="mt-3 flex items-start gap-3 rounded-xl bg-slate-50 p-4">

                    <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-slate-500 ring-1 ring-slate-200">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-700">
                            Latest Inventory Reading
                        </p>

                        <p id="last-reading-at" class="mt-1 text-sm font-semibold" style="color: #284B63;">
                            Loading...
                        </p>

                        <p class="mt-1 text-xs text-slate-500">
                            Most recent reading received by the system
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- DASHBOARD STYLES --}}
{{-- ========================================================= --}}

<style>

.ui-eyebrow{display:flex;align-items:center;gap:.5rem;color:#B96B00;font-size:.68rem;font-weight:800;letter-spacing:.12em}
.ui-eyebrow span{width:.42rem;height:.42rem;border-radius:9999px;background:#F59E0B;box-shadow:0 0 0 4px rgba(245,158,11,.10)}

    /* Tokens */
    .dashboard-page {
        --dash-primary: #284B63;
        --dash-primary-dark: #1E394C;
        --dash-primary-50: #EDF3F7;
        --dash-primary-100: #DCE7EE;
        --dash-border: #E2E8F0;
        --dash-danger: #DC2626;
        --dash-safe: #059669;
    }


    /* Header */
    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.85rem;
        border-radius: 9999px;
        background-color: #FFFFFF;
        border: 1px solid var(--dash-border);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        color: var(--dash-primary);
        font-size: 0.75rem;
        font-weight: 600;
    }

    .role-badge-dot {
        width: 0.4rem;
        height: 0.4rem;
        border-radius: 9999px;
        background-color: #F5B301;
    }


    /* Summary cards */
    .dashboard-summary-card {
        position: relative;
        overflow: hidden;
        background-color: #FFFFFF;
        border: 1px solid var(--dash-border);
        border-radius: 1rem;
        padding: 1.375rem 1.375rem 1.125rem;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .dashboard-summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--dash-primary);
    }

    .dashboard-summary-card:hover {
        transform: translateY(-2px);
        border-color: var(--dash-primary-100);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }

    .dashboard-summary-card:focus-visible {
        outline: 2px solid var(--dash-primary);
        outline-offset: 2px;
    }

    .low-stock-summary-card::before { background-color: var(--dash-danger); }
    .low-stock-summary-card.stock-card-safe::before { background-color: var(--dash-safe); }

    .dashboard-card-label {
        color: #64748B;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .dashboard-card-number {
        color: #0F172A;
        font-size: 2rem;
        line-height: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        font-variant-numeric: tabular-nums;
        margin-top: 0.5rem;
    }

    .dashboard-card-description {
        color: #94A3B8;
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 0.5rem;
    }

    .summary-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.1rem;
        padding-top: 0.8rem;
        border-top: 1px solid #F1F5F9;
        color: #94A3B8;
        font-size: 0.75rem;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .summary-card-chevron { transition: transform 0.2s ease; }

    .dashboard-summary-card:hover .summary-card-footer { color: var(--dash-primary); }
    .dashboard-summary-card:hover .summary-card-chevron { transform: translateX(3px); }

    .low-stock-summary-card:hover .summary-card-footer { color: var(--dash-danger); }
    .low-stock-summary-card.stock-card-safe:hover .summary-card-footer { color: var(--dash-safe); }


    /* Icons */
    .dashboard-icon-blue,
    .dashboard-icon-danger {
        width: 2.75rem;
        height: 2.75rem;
        flex-shrink: 0;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dashboard-icon-blue   { background-color: var(--dash-primary-50); color: var(--dash-primary); }
    .dashboard-icon-danger { background-color: #FEE2E2; color: var(--dash-danger); }

    .dashboard-summary-card:hover .dashboard-icon-blue {
        background-color: var(--dash-primary);
        color: #FFFFFF;
    }

    .stock-icon-safe {
        background-color: #ECFDF5 !important;
        color: var(--dash-safe) !important;
    }

    .dashboard-small-icon-blue,
    .dashboard-small-danger-icon {
        width: 2rem;
        height: 2rem;
        flex-shrink: 0;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dashboard-small-icon-blue   { background-color: var(--dash-primary-50); color: var(--dash-primary); }
    .dashboard-small-danger-icon { background-color: #FEE2E2; color: var(--dash-danger); }

    .attention-icon-safe {
        background-color: #ECFDF5;
        color: var(--dash-safe);
    }


    /* Panels */
    .dashboard-panel {
        background-color: #FFFFFF;
        border: 1px solid var(--dash-border);
        border-radius: 1rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .dashboard-panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }


    /* Primary text / links */
    .dashboard-text-primary       { color: var(--dash-primary); }
    .dashboard-text-primary:hover { color: var(--dash-primary-dark); }

    .dashboard-link:focus-visible {
        outline: 2px solid var(--dash-primary);
        outline-offset: 3px;
        border-radius: 0.25rem;
    }


    /* Live indicator */
    .live-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        background-color: #ECFDF5;
        border: 1px solid #D1FAE5;
    }

    .live-dot-wrapper {
        position: relative;
        display: inline-flex;
        width: 0.5rem;
        height: 0.5rem;
    }

    .live-dot {
        position: relative;
        display: inline-flex;
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 9999px;
        background-color: #10B981;
    }

    .live-dot-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 9999px;
        background-color: #34D399;
        opacity: 0.7;
        animation: dashboard-ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes dashboard-ping {
        75%, 100% { transform: scale(2); opacity: 0; }
    }


    /* Chart legend */
    .minimum-legend-line {
        width: 1.5rem;
        border-top: 2px dashed #F59E0B;
    }


    /* Stock attention */
    .stock-attention-count {
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
        background-color: #FEE2E2;
        color: var(--dash-danger);
        font-size: 0.7rem;
        font-weight: 700;
    }

    .stock-attention-item {
        display: block;
        background-color: #FFFFFF;
        border: 1px solid #F1F5F9;
        border-left: 3px solid var(--dash-danger);
        border-radius: 0.75rem;
        padding: 1rem 1.125rem;
        text-decoration: none;
        transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stock-attention-item:hover {
        background-color: #FFFBFB;
        border-color: #FECACA;
        border-left-color: var(--dash-danger);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
    }

    .stock-attention-item:focus-visible {
        outline: 2px solid var(--dash-danger);
        outline-offset: 2px;
    }

    .low-stock-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.15rem 0.55rem;
        border-radius: 9999px;
        background-color: #FEE2E2;
        color: #B91C1C;
        font-size: 0.6875rem;
        line-height: 1rem;
        font-weight: 600;
    }

    .stock-attention-arrow {
        color: #CBD5E1;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .stock-attention-item:hover .stock-attention-arrow {
        color: var(--dash-danger);
        transform: translateX(3px);
    }


    /* Inventory monitoring */
    .monitor-tile {
        border: 1px solid var(--dash-border);
        border-radius: 0.75rem;
        padding: 1rem;
        background-color: #FFFFFF;
    }

    .monitor-tile-warn {
        background-color: #FFFBEB;
        border-color: #FDE68A;
    }

    .monitor-tile-number {
        display: block;
        font-size: 1.75rem;
        line-height: 2rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        font-variant-numeric: tabular-nums;
    }


    /* Responsive */
    @media (max-width: 640px) {
        .dashboard-panel-header { flex-direction: column; }
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-summary-card,
        .dashboard-summary-card:hover,
        .stock-attention-item,
        .stock-attention-item:hover,
        .summary-card-chevron,
        .stock-attention-arrow { transform: none; transition: none; }

        .live-dot-pulse { animation: none; }
    }

</style>


{{-- ========================================================= --}}
{{-- DASHBOARD SCRIPT (logic unchanged) --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    let stockOverviewChart = null;


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div = document.createElement('div');

        div.textContent = value ?? '';

        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | Stock Chart
    |--------------------------------------------------------------------------
    */

    function updateStockChart(stockData) {

        const chartContainer = document.getElementById('stock-chart-container');
        const emptyState = document.getElementById('stock-chart-empty');
        const legend = document.getElementById('stock-chart-legend');


        if (!stockData || stockData.length === 0) {

            chartContainer.classList.add('hidden');

            legend.classList.add('hidden');

            emptyState.classList.remove('hidden');
            emptyState.classList.add('flex');


            if (stockOverviewChart) {

                stockOverviewChart.destroy();

                stockOverviewChart = null;

            }

            return;
        }


        emptyState.classList.add('hidden');
        emptyState.classList.remove('flex');

        chartContainer.classList.remove('hidden');

        legend.classList.remove('hidden');


        // Data
        const labels = stockData.map(function (item) {
            return item.item_name;
        });

        const quantities = stockData.map(function (item) {
            return Number(item.quantity) || 0;
        });

        const minimumStocks = stockData.map(function (item) {
            return Number(item.min_stock) || 0;
        });


        // Dynamic bar colours
        const barColors = stockData.map(function (item) {
            return item.is_low_stock === true ? '#DC2626' : '#284B63';
        });

        const borderColors = stockData.map(function (item) {
            return item.is_low_stock === true ? '#B91C1C' : '#1E394C';
        });


        // Update existing chart
        if (stockOverviewChart) {

            stockOverviewChart.data.labels = labels;

            stockOverviewChart.data.datasets[0].data = quantities;
            stockOverviewChart.data.datasets[0].backgroundColor = barColors;
            stockOverviewChart.data.datasets[0].borderColor = borderColors;

            stockOverviewChart.data.datasets[1].data = minimumStocks;

            stockOverviewChart.$stockData = stockData;

            stockOverviewChart.update();

            return;
        }


        // Create chart
        const canvas = document.getElementById('stockOverviewChart');

        const context = canvas.getContext('2d');


        stockOverviewChart = new Chart(context, {

            type: 'bar',

            data: {

                labels: labels,

                datasets: [

                    {
                        label: 'Current Quantity',
                        data: quantities,
                        backgroundColor: barColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                        borderRadius: 7,
                        borderSkipped: false,
                        maxBarThickness: 52,
                        order: 2
                    },

                    {
                        type: 'line',
                        label: 'Minimum Stock',
                        data: minimumStocks,
                        borderColor: '#F59E0B',
                        backgroundColor: '#F59E0B',
                        borderWidth: 2,
                        borderDash: [6, 5],
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#F59E0B',
                        tension: 0.25,
                        fill: false,
                        order: 1
                    }

                ]

            },

            options: {

                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        displayColors: false,

                        callbacks: {

                            title: function (items) {

                                if (!items.length) {
                                    return '';
                                }

                                return items[0].label;

                            },

                            label: function (context) {

                                // We already show both values manually below.
                                if (context.datasetIndex !== 0) {
                                    return null;
                                }

                                const item = stockOverviewChart.$stockData[context.dataIndex];

                                return [

                                    'Current quantity: ' + (item.quantity ?? 'No reading'),

                                    'Minimum stock: ' + item.min_stock,

                                    item.is_low_stock
                                        ? 'Status: Low stock'
                                        : 'Status: Sufficient'

                                ];

                            }

                        }

                    }

                },

                scales: {

                    x: {

                        grid: { display: false },

                        border: { display: false },

                        ticks: {
                            color: '#6B7280',
                            font: { size: 12, weight: 500 }
                        }

                    },

                    y: {

                        beginAtZero: true,

                        border: { display: false },

                        ticks: {
                            precision: 0,
                            color: '#9CA3AF',
                            padding: 8
                        },

                        grid: {
                            color: 'rgba(229, 231, 235, 0.65)'
                        },

                        title: {
                            display: true,
                            text: 'Quantity',
                            color: '#9CA3AF',
                            font: { size: 11, weight: 500 }
                        }

                    }

                }

            }

        });


        stockOverviewChart.$stockData = stockData;

    }


    /*
    |--------------------------------------------------------------------------
    | Stock Attention
    |--------------------------------------------------------------------------
    */

    function updateStockAttention(stockData) {

        const container = document.getElementById('stock-attention-list');
        const countBadge = document.getElementById('stock-attention-count');
        const headerIcon = document.getElementById('stock-attention-header-icon');


        if (!stockData) {

            container.innerHTML = '';

            return;

        }


        const lowStockItems = stockData.filter(function (item) {
            return item.is_low_stock === true;
        });


        // Update header state
        if (lowStockItems.length === 0) {

            countBadge.classList.add('hidden');

            headerIcon.classList.add('attention-icon-safe');

        } else {

            countBadge.textContent = lowStockItems.length;

            countBadge.classList.remove('hidden');

            headerIcon.classList.remove('attention-icon-safe');

        }


        // Empty / healthy state
        if (lowStockItems.length === 0) {

            container.innerHTML = `

                <div class="py-8 text-center">

                    <div
                        class="w-11 h-11 mx-auto
                               rounded-full
                               flex items-center
                               justify-center"
                        style="
                            background-color: #ECFDF5;
                            color: #059669;
                        "
                    >

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

                    <p
                        class="text-sm font-semibold
                               text-gray-700 mt-3"
                    >
                        Stock levels are sufficient
                    </p>

                    <p
                        class="text-xs text-gray-400 mt-1"
                    >
                        No items currently require attention.
                    </p>

                </div>

            `;

            return;

        }


        // Low stock rows
        container.innerHTML =
            lowStockItems
                .map(function (item) {

                    const quantity = Number(item.quantity) || 0;

                    const minimumStock = Number(item.min_stock) || 0;

                    const shortage = Math.max(0, minimumStock - quantity);

                    const itemName = escapeHtml(item.item_name);

                    const itemCode = escapeHtml(item.item_code || '');

                    const itemUrl = escapeHtml(item.url || '#');


                    return `

                        <a
                            href="${itemUrl}"
                            class="stock-attention-item"
                            title="View ${itemName} details"
                        >

                            <div
                                class="flex items-center
                                       justify-between gap-4"
                            >

                                <div class="min-w-0 flex-1">

                                    <div
                                        class="flex flex-wrap
                                               items-center gap-2"
                                    >

                                        <p
                                            class="text-sm
                                                   font-semibold
                                                   text-gray-800
                                                   truncate"
                                        >
                                            ${itemName}
                                        </p>

                                        ${
                                            itemCode
                                                ? `
                                                    <span
                                                        class="
                                                            text-[10px]
                                                            font-medium
                                                            text-gray-400
                                                            bg-gray-50
                                                            px-2 py-0.5
                                                            rounded-full
                                                        "
                                                    >
                                                        ${itemCode}
                                                    </span>
                                                `
                                                : ''
                                        }

                                        <span class="low-stock-badge">
                                            Low Stock
                                        </span>

                                    </div>


                                    <div
                                        class="flex flex-wrap
                                               items-center gap-x-4
                                               gap-y-1 mt-2"
                                    >

                                        <p class="text-xs text-gray-500">
                                            Current:
                                            <span
                                                class="font-semibold
                                                       text-gray-700"
                                            >
                                                ${quantity}
                                            </span>
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            Minimum:
                                            <span
                                                class="font-semibold
                                                       text-gray-700"
                                            >
                                                ${minimumStock}
                                            </span>
                                        </p>

                                        <p class="text-xs text-red-600">
                                            Short by:
                                            <span class="font-semibold">
                                                ${shortage}
                                            </span>
                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="flex items-center
                                           gap-4 flex-shrink-0"
                                >

                                    <div class="text-right">

                                        <p
                                            class="text-lg font-bold"
                                            style="color: #DC2626;"
                                        >
                                            ${quantity}
                                        </p>

                                        <p
                                            class="text-[11px]
                                                   text-gray-400"
                                        >
                                            remaining
                                        </p>

                                    </div>


                                    <svg
                                        class="w-4 h-4
                                               stock-attention-arrow"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>

                                </div>

                            </div>

                        </a>

                    `;

                })
                .join('');

    }


    /*
    |--------------------------------------------------------------------------
    | Low Stock Summary State
    |--------------------------------------------------------------------------
    */

    function updateLowStockCard(lowStockCount) {

        const card = document.getElementById('low-stock-card');
        const number = document.getElementById('low-stock');
        const description = document.getElementById('low-stock-description');
        const icon = document.getElementById('low-stock-icon');

        const count = Number(lowStockCount) || 0;


        if (count === 0) {

            card.classList.add('stock-card-safe');

            icon.classList.add('stock-icon-safe');

            number.style.color = '#059669';

            description.style.color = '#059669';

            description.textContent = 'Stock levels are sufficient';

            return;

        }


        card.classList.remove('stock-card-safe');

        icon.classList.remove('stock-icon-safe');

        number.style.color = '#DC2626';

        description.style.color = '#DC2626';

        description.textContent =
            count === 1
                ? 'Item needs attention'
                : 'Items need attention';

    }


    /*
    |--------------------------------------------------------------------------
    | Retrieve Dashboard Data
    |--------------------------------------------------------------------------
    */

    async function updateDashboardStats() {

        try {

            const response = await fetch(
                "{{ route('dashboard.stats') }}",
                {

                    headers: {

                        'Accept': 'application/json',

                        'X-Requested-With': 'XMLHttpRequest'

                    },

                    cache: 'no-store'

                }
            );


            if (!response.ok) {

                throw new Error('Failed to retrieve dashboard data.');

            }


            const data = await response.json();


            // Summary
            document.getElementById('items-with-readings')
                .textContent = data.items_with_readings;

            document.getElementById('items-without-readings')
                .textContent = data.items_without_readings;

            const lastReadingElement = document.getElementById('last-reading-at');

            if (data.last_reading_at) {
                lastReadingElement.textContent =
                    new Date(data.last_reading_at)
                        .toLocaleString('en-MY', {
                            dateStyle: 'medium',
                            timeStyle: 'short'
                        });
            } else {
                lastReadingElement.textContent = 'No readings recorded yet';
            }

            document.getElementById('total-items').textContent = data.total_items;

            document.getElementById('total-sensors').textContent = data.total_sensors;

            document.getElementById('low-stock').textContent = data.low_stock;


            updateLowStockCard(data.low_stock);


            // Chart
            updateStockChart(data.stock_overview);


            // Stock attention
            updateStockAttention(data.stock_overview);


            // Updated time
            document.getElementById('dashboard-updated-at').textContent = data.updated_at;

        }

        catch (error) {

            console.error('Dashboard update error:', error);

        }

    }


    // Initial load
    updateDashboardStats();


    // Auto refresh every 5 seconds
    setInterval(updateDashboardStats, 5000);

});

</script>

@endsection