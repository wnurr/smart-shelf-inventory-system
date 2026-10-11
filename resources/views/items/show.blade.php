@extends('layouts.app')

@section('content')

@php
    $latestReading = $item->latestReading;
    $stockStatus = $item->stockStatus();
@endphp


<div
    class="max-w-6xl mx-auto px-4 sm:px-6 py-8"
    x-data="{
        showEditModal: {{ $errors->any() ? 'true' : 'false' }},
        showDeleteModal: false
    }"
    x-effect="
        document.body.classList.toggle(
            'overflow-hidden',
            showEditModal || showDeleteModal
        )
    "
>


    {{-- ========================================================= --}}
    {{-- BACK --}}
    {{-- ========================================================= --}}

    <a
        href="{{ route('items.index') }}"
        class="inline-flex items-center gap-1.5
               text-sm text-gray-500
               hover:text-gray-700
               mb-5"
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
                d="M15 19l-7-7 7-7"
            />
        </svg>

        Back to Items
    </a>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row
                sm:items-start sm:justify-between
                gap-4 mb-7">

        <div class="flex items-center gap-4">

            <div class="item-main-icon">

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


            <div>

                <div class="item-eyebrow"><span></span> INVENTORY ITEM</div>

                <div class="flex flex-wrap items-center gap-2">

                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $item->item_name }}
                    </h1>


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

                </div>


                <p class="text-sm text-gray-500 mt-1">

                    Item Code

                    <span class="item-code ml-1">
                        {{ $item->item_code }}
                    </span>

                </p>

            </div>

        </div>


@if(auth()->user()->hasPermission('manage_items'))

    <div class="flex items-center gap-2">

{{-- EDIT ITEM --}}
<button
    type="button"
    @click="showEditModal = true"
    class="item-edit-btn"
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
            d="M11 5H6a2 2 0 00-2 2v11
               a2 2 0 002 2h11a2 2 0 002-2v-5
               m-1.414-9.414a2 2 0 112.828 2.828
               L11.828 15H9v-2.828
               l8.586-8.586z"
        />
    </svg>

    Edit Item
</button>


{{-- DELETE ITEM --}}
<button
    type="button"
    @click="showDeleteModal = true"
    class="item-delete-btn"
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
            d="M19 7l-.867 12.142
               A2 2 0 0116.138 21H7.862
               a2 2 0 01-1.995-1.858L5 7
               m5 4v6m4-6v6
               m1-10V4a1 1 0 00-1-1h-4
               a1 1 0 00-1 1v3
               M4 7h16"
        />
    </svg>

    Delete
