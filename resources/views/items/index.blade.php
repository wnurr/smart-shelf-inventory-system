@extends('layouts.app')

@section('content')

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8"
    x-data="{
        showAddModal: {{ $errors->any() ? 'true' : 'false' }}
    }"
>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-4 mb-7">

        <div>
            <div class="ui-eyebrow"><span></span>INVENTORY SETUP</div>
            <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">
                Item Management
            </h1>

            <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">
                Manage inventory items and their shelf assignments.
            </p>
        </div>

        @if(auth()->user()->hasPermission('manage_items'))

            <button
                type="button"
                @click="showAddModal = true"
                class="inline-flex items-center justify-center gap-2
                       px-4 py-2.5 rounded-lg
                       text-sm font-medium text-white
                       transition-all duration-200
                       hover:-translate-y-0.5"
                style="background-color: #243B53;"
            >
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
                        d="M12 4v16m8-8H4"
                    />
                </svg>

                Add Item
            </button>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- ITEM SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5"
    data-live-region="item-summary">

        {{-- Total Items --}}
        <div class="item-summary-card ui-summary">

            <div>
                <p class="item-summary-label">
                    Total Items
                </p>

                <p class="item-summary-number">
                    {{ $totalItems }}
                </p>

                <p class="item-summary-description">
                    Registered inventory items
                </p>
            </div>

            <div class="item-summary-icon">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M20 7l-8-4-8 4
                           m16 0l-8 4
                           m8-4v10l-8 4
                           m0-10L4 7
                           m8 4v10
                           M4 7v10l8 4"
                    />
                </svg>

            </div>

        </div>


        {{-- Linked Sensors --}}
        <div class="item-summary-card ui-summary">

            <div>
                <p class="item-summary-label">
                    Linked Sensors
                </p>

                <p class="item-summary-number">
                    {{ $linkedSensors }}
                </p>

                <p class="item-summary-description">
                    Items currently monitored
                </p>
            </div>

            <div class="item-summary-icon item-summary-icon-success">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M13 10V3L4 14h7v7l9-11h-7z"
                    />
                </svg>

            </div>

        </div>


        {{-- Unlinked Items --}}
        <div class="item-summary-card ui-summary">

            <div>
                <p class="item-summary-label">
                    Unlinked Items
                </p>

                <p
                    class="item-summary-number"
                    @if($unlinkedItems > 0)
                        style="color: #B45309;"
                    @endif
                >
                    {{ $unlinkedItems }}
                </p>

                <p class="item-summary-description">
                    Items without a sensor
                </p>
            </div>

            <div class="item-summary-icon item-summary-icon-warning">

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 9v3.75
                           m9-.75a9 9 0 11-18 0
                           9 9 0 0118 0
                           zm-9 3.75h.008v.008H12v-.008z"
                    />
                </svg>

            </div>

        </div>

    </div>


{{-- ========================================================= --}}
{{-- SEARCH & SORT --}}
{{-- ========================================================= --}}

<x-search-filter
    :action="route('items.index')"
    :search="$search"
    placeholder="Search item name or code..."
    :clear-url="route('items.index')"
    :show-clear="$search !== '' || $sort !== 'asc'"
    class="mb-5"
>

    <select
        name="sort"
        class="h-10
               md:w-48
               rounded-lg
               border-gray-300
               text-sm
               focus:border-[#F59E0B]
               focus:ring-[#F59E0B]"
    >
        <option value="asc" @selected($sort === 'asc')>
            Item Name : A &gt; Z
        </option>

        <option value="desc" @selected($sort === 'desc')>
            Item Name : Z &gt; A
        </option>
    </select>

</x-search-filter>

    {{-- ========================================================= --}}
    {{-- ITEMS TABLE --}}
    {{-- ========================================================= --}}

    <div class="items-panel ui-surface"
    data-live-region="items-table">

        {{-- Table Header --}}
        <div class="px-5 py-4 border-b border-gray-100">

            <div class="flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-2">

                <div>
                    <h2 class="text-sm font-semibold text-gray-900">
                        Inventory Items
                    </h2>

                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $items->total() }}
                        {{ Str::plural('item', $items->total()) }}
                        found
                    </p>
                </div>

                @if($search !== '' || $sort !== 'asc')

    <div class="text-xs text-gray-400">
        Filtered results
    </div>