</button>


        {{-- ================================================= --}}
        {{-- DELETE CONFIRMATION MODAL --}}
        {{-- ================================================= --}}

        <div
            x-show="showDeleteModal"
            x-cloak
            class="fixed inset-0 z-50
                   flex items-center justify-center
                   px-4"
            style="display: none;"
        >

            {{-- Backdrop --}}
            <div
                class="absolute inset-0 bg-slate-950/55 backdrop-blur-[2px]"
                @click="showDeleteModal = false"
            ></div>


            {{-- Modal --}}
            <div
                class="relative
                       w-full max-w-md
                       bg-white
                       rounded-xl
                       shadow-xl
                       border border-gray-200
                       overflow-hidden"
                @click.away="showDeleteModal = false"
            >

                <div class="p-6">

                    {{-- Icon --}}
                    <div
                        class="w-11 h-11
                               rounded-full
                               bg-red-50
                               flex items-center justify-center
                               mb-4"
                    >
                        <svg
                            class="w-5 h-5 text-red-600"
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
                                   c1.54 0 2.5-1.667 1.73-3
                                   L13.73 4
                                   c-.77-1.333-2.69-1.333-3.46 0
                                   L3.34 16
                                   c-.77 1.333.19 3 1.73 3z"
                            />
                        </svg>
                    </div>


                    <h2 class="text-lg font-semibold text-gray-900">
                        Delete Item?
                    </h2>

                    <p class="text-sm text-gray-500 mt-2 leading-6">
                        You are about to delete
                        <span class="font-semibold text-gray-700">
                            {{ $item->item_name }}
                        </span>.
                        This action cannot be undone.
                    </p>


                    {{-- Item info --}}
                    <div
                        class="mt-4
                               px-4 py-3
                               rounded-lg
                               bg-gray-50
                               border border-gray-100"
                    >
                        <div class="flex items-center justify-between gap-4">

                            <span class="text-xs text-gray-500">
                                Item Code
                            </span>

                            <span class="text-xs font-mono font-semibold text-gray-700">
                                {{ $item->item_code }}
                            </span>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 mt-6">

                        <button
                            type="button"
                            @click="showDeleteModal = false"
                            class="delete-modal-cancel"
                        >
                            Cancel
                        </button>

                        <form
                            method="POST"
                            action="{{ route('items.destroy', $item) }}"
                            class="m-0"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="delete-modal-confirm"
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
                                        d="M19 7l-.867 12.142
                                           A2 2 0 0116.138 21H7.862
                                           a2 2 0 01-1.995-1.858L5 7
                                           m5 4v6m4-6v6
                                           m1-10V4a1 1 0 00-1-1h-4
                                           a1 1 0 00-1 1v3
                                           M4 7h16"
                                    />
                                </svg>

                                Delete Item
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif

    </div>



    {{-- ========================================================= --}}
    {{-- EDIT ITEM MODAL --}}
    {{-- ========================================================= --}}

    @if(auth()->user()->hasPermission('manage_items'))

        <div
            x-show="showEditModal"
            x-cloak
            @keydown.escape.window="showEditModal = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4"
            style="display: none;"
        >

            <div
                class="absolute inset-0 bg-slate-950/55 backdrop-blur-[2px]"
                @click="showEditModal = false"
            ></div>

            <div
                x-show="showEditModal"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @click.stop
                class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden flex flex-col"
                style="max-height: 92vh;"
            >

                <div
                    class="flex items-center justify-between gap-4 px-6 py-5 text-white flex-shrink-0"
                    style="background:linear-gradient(135deg,#243B53 0%,#334E68 100%);"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center justify-center w-10 h-10 rounded-lg flex-shrink-0"
                            style="background:rgba(255,255,255,.10);"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>

                        <div>
                            <h2 class="text-lg font-semibold leading-tight">Edit Item</h2>
                            <p class="text-sm mt-1 text-white/70">Update item information and shelf assignment.</p>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="showEditModal = false"
                        class="flex items-center justify-center w-9 h-9 rounded-lg text-white/60 hover:text-white hover:bg-white/10 transition"
                        aria-label="Close modal"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form
                    method="POST"
                    action="{{ route('items.update', $item) }}"
                    class="flex flex-col min-h-0"
                >
                    @csrf
                    @method('PUT')

                    <div class="overflow-y-auto px-6 py-6">


                        <section>
                            <p class="edit-item-section-title">Item Information</p>
                            <p class="edit-item-section-help">Basic identification details for this item.</p>
                            <div class="edit-item-divider"></div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="edit_item_name" class="edit-item-label">Item Name</label>
                                    <input
                                        id="edit_item_name"
                                        name="item_name"
                                        type="text"
                                        value="{{ old('item_name', $item->item_name) }}"
                                        required
                                        data-titlecase
                                        class="edit-item-input"
                                    >
                                    @error('item_name')
                                        <p class="edit-item-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="edit_item_code" class="edit-item-label">Item Code</label>
                                    <input
                                        id="edit_item_code"
                                        name="item_code"
                                        type="text"
                                        value="{{ old('item_code', $item->item_code) }}"
                                        required
                                        data-uppercase
                                        class="edit-item-input font-mono"
                                    >
                                    @error('item_code')
                                        <p class="edit-item-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </section>

                        <section class="mt-8">
                            <p class="edit-item-section-title">Stock Setting</p>
                            <p class="edit-item-section-help">Configure the threshold used for low-stock monitoring.</p>
                            <div class="edit-item-divider"></div>

                            <div>
                                <label for="edit_min_stock" class="edit-item-label">Minimum Stock</label>
                                <div class="edit-item-unit-input">
                                    <input
                                        id="edit_min_stock"
                                        name="min_stock"
                                        type="number"
                                        min="0"
                                        value="{{ old('min_stock', $item->min_stock) }}"
                                        required
                                    >
                                    <span>units</span>
                                </div>
                                @error('min_stock')
                                    <p class="edit-item-error">{{ $message }}</p>
                                @enderror
                                <p class="edit-item-help">The system uses this value to determine low-stock status.</p>
                            </div>
                        </section>

                        <section class="mt-8">
                            <p class="edit-item-section-title">Shelf Assignment</p>
                            <p class="edit-item-section-help">Update the storage location and monitoring sensor.</p>
                            <div class="edit-item-divider"></div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="edit_location_id" class="edit-item-label">Location</label>
                                    <select id="edit_location_id" name="location_id" required class="edit-item-input">
                                        @foreach($locations as $location)
                                            <option
                                                value="{{ $location->id }}"
                                                @selected(old('location_id', $item->location_id) == $location->id)
                                            >
                                                {{ $location->label() }}
                                                @if($location->id == $item->location_id)
                                                    (Current)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('location_id')
                                        <p class="edit-item-error">{{ $message }}</p>
                                    @enderror
                                    <p class="edit-item-help">Only available locations and the current location are shown.</p>
                                </div>

                                <div>
                                    <label for="edit_sensor_id" class="edit-item-label">
                                        Sensor <span class="font-normal text-gray-400">(Optional)</span>
                                    </label>
                                    <select id="edit_sensor_id" name="sensor_id" class="edit-item-input">
                                        <option value="">No Sensor</option>
                                        @foreach($sensors as $sensor)
                                            <option
                                                value="{{ $sensor->id }}"
                                                @selected(old('sensor_id', $item->sensor?->id) == $sensor->id)
                                            >
                                                {{ $sensor->sensor_code }}
                                                @if($item->sensor && $sensor->id == $item->sensor->id)
                                                    (Current)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sensor_id')
                                        <p class="edit-item-error">{{ $message }}</p>
                                    @enderror
                                    <p class="edit-item-help">Select No Sensor to remove the current sensor assignment.</p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                        <button type="button" @click="showEditModal = false" class="edit-item-cancel">Cancel</button>
                        <button type="submit" class="edit-item-submit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

    @endif

    {{-- ========================================================= --}}
    {{-- CURRENT STOCK HERO --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">


        <div class="lg:col-span-2 current-stock-card item-ui-surface-dark">

            <div>

                <p class="text-xs font-semibold
                          uppercase tracking-wide"
                   style="color: rgba(255,255,255,.65);">
                    Current Stock
                </p>


                @if($latestReading)

                    <div class="flex items-end gap-2 mt-2">

                        <span class="text-4xl font-bold text-white">
                            {{ $latestReading->quantity }}
                        </span>

                        <span class="text-sm text-white/70 mb-1">
                            units
                        </span>

                    </div>


                    <p class="text-xs text-white/60 mt-3">
                        Minimum stock:
                        {{ $item->min_stock }} units
                    </p>

                @else

                    <p class="text-2xl font-semibold text-white mt-2">
                        No reading available
                    </p>

                    <p class="text-xs text-white/60 mt-3">
                        Waiting for inventory data.
                    </p>

                @endif

            </div>


            <div class="stock-hero-icon">

                <svg
                    class="w-8 h-8"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M3 6l9-4 9 4
                           m-18 0l9 4
                           m-9-4v12l9 4
                           m9-16l-9 4
                           m9-4v12l-9 4
                           m0-12v12"
                    />
                </svg>

            </div>

        </div>



        {{-- Latest weight --}}
        <div class="detail-card item-ui-surface">

            <p class="detail-label">
                Latest Weight
            </p>

            @if($latestReading)

                <p class="text-2xl font-bold text-gray-900 mt-2">
                    {{ number_format($latestReading->weight, 2) }}

                    <span class="text-sm font-medium text-gray-400">
                        g
                    </span>
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    Recorded
                    {{ $latestReading->recorded_at->format(
                        'd M Y, h:i A'
                    ) }}
                </p>

            @else

                <p class="text-lg font-semibold text-gray-400 mt-2">
                    —
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    No reading yet
                </p>

            @endif

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- ASSIGNMENT DETAILS --}}
    {{-- ========================================================= --}}

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-6 item-ui-surface">

        <div class="px-5 py-4 border-b border-gray-100">

            <h2 class="text-sm font-semibold text-gray-900">
                Shelf Assignment
            </h2>

            <p class="text-xs text-gray-500 mt-0.5">
                Storage and sensor configuration for this item.
            </p>

        </div>


        <div class="grid grid-cols-1
                    sm:grid-cols-3
                    divide-y sm:divide-y-0
                    sm:divide-x
                    divide-gray-100">


            {{-- Location --}}
            <div class="p-5">

                <div class="detail-icon">

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

                </div>

                <p class="detail-label mt-3">
                    Location
                </p>

                <p class="detail-value">
                    {{ $item->location?->label() ?? 'Not assigned' }}
                </p>

            </div>



            {{-- Sensor --}}
            <div class="p-5">

                <div class="detail-icon">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