@endif

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead>

                    <tr class="bg-gray-50
                               border-b border-gray-100
                               text-left
                               text-xs font-semibold
                               text-gray-500
                               uppercase tracking-wide">

                        <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3">
                            Item
                        </th>

                        <th class="px-5 py-3">
                            Location
                        </th>

                        <th class="px-5 py-3">
                            Sensor
                        </th>

                        <th class="px-5 py-3">
                            Current Stock
                        </th>

                        <th class="px-5 py-3">
                            Minimum
                        </th>

                        <th class="px-5 py-3">
                            Status
                        </th>

                        <th class="px-5 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($items as $item)

                        @php
                            $reading = $item->latestReading;
                            $stockStatus = $item->stockStatus();
                        @endphp

                        <tr class="item-row ui-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" :paginator="$items" /></td>

                            {{-- ITEM --}}
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


                                    <div class="min-w-0">

                                        <a
                                            href="{{ route('items.show', $item) }}"
                                            class="font-semibold text-gray-900
                                                   hover:underline"
                                            style="text-decoration-color: #284B63;"
                                        >
                                            {{ $item->item_name }}
                                        </a>

                                        <div class="mt-1">

                                            <span class="item-code">
                                                {{ $item->item_code }}
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- LOCATION --}}
                            <td class="px-5 py-4">

                                @if($item->location)

                                    <div class="flex items-center gap-2 text-gray-600">

                                        <svg
                                            class="w-4 h-4 text-gray-400 shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657
                                                   L13.414 20.9
                                                   a2 2 0 01-2.828 0
                                                   l-4.243-4.243
                                                   a8 8 0 1111.314 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0
                                                   3 3 0 016 0z"
                                            />
                                        </svg>

                                        <span class="whitespace-nowrap">
                                            {{ $item->location->label() }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-gray-400">
                                        Not assigned
                                    </span>

                                @endif

                            </td>


                            {{-- SENSOR --}}
                            <td class="px-5 py-4">

                                @if($item->sensor)

                                    <span class="sensor-badge">

                                        <span class="sensor-dot"></span>

                                        {{ $item->sensor->sensor_code }}

                                    </span>

                                @else

                                    <span class="sensor-unlinked-badge">

                                        <span class="sensor-unlinked-dot"></span>

                                        Not linked

                                    </span>

                                @endif

                            </td>


                            {{-- CURRENT STOCK --}}
                            <td class="px-5 py-4">

                                @if(!$item->sensor)

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @elseif(!$reading)

                                    <div>
                                        <span class="text-gray-400">
                                            —
                                        </span>

                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                            Awaiting data
                                        </p>
                                    </div>

                                @else

                                    @if($stockStatus === 'out_of_stock')
                                        <span class="font-semibold text-red-600">
                                            {{ $reading->quantity }}
                                        </span>
                                    @elseif($stockStatus === 'low_stock')
                                        <span class="font-semibold text-amber-600">
                                            {{ $reading->quantity }}
                                        </span>
                                    @else
                                        <span class="font-semibold text-gray-900">
                                            {{ $reading->quantity }}
                                        </span>
                                    @endif

                                    <span class="text-xs text-gray-400">
                                        units
                                    </span>

                                @endif

                            </td>


                            {{-- MINIMUM STOCK --}}
                            <td class="px-5 py-4 text-gray-600">

                                <span class="font-medium">
                                    {{ $item->min_stock }}
                                </span>

                                <span class="text-xs text-gray-400">
                                    units
                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4">

                                @if(!$item->sensor)

                                    <span class="status-badge status-unmonitored">
                                        <span class="status-dot"></span>
                                        Not Monitored
                                    </span>

                                @elseif($stockStatus === 'no_data')

                                    <span class="status-badge status-none">
                                        <span class="status-dot"></span>
                                        No Data
                                    </span>

                                @elseif($stockStatus === 'out_of_stock')

                                    <span class="status-badge status-danger">
                                        <span class="status-dot"></span>
                                        Out of Stock
                                    </span>

                                @elseif($stockStatus === 'low_stock')

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


                            {{-- ACTION --}}
                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('items.show', $item) }}"
                                    class="view-details-link"
                                >
                                    View Details

                                    <svg
                                        class="w-3.5 h-3.5"
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

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
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
                                            d="M20 7l-8-4-8 4
                                               m16 0l-8 4
                                               m8-4v10l-8 4
                                               m0-10L4 7
                                               m8 4v10
                                               M4 7v10l8 4"
                                        />
                                    </svg>

                                </div>


@if($search !== '')

    <h3 class="text-sm font-semibold text-gray-700 mt-3">
        No matching items
    </h3>

    <p class="text-xs text-gray-400 mt-1">
        No items match "{{ $search }}".
    </p>

    <a
        href="{{ route('items.index') }}"
        class="inline-flex items-center
               mt-4 px-3.5 py-2
               rounded-lg
               border border-gray-200
               text-xs font-semibold
               text-gray-600
               hover:bg-gray-50"
    >
        Clear Search
    </a>

@else

    <h3 class="text-sm font-semibold text-gray-700 mt-3">
        No inventory items yet
    </h3>

    <p class="text-xs text-gray-400 mt-1">
        Start by registering your first inventory item.
    </p>

    @if(auth()->user()->hasPermission('manage_items'))

        <button
            type="button"
            @click="showAddModal = true"
            class="inline-flex items-center gap-1.5
                   mt-4 px-3.5 py-2
                   rounded-lg
                   text-xs font-semibold
                   text-white"
            style="background-color: #243B53;"
        >
            + Add Item
        </button>

    @endif

@endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($items->hasPages())

            <div class="px-5 py-4 border-t border-gray-100">

                <x-simple-pagination :paginator="$items" />

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- ADD ITEM MODAL --}}
    {{-- ========================================================= --}}

    @if(auth()->user()->hasPermission('manage_items'))

        <div
            x-show="showAddModal"
            x-cloak
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   px-4 py-6"
            style="display: none;"
        >

            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-black/50"
                @click="showAddModal = false"
            ></div>


            {{-- Modal --}}
            <div
                class="relative w-full max-w-xl
                       max-h-[90vh] overflow-y-auto"
                @click.away="showAddModal = false"
            >

                <div class="bg-white rounded-xl
                            shadow-xl
                            border border-gray-200
                            overflow-hidden">

                    {{-- Header --}}
                    <div
                        class="px-6 py-5
                               flex items-center justify-between"
                        style="background-color: #243B53;"
                    >

                        <div class="flex items-center gap-3">

                            <div class="h-10 w-10
                                        rounded-lg
                                        bg-white/10
                                        flex items-center
                                        justify-center
                                        shrink-0">

                                <svg
                                    class="h-5 w-5 text-white"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
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
                                <h2 class="text-lg font-semibold text-white">
                                    Add Item
                                </h2>

                                <p class="text-sm text-white/60">
                                    Register a new inventory item.
                                </p>
                            </div>

                        </div>


                        <button
                            type="button"
                            @click="showAddModal = false"
                            class="text-white/70
                                   hover:text-white
                                   transition-colors"
                            aria-label="Close modal"
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
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>

                    </div>


                    {{-- Form --}}
                    <form
                        method="POST"
                        action="{{ route('items.store') }}"
                        class="px-6 py-6 space-y-6"
                    >
                        @csrf


                        {{-- ========================================= --}}
                        {{-- ITEM INFORMATION --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="form-section-heading">
                                <p class="form-section-title">
                                    Item Information
                                </p>

                                <p class="form-section-description">
                                    Basic identification details for this item.
                                </p>
                            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-3
                                        gap-4 mt-4">

                                {{-- Item Name --}}
                                <div class="sm:col-span-2">

                                    <label
                                        for="item_name"
                                        class="form-label"
                                    >
                                        Item Name
                                    </label>

                                    <input
                                        id="item_name"
                                        name="item_name"
                                        value="{{ old('item_name') }}"
                                        placeholder="e.g. Nails"
                                        required
                                        class="form-input
                                               @error('item_name')
                                                   form-input-error
                                               @enderror"
                                    >

                                    @error('item_name')
                                        <p class="form-error">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>


                                {{-- Item Code --}}
                                <div>

                                    <label
                                        for="item_code"
                                        class="form-label"
                                    >
                                        Item Code
                                    </label>

                                    <input
                                        id="item_code"
                                        name="item_code"
                                        value="{{ old('item_code') }}"
                                        placeholder="F001"
                                        required
                                        class="form-input font-mono
                                               @error('item_code')
                                                   form-input-error
                                               @enderror"
                                    >

                                    @error('item_code')
                                        <p class="form-error">
                                            {{ $message }}
                                        </p>
                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- STOCK SETTING --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="form-section-heading">
                                <p class="form-section-title">
                                    Stock Setting
                                </p>

                                <p class="form-section-description">
                                    Define when this item should require stock attention.
                                </p>
                            </div>


                            <div class="mt-4">

                                <label
                                    for="min_stock"
                                    class="form-label"
                                >
                                    Minimum Stock
                                </label>

                                <div class="relative">

                                    <input
                                        id="min_stock"
                                        name="min_stock"
                                        type="number"
                                        min="0"
                                        value="{{ old('min_stock', 0) }}"
                                        required
                                        class="form-input pr-16
                                               @error('min_stock')
                                                   form-input-error
                                               @enderror"
                                    >

                                    <span class="absolute
                                                 inset-y-0 right-3
                                                 flex items-center
                                                 text-xs text-gray-400
                                                 pointer-events-none">
                                        units
                                    </span>

                                </div>

                                <p class="form-help">
                                    Stock at or below this value will be marked as low stock.
                                </p>

                                @error('min_stock')
                                    <p class="form-error">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- SHELF ASSIGNMENT --}}
                        {{-- ========================================= --}}

                        <div>

                            <div class="form-section-heading">
                                <p class="form-section-title">
                                    Shelf Assignment
                                </p>

                                <p class="form-section-description">
                                    Assign the item to a storage location and smart shelf sensor.
                                </p>
                            </div>


                            <div class="grid grid-cols-1 sm:grid-cols-2
                                        gap-4 mt-4">

                                {{-- Location --}}
                                <div>

                                    <label
                                        for="location_id"
                                        class="form-label"
                                    >
                                        Location
                                    </label>

                                    <select
                                        id="location_id"
                                        name="location_id"
                                        required
                                        class="form-input
                                               @error('location_id')
                                                   form-input-error
                                               @enderror"
                                    >

                                        <option value="">
                                            Select Location
                                        </option>

                                        @foreach($locations as $location)

                                            <option
                                                value="{{ $location->id }}"
                                                @selected(
                                                    old('location_id') == $location->id
                                                )
                                            >
                                                {{ $location->label() }}
                                            </option>

                                        @endforeach

                                    </select>


                                    @error('location_id')
                                        <p class="form-error">
                                            {{ $message }}
                                        </p>
                                    @enderror


                                    @if($locations->isEmpty())

                                        <p class="form-warning">
                                            No locations available.
                                            Please create a location first.
                                        </p>

                                    @else

                                        <p class="form-help">
                                            Available storage locations only.
                                        </p>

                                    @endif

                                </div>


                                {{-- Sensor --}}
                                <div>

                                    <label
                                        for="sensor_id"
                                        class="form-label"
                                    >
                                        Sensor

                                        <span class="font-normal text-gray-400">
                                            (Optional)
                                        </span>
                                    </label>

                                    <select
                                        id="sensor_id"
                                        name="sensor_id"
                                        class="form-input
                                               @error('sensor_id')
                                                   form-input-error
                                               @enderror"
                                    >

                                        <option value="">
                                            No Sensor
                                        </option>

                                        @foreach($sensors as $sensor)

                                            <option
                                                value="{{ $sensor->id }}"
                                                @selected(
                                                    old('sensor_id') == $sensor->id
                                                )
                                            >
                                                {{ $sensor->sensor_code }}
                                            </option>

                                        @endforeach

                                    </select>


                                    @error('sensor_id')
                                        <p class="form-error">
                                            {{ $message }}
                                        </p>
                                    @enderror


                                    @if($sensors->isEmpty())

                                        <p class="form-warning">
                                            No available sensors.
                                            You can assign one later.
                                        </p>

                                    @else

                                        <p class="form-help">
                                            Only unassigned sensors are shown.
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- ========================================= --}}
                        {{-- ACTIONS --}}
                        {{-- ========================================= --}}

                        <div class="flex items-center
                                    justify-end gap-3
                                    pt-5
                                    border-t border-gray-100">

                            <button
                                type="button"
                                @click="showAddModal = false"
                                class="px-4 py-2.5
                                       rounded-lg
                                       text-sm font-medium
                                       text-gray-600
                                       hover:bg-gray-50
                                       transition-colors"
                            >
                                Cancel
                            </button>


                            <button
                                type="submit"
                                class="inline-flex
                                       items-center gap-2
                                       px-4 py-2.5
                                       rounded-lg
                                       text-sm font-medium
                                       text-white
                                       transition-colors"
                                style="background-color: #243B53;"
                            >

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>

                                Add Item

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- ========================================================= --}}
{{-- STYLES --}}
{{-- ========================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | Summary Cards
    |--------------------------------------------------------------------------
    */

    .item-summary-card {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;

        padding: 1.125rem 1.25rem;

        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;

        box-shadow:
            0 1px 2px rgba(0, 0, 0, 0.025);
    }

    .item-summary-label {
        color: #6B7280;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .item-summary-number {
        margin-top: 0.35rem;

        color: #111827;
        font-size: 1.5rem;
        line-height: 1.75rem;
        font-weight: 700;
    }

    .item-summary-description {
        margin-top: 0.35rem;

        color: #9CA3AF;
        font-size: 0.7rem;
    }

    .item-summary-icon {
        width: 2.5rem;
        height: 2.5rem;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 0.625rem;
    }

    .item-summary-icon-success {
        color: #15803D;
        background: #DCFCE7;
    }

    .item-summary-icon-warning {
        color: #B45309;
        background: #FEF3C7;
    }


    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    .filter-panel {
        padding: 1rem;

        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
    }

    .filter-apply-button {
        padding: 0.625rem 1rem;

        color: #FFFFFF;
        background: #284B63;

        border-radius: 0.5rem;

        font-size: 0.875rem;
        font-weight: 500;

        transition:
            background-color 0.15s ease,
            transform 0.15s ease;
    }

    .filter-apply-button:hover {
        background: #1E394C;
    }

    .filter-clear-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0.625rem 1rem;

        color: #4B5563;
        background: #FFFFFF;

        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;

        font-size: 0.875rem;
        font-weight: 500;

        transition: background-color 0.15s ease;
    }

    .filter-clear-button:hover {
        background: #F9FAFB;
    }


    /*
    |--------------------------------------------------------------------------
    | Items Panel
    |--------------------------------------------------------------------------
    */

    .items-panel {
        overflow: hidden;

        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;

        box-shadow:
            0 1px 2px rgba(0, 0, 0, 0.025);
    }


    /*
    |--------------------------------------------------------------------------
    | Item Row
    |--------------------------------------------------------------------------
    */

    .item-row {
        transition: background-color 0.15s ease;
    }

    .item-row:hover {
        background-color: #F8FAFC;
    }


    /*
    |--------------------------------------------------------------------------
    | Item
    |--------------------------------------------------------------------------
    */

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

    .item-code {
        display: inline-flex;

        padding: 0.2rem 0.45rem;

        color: #6B7280;
        background: #F3F4F6;

        border-radius: 0.35rem;

        font-family: monospace;
        font-size: 0.7rem;
    }


    /*
    |--------------------------------------------------------------------------
    | Sensor
    |--------------------------------------------------------------------------
    */

    .sensor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;

        padding: 0.3rem 0.55rem;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 0.4rem;

        font-size: 0.75rem;
        font-weight: 600;
    }

    .sensor-dot,
    .sensor-unlinked-dot {
        width: 0.375rem;
        height: 0.375rem;

        flex-shrink: 0;

        background: currentColor;

        border-radius: 9999px;
    }

    .sensor-unlinked-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;

        padding: 0.3rem 0.55rem;

        color: #B45309;
        background: #FEF3C7;

        border-radius: 0.4rem;

        font-size: 0.75rem;
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

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

        flex-shrink: 0;

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

    .status-unmonitored {
        color: #B45309;
        background: #FFFBEB;
    }


    /*
    |--------------------------------------------------------------------------
    | View Details
    |--------------------------------------------------------------------------
    */

    .view-details-link {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;

        color: #284B63;

        font-size: 0.8rem;
        font-weight: 600;

        white-space: nowrap;

        transition:
            gap 0.15s ease,
            color 0.15s ease;
    }

    .view-details-link:hover {
        gap: 0.55rem;
        color: #1E394C;
    }


    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Modal Form
    |--------------------------------------------------------------------------
    */

    .form-section-heading {
        padding-bottom: 0.65rem;

        border-bottom: 1px solid #F3F4F6;
    }

    .form-section-title {
        color: #374151;

        font-size: 0.7rem;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-section-description {
        margin-top: 0.2rem;

        color: #9CA3AF;
        font-size: 0.7rem;
    }

    .form-label {
        display: block;

        margin-bottom: 0.375rem;

        color: #374151;

        font-size: 0.8rem;
        font-weight: 600;
    }

    .form-input {
        width: 100%;

        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;

        font-size: 0.875rem;

        transition:
            border-color 0.15s ease,
            box-shadow 0.15s ease;
    }

    .form-input:focus {
        border-color: #284B63;

        --tw-ring-color:
            rgba(40, 75, 99, 0.18);
    }

    .form-input-error {
        border-color: #DC2626;
    }

    .form-error {
        margin-top: 0.3rem;

        color: #DC2626;
        font-size: 0.7rem;
    }

    .form-help {
        margin-top: 0.35rem;

        color: #9CA3AF;
        font-size: 0.7rem;
    }

    .form-warning {
        margin-top: 0.35rem;

        color: #B45309;
        font-size: 0.7rem;
    }


    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 640px) {

        .item-summary-card {
            padding: 1rem;
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

    .item-summary-label{color:#64748B!important;font-size:.7rem!important;letter-spacing:.055em!important}.item-summary-number{color:#0F172A;font-size:2rem!important;letter-spacing:-.035em}.item-summary-description{color:#94A3B8!important}
    .item-summary-icon{width:2.8rem!important;height:2.8rem!important;border-radius:.8rem!important;background:#FFF7E6!important;color:#B96B00!important;border:1px solid #FDE3AE}.item-summary-icon-success{background:#ECFDF5!important;color:#059669!important;border-color:#D1FAE5}.item-summary-icon-warning{background:#FFF7E6!important;color:#B96B00!important;border-color:#FDE3AE}
    .items-panel{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}.item-icon{background:#F1F5F9!important;color:#334E68!important;border:1px solid #E2E8F0!important}
    .form-input{border-color:#CBD5E1!important;border-radius:.65rem!important}.form-input:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important}

</style>

@endsection