</svg>


                </div>

                <p class="detail-label mt-3">
                    Sensor
                </p>

                @if($item->sensor)

                    <span class="sensor-badge mt-1.5">
                        {{ $item->sensor->sensor_code }}
                    </span>

                @else

                    <p class="detail-value">
                        Not linked
                    </p>

                @endif

            </div>



            {{-- Minimum --}}
            <div class="p-5">

                <div class="detail-icon">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
  <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
</svg>


                </div>

                <p class="detail-label mt-3">
                    Minimum Stock
                </p>

                <p class="detail-value">
                    {{ $item->min_stock }} units
                </p>

            </div>

        </div>

    </div>



{{-- ========================================================= --}}
{{-- RECENT READINGS --}}
{{-- ========================================================= --}}

@php
    $recentReadings = $item->inventoryReadings
        ->sortByDesc('recorded_at')
        ->take(5);
@endphp

<div
    class="bg-white border border-gray-200 rounded-xl overflow-hidden item-ui-surface"
    data-live-region="item-recent-readings"
>

    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div class="px-5 py-4
                border-b border-gray-100
                flex flex-col sm:flex-row
                sm:items-center sm:justify-between
                gap-3">

        <div>

            <div class="flex items-center gap-2">

                <h2 class="text-sm font-semibold text-gray-900">
                    Recent Readings
                </h2>

                @if($recentReadings->isNotEmpty())

                    <span class="recent-reading-count">
                        {{ $recentReadings->count() }}
                    </span>

                @endif

            </div>

            <p class="text-xs text-gray-500 mt-0.5">
                Latest inventory measurements recorded for this item.
            </p>

        </div>


        {{-- View Full History --}}
        <a
            href="{{ route('history.index', ['item_id' => $item->id]) }}"
            class="recent-history-link"
        >
            View Full History

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

    </div>


    {{-- ===================================================== --}}
    {{-- TABLE --}}
    {{-- ===================================================== --}}

    @if($recentReadings->isNotEmpty())

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
                            Recorded At
                        </th>

                        <th class="px-5 py-3">
                            Weight
                        </th>

                        <th class="px-5 py-3">
                            Quantity
                        </th>

                        <th class="px-5 py-3">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach($recentReadings as $reading)

                        <tr class="recent-reading-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" /></td>

                            {{-- ================================= --}}
                            {{-- RECORDED AT --}}
                            {{-- ================================= --}}

                            <td class="px-5 py-3.5">

                                <div class="flex items-center gap-3">

                                    <div class="recent-reading-icon">

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
                                                d="M12 8v4l3 2
                                                   m6-2
                                                   a9 9 0 11-18 0
                                                   9 9 0 0118 0z"
                                            />
                                        </svg>

                                    </div>


                                    <div>

                                        <p class="text-sm font-medium text-gray-700">
                                            {{ $reading->recorded_at->format('d M Y') }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $reading->recorded_at->format('h:i A') }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- ================================= --}}
                            {{-- WEIGHT --}}
                            {{-- ================================= --}}

                            <td class="px-5 py-3.5">

                                <span class="font-medium text-gray-700">
                                    {{ number_format($reading->weight, 2) }}
                                </span>

                                <span class="text-xs text-gray-400">
                                    g
                                </span>

                            </td>


                            {{-- ================================= --}}
                            {{-- QUANTITY --}}
                            {{-- ================================= --}}

                            <td class="px-5 py-3.5">

                                <span class="text-base font-semibold text-gray-900">
                                    {{ $reading->quantity }}
                                </span>

                                <span class="text-xs text-gray-400">
                                    units
                                </span>

                            </td>


                            {{-- ================================= --}}
                            {{-- STATUS --}}
                            {{-- ================================= --}}

                            <td class="px-5 py-3.5">

                                @if($reading->stock_status === 'out_of_stock')

                                    <span class="status-badge status-danger">

                                        <span class="status-dot"></span>

                                        Out of Stock

                                    </span>


                                @elseif($reading->stock_status === 'low_stock')

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

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ================================================= --}}
        {{-- FOOTER --}}
        {{-- ================================================= --}}

        <div class="recent-readings-footer">

            <div class="flex items-center gap-1.5">

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
                        d="M13 16h-1v-4h-1
                           m1-4h.01
                           M21 12
                           a9 9 0 11-18 0
                           9 9 0 0118 0z"
                    />
                </svg>

                <span>
                    Showing latest
                    {{ $recentReadings->count() }}
                    {{ Str::plural('reading', $recentReadings->count()) }}
                </span>

            </div>


        </div>

    @else

        {{-- ================================================= --}}
        {{-- EMPTY STATE --}}
        {{-- ================================================= --}}

        <div class="recent-readings-empty">

            <div class="recent-readings-empty-icon">

                <svg
                    class="w-6 h-6"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.7"
                        d="M12 8v4l3 2
                           m6-2
                           a9 9 0 11-18 0
                           9 9 0 0118 0z"
                    />
                </svg>

            </div>

            <h3 class="text-sm font-semibold text-gray-700 mt-3">
                No readings yet
            </h3>

            <p class="text-xs text-gray-400 mt-1">
                Inventory readings will appear here once data is received from the sensor.
            </p>

        </div>

    @endif

</div>

</div>


<style>

    .item-main-icon {
        width: 3rem;
        height: 3rem;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 0.75rem;
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


    .current-stock-card {
        display: flex;
        align-items: center;
        justify-content: space-between;

        min-height: 150px;

        padding: 1.5rem;

        color: #FFFFFF;
        background: #284B63;

        border-radius: 0.75rem;
    }


    .stock-hero-icon {
        width: 4rem;
        height: 4rem;

        display: flex;
        align-items: center;
        justify-content: center;

        color: rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.1);

        border-radius: 0.75rem;
    }


    .detail-card {
        padding: 1.5rem;

        background: #FFFFFF;

        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
    }


    .detail-label {
        color: #9CA3AF;

        font-size: 0.7rem;
        font-weight: 600;

        text-transform: uppercase;
        letter-spacing: 0.04em;
    }


    .detail-value {
        color: #374151;

        font-size: 0.875rem;
        font-weight: 600;

        margin-top: 0.35rem;
    }


    .detail-icon {
        width: 2rem;
        height: 2rem;

        display: flex;
        align-items: center;
        justify-content: center;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 0.5rem;
    }


    .sensor-badge {
        display: inline-flex;

        padding: 0.3rem 0.55rem;

        color: #284B63;
        background: #EDF3F7;

        border-radius: 0.4rem;

        font-size: 0.75rem;
        font-weight: 600;
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

    .status-unmonitored {
    color: #B45309;
    background: #FFFBEB;
}

    /* ========================================================= */
    /* ITEM ACTION BUTTONS */
    /* ========================================================= */

    .item-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: #284B63;
        color: #FFFFFF;
        border: 1px solid #284B63;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .item-edit-btn:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
        color: #FFFFFF;
    }

    .item-delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem 1rem;
        background: #D98B84;
        color: #FFFFFF;
        border: 1px solid #D98B84;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .item-delete-btn:hover {
        background: #B42318;
    }

    .delete-modal-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0.625rem 1rem;
        background: #FFFFFF;
        color: #4B5563;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .delete-modal-cancel:hover {
        background: #F9FAFB;
        border-color: #9CA3AF;
    }

    .delete-modal-confirm {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-height: 42px;
        padding: 0.625rem 1rem;
        background: #B42318 !important;
        color: #FFFFFF !important;
        border: 1px solid #B42318;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }

    .delete-modal-confirm:hover {
        background: #912018 !important;
        border-color: #912018;
    }

    /* ========================================================= */
/* RECENT READINGS */
/* ========================================================= */

.recent-reading-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 1.4rem;
    height: 1.4rem;
    padding: 0 0.4rem;

    color: #284B63;
    background: #EDF3F7;

    border-radius: 9999px;

    font-size: 0.65rem;
    font-weight: 700;
}


.recent-history-link {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;

    color: #284B63;

    font-size: 0.75rem;
    font-weight: 600;

    white-space: nowrap;

    transition: gap 0.15s ease;
}

.recent-history-link:hover {
    gap: 0.5rem;
    text-decoration: underline;
}


.recent-reading-row {
    transition: background-color 0.15s ease;
}

.recent-reading-row:hover {
    background: #F9FAFB;
}


.recent-reading-icon {
    width: 2rem;
    height: 2rem;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    color: #284B63;
    background: #EDF3F7;

    border-radius: 0.5rem;
}


.recent-readings-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;

    padding: 0.75rem 1.25rem;

    color: #9CA3AF;
    background: #FAFAFA;

    border-top: 1px solid #F3F4F6;

    font-size: 0.7rem;
}


.recent-readings-empty {
    padding: 3.5rem 1.5rem;

    text-align: center;
}


.recent-readings-empty-icon {
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


@media (max-width: 640px) {

    .recent-readings-footer {
        align-items: flex-start;
        flex-direction: column;
    }

}


    /* =========================================================
       EDIT ITEM MODAL
       ========================================================= */

    [x-cloak] {
        display: none !important;
    }

    .edit-item-section-title {
        color: #374151;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .edit-item-section-help {
        margin-top: 0.3rem;
        color: #9CA3AF;
        font-size: 0.7rem;
    }

    .edit-item-divider {
        height: 1px;
        margin: 0.65rem 0 1.2rem;
        background: #EEF0F2;
    }

    .edit-item-label {
        display: block;
        margin-bottom: 0.45rem;
        color: #4B5563;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .edit-item-input {
        width: 100%;
        height: 2.7rem;
        padding: 0 0.8rem;
        color: #111827;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
        border-radius: 0.55rem;
        font-size: 0.875rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .edit-item-input:focus {
        border-color: #284B63;
        outline: none;
        box-shadow: 0 0 0 1px #284B63;
    }

    .edit-item-help {
        margin-top: 0.4rem;
        color: #9CA3AF;
        font-size: 0.68rem;
        line-height: 1.45;
    }

    .edit-item-error {
        margin-top: 0.35rem;
        color: #DC2626;
        font-size: 0.7rem;
    }

    .edit-item-unit-input {
        width: 100%;
        display: flex;
        align-items: stretch;
        overflow: hidden;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
        border-radius: 0.55rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .edit-item-unit-input:focus-within {
        border-color: #284B63;
        box-shadow: 0 0 0 1px #284B63;
    }

    .edit-item-unit-input input {
        min-width: 0;
        flex: 1;
        height: 2.7rem;
        padding: 0 0.8rem;
        border: 0 !important;
        outline: none !important;
        box-shadow: none !important;
        font-size: 0.875rem;
    }

    .edit-item-unit-input span {
        display: flex;
        align-items: center;
        padding: 0 0.8rem;
        color: #6B7280;
        background: #F9FAFB;
        border-left: 1px solid #E5E7EB;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .edit-item-validation {
        padding: 0.9rem 1rem;
        color: #B42318;
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-radius: 0.6rem;
    }

    .edit-item-cancel,
    .edit-item-submit {
        min-height: 2.55rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 0 1rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
        transition: 0.15s ease;
    }

    .edit-item-cancel {
        color: #4B5563;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
    }

    .edit-item-cancel:hover {
        background: #F9FAFB;
    }

    .edit-item-submit {
        color: #FFFFFF;
        background: #284B63;
        border: 1px solid #284B63;
    }

    .edit-item-submit:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
    }

    @media (max-width: 640px) {
        .edit-item-cancel,
        .edit-item-submit {
            min-height: 2.7rem;
        }
    }


    /* Industrial Steel + Safety Amber UI refresh — presentation only */
    .item-eyebrow{display:flex;align-items:center;gap:.5rem;margin-bottom:.45rem;color:#B96B00;font-size:.66rem;font-weight:800;letter-spacing:.12em}
    .item-eyebrow span{width:.4rem;height:.4rem;border-radius:9999px;background:#F59E0B;box-shadow:0 0 0 4px rgba(245,158,11,.10)}
    .item-main-icon{color:#334E68!important;background:#F1F5F9!important;border:1px solid #E2E8F0;border-radius:.85rem!important;box-shadow:0 3px 10px rgba(15,23,42,.04)}
    .item-code{color:#334E68!important;background:#F1F5F9!important;border:1px solid #E2E8F0}
    .item-edit-btn{background:#243B53!important;border-color:#243B53!important;border-radius:.65rem!important;box-shadow:0 4px 12px rgba(36,59,83,.12)}
    .item-edit-btn:hover{background:#334E68!important;border-color:#334E68!important;transform:translateY(-1px)}
    .item-delete-btn{background:#fff!important;color:#B42318!important;border-color:#FECACA!important;border-radius:.65rem!important}.item-delete-btn:hover{background:#FEF2F2!important;border-color:#FCA5A5!important}
    .item-ui-surface,.detail-card{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}
    .current-stock-card{background:linear-gradient(135deg,#243B53 0%,#334E68 100%)!important;border-radius:1rem!important;box-shadow:0 12px 28px rgba(36,59,83,.16);position:relative;overflow:hidden}
    .current-stock-card:after{content:"";position:absolute;width:180px;height:180px;border-radius:9999px;right:-65px;top:-80px;background:rgba(245,158,11,.10)}
    .stock-hero-icon{position:relative;z-index:1;border:1px solid rgba(255,255,255,.14)!important}
    .detail-label{color:#64748B!important;font-weight:800!important;letter-spacing:.06em!important}.detail-value{color:#1E293B!important}
    .detail-icon,.recent-reading-icon{color:#334E68!important;background:#F1F5F9!important;border:1px solid #E2E8F0!important;border-radius:.65rem!important}
    .sensor-badge,.recent-reading-count{color:#B96B00!important;background:#FFF7E6!important;border:1px solid #FDE3AE!important}
    .recent-history-link{color:#334E68!important}.recent-reading-row:hover{background:#F8FAFC!important}
    .edit-item-input,.edit-item-unit-input{border-color:#CBD5E1!important;border-radius:.65rem!important}.edit-item-input:focus,.edit-item-unit-input:focus-within{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important}
    .edit-item-submit{background:#243B53!important;border-color:#243B53!important;border-radius:.6rem!important}.edit-item-submit:hover{background:#334E68!important;border-color:#334E68!important}
    .delete-modal-cancel,.delete-modal-confirm{border-radius:.6rem!important}

</style>

@endsection