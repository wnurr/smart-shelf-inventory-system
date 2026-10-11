@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">

        <div>
            <div class="ui-eyebrow"><span></span>HARDWARE SETUP</div>
            <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">
                Manage Sensors
            </h1>

            <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">
                Register and monitor the two physical sensors connected to the smart shelf prototype.
            </p>
        </div>


        @if(auth()->user()->hasPermission('manage_sensors'))

            @if($registeredSupportedCount < 2)

                <button
    type="button"
    onclick="openAddSensorModal()"
    class="sensor-primary-btn"
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

    Add Sensor
</button>

            @else

                <span
                    class="sensor-disabled-btn"
                    title="Both physical sensor slots are already registered."
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

                    Add Sensor
                </span>

            @endif

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div data-live-region="sensor-summary"
         class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- REGISTERED --}}
        <div class="sensor-summary-card ui-summary">

            <div>
                <p class="sensor-summary-label">
                    Registered Sensors
                </p>

                <p class="sensor-summary-number">
                    {{ $registeredSupportedCount }}
                    <span>/ 2</span>
                </p>

                <p class="sensor-summary-help">
                    Physical slots registered
                </p>
            </div>

            <div class="sensor-summary-icon sensor-summary-primary">

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
                        d="M13 10V3L4 14h7v7l9-11h-7z"
                    />
                </svg>

            </div>

        </div>


        {{-- ASSIGNED --}}
        <div class="sensor-summary-card ui-summary">

            <div>
                <p class="sensor-summary-label">
                    Assigned
                </p>

                <p class="sensor-summary-number">
                    {{ $assignedCount }}
                </p>

                <p class="sensor-summary-help">
                    Sensors linked to items
                </p>
            </div>

            <div class="sensor-summary-icon sensor-summary-blue">

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


        {{-- AVAILABLE --}}
        <div class="sensor-summary-card ui-summary">

            <div>
                <p class="sensor-summary-label">
                    Available
                </p>

                <p class="sensor-summary-number">
                    {{ $availableCount }}
                </p>

                <p class="sensor-summary-help">
                    Ready for item assignment
                </p>
            </div>

            <div class="sensor-summary-icon sensor-summary-green">

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
                        d="M12 8v4m0 4h.01M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"
                    />
                </svg>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LEGACY SENSOR NOTICE --}}
    {{-- ========================================================= --}}

    @if($legacyCount > 0)

        <div class="sensor-notice mb-5">

            <div class="sensor-notice-icon">

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
                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.667 1.73-3L13.73 4c-.77-1.333-2.69-1.333-3.46 0L3.34 16c-.77 1.333.19 3 1.73 3z"
                    />
                </svg>

            </div>

            <div>
                <p class="text-sm font-semibold text-amber-900">
                    Legacy sensor records detected
                </p>

                <p class="text-xs text-amber-700 mt-0.5">
                    {{ $legacyCount }}
                    {{ \Illuminate\Support\Str::plural('record', $legacyCount) }}
                    do not match the current two-sensor prototype.
                    Unassign them from Items first, then delete them from this page.
                </p>
            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- SEARCH --}}
    {{-- ========================================================= --}}

    <x-search-filter
        :action="route('sensors.index')"
        :search="$search"
        placeholder="Search sensor code..."
        :clear-url="route('sensors.index')"
        :show-clear="$search !== ''"
        class="mb-5"
    />


    {{-- ========================================================= --}}
    {{-- SENSOR TABLE --}}
    {{-- ========================================================= --}}

    <div data-live-region="sensor-table" class="sensor-table-card ui-surface">

        {{-- TABLE HEADER --}}
        <div class="sensor-table-header">

            <div>
                <h2 class="text-sm font-semibold text-gray-900">
                    Sensor Registry
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    Hardware identity, connection status and current item assignment.
                </p>
            </div>

            <span class="sensor-count-badge">
                {{ $sensors->total() }}
                {{ \Illuminate\Support\Str::plural('sensor', $sensors->total()) }}
            </span>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                {{-- ================================================= --}}
                {{-- TABLE HEAD --}}
                {{-- ================================================= --}}

                <thead>

                    <tr class="sensor-table-head ui-table-head">

                        <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3 text-left">
                            Sensor Code
                        </th>

                        <th class="px-5 py-3 text-left">
                            Physical Slot
                        </th>

                        <th class="px-5 py-3 text-left">
                            Assigned Item
                        </th>

                        <th class="px-5 py-3 text-left">
                            Connection
                        </th>

                        <th class="px-5 py-3 text-left">
                            Assignment
                        </th>

                        <th class="px-5 py-3 text-right">
                            Action
                        </th>

                    </tr>

                </thead>


                {{-- ================================================= --}}
                {{-- TABLE BODY --}}
                {{-- ================================================= --}}

                <tbody class="divide-y divide-gray-100">

                    @forelse($sensors as $sensor)

                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | PHYSICAL SENSOR
                            |--------------------------------------------------------------------------
                            */

                            $isSupported = in_array(
                                $sensor->sensor_code,
                                $supportedCodes,
                                true
                            );


                            $slotNumber = $isSupported
                                ? str_replace(
                                    'SENSOR-',
                                    '',
                                    $sensor->sensor_code
                                )
                                : null;


                            /*
                            |--------------------------------------------------------------------------
                            | CONNECTION HEARTBEAT
                            |--------------------------------------------------------------------------
                            |
                            | last_seen_at is refreshed whenever the ESP32 polls Laravel.
                            | It is independent from inventory readings, which are only saved
                            | when the calculated quantity changes.
                            |
                            */

                            $lastSeen = $sensor->last_seen_at;

                            $isOnline =
                                $isSupported
                                && $lastSeen
                                && $lastSeen->greaterThanOrEqualTo(
                                    now()->subSeconds(15)
                                );
                        @endphp


                        <tr class="sensor-row ui-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" :paginator="$sensors" /></td>


                            {{-- ===================================== --}}
                            {{-- SENSOR CODE --}}
                            {{-- ===================================== --}}

                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="sensor-code-icon">

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
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>

                                    </div>


                                    <div>

                                        <p class="font-mono font-semibold text-gray-900">
                                            {{ $sensor->sensor_code }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $isSupported
                                                ? 'ESP32 hardware sensor'
                                                : 'Legacy record'
                                            }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- ===================================== --}}
                            {{-- PHYSICAL SLOT --}}
                            {{-- ===================================== --}}

                            <td class="px-5 py-4">

                                @if($isSupported)

                                    <span class="sensor-slot-badge">
                                        Slot {{ $slotNumber }}
                                    </span>

                                @else

                                    <span class="text-xs text-gray-400">
                                        Not supported
                                    </span>

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- ASSIGNED ITEM --}}
                            {{-- ===================================== --}}

                            <td class="px-5 py-4">

                                @if($sensor->item)

                                    <p class="font-medium text-gray-800">
                                        {{ $sensor->item->item_name }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $sensor->item->item_code }}
                                    </p>

                                @else

                                    <span class="text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- CONNECTION --}}
                            {{-- ===================================== --}}

                            <td class="px-5 py-4">

                                @if(!$isSupported)

                                    <span class="sensor-status sensor-status-legacy">
                                        <span class="sensor-status-dot"></span>
                                        Legacy
                                    </span>


                                @elseif($isOnline)

                                    <div>

                                        <span class="sensor-status sensor-status-online">
                                            <span class="sensor-status-dot"></span>
                                            Online
                                        </span>

                                        <p class="sensor-last-seen">
                                            Receiving data
                                        </p>

                                    </div>


                                @else

                                    <div>

                                        <span class="sensor-status sensor-status-offline">
                                            <span class="sensor-status-dot"></span>
                                            Offline
                                        </span>


                                        @if($lastSeen)

                                            <p
                                                class="sensor-last-seen"
                                                title="{{ $lastSeen->format('Y-m-d H:i:s') }}"
                                            >
                                                Last seen
                                                {{ $lastSeen->diffForHumans() }}
                                            </p>

                                        @else

                                            <p class="sensor-last-seen">
                                                No heartbeat received
                                            </p>

                                        @endif

                                    </div>

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- ASSIGNMENT --}}
                            {{-- ===================================== --}}

                            <td class="px-5 py-4">

                                @if(!$isSupported)

                                    <span class="text-xs text-gray-400">
                                        —
                                    </span>


                                @elseif($sensor->item_id !== null)

                                    <span class="sensor-status sensor-status-assigned">
                                        <span class="sensor-status-dot"></span>
                                        Assigned
                                    </span>


                                @else

                                    <span class="sensor-status sensor-status-available">
                                        <span class="sensor-status-dot"></span>
                                        Available
                                    </span>

                                @endif

                            </td>


                            {{-- ===================================== --}}
                            {{-- ACTION --}}
                            {{-- ===================================== --}}

                            <td class="px-5 py-4 text-right">

                                @if(auth()->user()->hasPermission('manage_sensors'))

                                    @if($sensor->item_id !== null)

                                        <span
                                            class="sensor-delete-disabled"
                                            title="Unassign this sensor from its item before deleting it."
                                        >
                                            In Use
                                        </span>

                                    @else

                                        <form
                                            method="POST"
                                            action="{{ route('sensors.destroy', $sensor) }}"
                                            class="js-confirm-form inline-block"
                                            data-confirm-title="Delete Sensor"
                                            data-confirm-message="Are you sure you want to delete {{ $sensor->sensor_code }}? This action cannot be undone."
                                            data-confirm-button="Delete"
                                        >

                                            @csrf
                                            @method('DELETE')


                                            <x-table-action-button type="submit" variant="delete">
                                                Delete
                                            </x-table-action-button>

                                        </form>

                                    @endif

                                @else

                                    <span class="text-gray-300">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        {{-- ========================================= --}}
                        {{-- EMPTY STATE --}}
                        {{-- ========================================= --}}

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center"
                            >

                                <div class="sensor-empty-icon">

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
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>

                                </div>


                                <h3 class="text-sm font-semibold text-gray-700 mt-3">

                                    {{
                                        $search !== ''
                                            ? 'No matching sensors'
                                            : 'No sensors registered'
                                    }}

                                </h3>


                                <p class="text-xs text-gray-400 mt-1">

                                    @if($search !== '')

                                        Try a different sensor code.

                                    @else

                                        Register a physical sensor slot to get started.

                                    @endif

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        <x-simple-pagination :paginator="$sensors" />

    </div>

    {{-- ============================================================= --}}
{{-- ADD SENSOR MODAL --}}
{{-- ============================================================= --}}

@if(auth()->user()->hasPermission('manage_sensors'))

<div
    id="addSensorModal"
    class="sensor-modal-overlay"
    aria-hidden="true"
>
    <div
        class="sensor-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="addSensorModalTitle"
    >

        {{-- ===================================================== --}}
        {{-- MODAL HEADER --}}
        {{-- ===================================================== --}}

        <div class="sensor-modal-header">

            <div class="flex items-center gap-3">

                <div class="sensor-modal-header-icon">

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
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>

                </div>


                <div>

                    <h2
                        id="addSensorModalTitle"
                        class="text-lg font-semibold text-white"
                    >
                        Add Sensor
                    </h2>

                    <p class="text-xs text-blue-100 mt-0.5">
                        Register a physical smart shelf sensor.
                    </p>

                </div>

            </div>


            <button
                type="button"
                onclick="closeAddSensorModal()"
                class="sensor-modal-close"
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


        {{-- ===================================================== --}}
        {{-- FORM --}}
        {{-- ===================================================== --}}

        <form
            method="POST"
            action="{{ route('sensors.store') }}"
        >

            @csrf


            <div class="sensor-modal-body">


                {{-- ================================================= --}}
                {{-- SENSOR INFORMATION --}}
                {{-- ================================================= --}}

                <div class="sensor-form-section">

                    <p class="sensor-section-title">
                        Sensor Information
                    </p>

                    <p class="sensor-section-description">
                        Select the physical sensor slot connected to the ESP32.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- SENSOR NUMBER --}}
                {{-- ================================================= --}}

                <div class="mt-5">

                    <label
                        for="sensor_number"
                        class="sensor-field-label"
                    >
                        Sensor Number
                    </label>


                    <select
                        id="sensor_number"
                        name="sensor_number"
                        required
                        class="sensor-field-input"
                    >

                        <option value="">
                            Select Sensor
                        </option>


                        @foreach($availableNumbers as $number)

                            <option
                                value="{{ $number }}"
                                @selected(
                                    old('sensor_number') == $number
                                )
                            >
                                SENSOR-{{ $number }}
                            </option>

                        @endforeach

                    </select>


                    @error('sensor_number')

                        <p class="sensor-field-error">
                            {{ $message }}
                        </p>

                    @enderror


                    <p class="sensor-field-help">
                        Only unregistered physical sensor slots are shown.
                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- GENERATED CODE PREVIEW --}}
                {{-- ================================================= --}}

                <div class="mt-6">

                    <div class="sensor-form-section">

                        <p class="sensor-section-title">
                            Sensor Identity
                        </p>

                        <p class="sensor-section-description">
                            The final sensor code is generated automatically.
                        </p>

                    </div>


                    <div class="sensor-code-preview">

                        <div>

                            <p class="sensor-preview-label">
                                Generated Sensor Code
                            </p>

                            <p
                                id="sensorCodePreview"
                                class="sensor-preview-code"
                            >
                                SENSOR-—
                            </p>

                        </div>


                        <div class="sensor-preview-icon">

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
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>

                        </div>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- HARDWARE NOTE --}}
                {{-- ================================================= --}}

                <div class="sensor-modal-note">

                    <svg
                        class="w-4 h-4 flex-shrink-0 mt-0.5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>


                    <p>
                        The current prototype supports two physical
                        sensor channels:
                        <strong>SENSOR-1</strong>
                        and
                        <strong>SENSOR-2</strong>.
                        The generated code must match the identity
                        configured on the ESP32.
                    </p>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- FOOTER --}}
            {{-- ================================================= --}}

            <div class="sensor-modal-footer">

                <button
                    type="button"
                    onclick="closeAddSensorModal()"
                    class="sensor-modal-cancel"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="sensor-modal-submit"
                >
                    Register Sensor
                </button>

            </div>

        </form>

    </div>
</div>

@endif

</div>


{{-- ============================================================= --}}
{{-- STYLES --}}
{{-- ============================================================= --}}

<style>

    /*
    |--------------------------------------------------------------------------
    | BUTTONS
    |--------------------------------------------------------------------------
    */

    .sensor-primary-btn,
    .sensor-disabled-btn {
        min-height: 2.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 0 1rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
    }


    .sensor-primary-btn {
        color: #FFFFFF;
        background: #284B63;
        border: 1px solid #284B63;
    }


    .sensor-primary-btn:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
    }


    .sensor-disabled-btn {
        color: #9CA3AF;
        background: #F3F4F6;
        border: 1px solid #E5E7EB;
        cursor: not-allowed;
    }


    /*
    |--------------------------------------------------------------------------
    | SUMMARY CARDS
    |--------------------------------------------------------------------------
    */

    .sensor-summary-card {
        min-height: 7rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
    }


    .sensor-summary-label {
        color: #6B7280;
        font-size: 0.75rem;
        font-weight: 500;
    }


    .sensor-summary-number {
        margin-top: 0.45rem;
        color: #111827;
        font-size: 1.75rem;
        line-height: 1;
        font-weight: 700;
    }


    .sensor-summary-number span {
        color: #9CA3AF;
        font-size: 0.9rem;
        font-weight: 600;
    }


    .sensor-summary-help {
        margin-top: 0.5rem;
        color: #9CA3AF;
        font-size: 0.68rem;
    }


    /*
    |--------------------------------------------------------------------------
    | ICONS
    |--------------------------------------------------------------------------
    */

    .sensor-summary-icon,
    .sensor-code-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 0.6rem;
    }


    .sensor-summary-icon {
        width: 2.5rem;
        height: 2.5rem;
    }


    .sensor-summary-primary,
    .sensor-code-icon {
        color: #284B63;
        background: #EDF3F7;
    }


    .sensor-summary-blue {
        color: #1D4ED8;
        background: #DBEAFE;
    }


    .sensor-summary-green {
        color: #15803D;
        background: #DCFCE7;
    }


    /*
    |--------------------------------------------------------------------------
    | LEGACY NOTICE
    |--------------------------------------------------------------------------
    */

    .sensor-notice {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.9rem 1rem;
        color: #92400E;
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 0.75rem;
    }


    .sensor-notice-icon {
        width: 1.75rem;
        height: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #B45309;
        background: #FEF3C7;
        border-radius: 0.45rem;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .sensor-table-card {
        overflow: hidden;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
    }


    .sensor-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #F3F4F6;
    }


    .sensor-count-badge {
        padding: 0.3rem 0.6rem;
        color: #284B63;
        background: #EDF3F7;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
    }


    .sensor-table-head {
        color: #6B7280;
        background: #F9FAFB;
        border-bottom: 1px solid #F3F4F6;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.035em;
    }


    .sensor-row {
        transition: background-color 0.15s ease;
    }


    .sensor-row:hover {
        background: #FAFAFA;
    }


    /*
    |--------------------------------------------------------------------------
    | SENSOR CODE
    |--------------------------------------------------------------------------
    */

    .sensor-code-icon {
        width: 2.1rem;
        height: 2.1rem;
    }


    /*
    |--------------------------------------------------------------------------
    | PHYSICAL SLOT
    |--------------------------------------------------------------------------
    */

    .sensor-slot-badge {
        display: inline-flex;
        padding: 0.3rem 0.55rem;
        color: #284B63;
        background: #EDF3F7;
        border-radius: 0.4rem;
        font-size: 0.72rem;
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS BADGES
    |--------------------------------------------------------------------------
    */

    .sensor-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }


    .sensor-status-dot {
        width: 0.38rem;
        height: 0.38rem;
        background: currentColor;
        border-radius: 9999px;
    }


    /*
    |--------------------------------------------------------------------------
    | CONNECTION STATUS
    |--------------------------------------------------------------------------
    */

    .sensor-status-online {
        color: #15803D;
        background: #DCFCE7;
    }


    .sensor-status-offline {
        color: #B42318;
        background: #FEE2E2;
    }


    .sensor-last-seen {
        margin-top: 0.35rem;
        color: #9CA3AF;
        font-size: 0.65rem;
        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT STATUS
    |--------------------------------------------------------------------------
    */

    .sensor-status-assigned {
        color: #1D4ED8;
        background: #DBEAFE;
    }


    .sensor-status-available {
        color: #15803D;
        background: #DCFCE7;
    }


    /*
    |--------------------------------------------------------------------------
    | LEGACY STATUS
    |--------------------------------------------------------------------------
    */

    .sensor-status-legacy {
        color: #B45309;
        background: #FEF3C7;
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */


    .sensor-delete-btn:hover {
        text-decoration: underline;
    }


    .sensor-delete-disabled {
        color: #9CA3AF;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: not-allowed;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY STATE
    |--------------------------------------------------------------------------
    */

    .sensor-empty-icon {
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
| SENSOR MODAL
|--------------------------------------------------------------------------
*/

.sensor-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 100;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(1px);
}


.sensor-modal-overlay.is-open {
    display: flex;
}


.sensor-modal {
    width: 100%;
    max-width: 35rem;
    max-height: calc(100vh - 2rem);
    overflow-y: auto;
    background: #FFFFFF;
    border-radius: 0.75rem;
    box-shadow:
        0 20px 25px -5px rgba(0, 0, 0, 0.15),
        0 8px 10px -6px rgba(0, 0, 0, 0.10);
}


/*
|--------------------------------------------------------------------------
| MODAL HEADER
|--------------------------------------------------------------------------
*/

.sensor-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 1.15rem 1.5rem;
    color: #FFFFFF;
    background: #284B63;
    border-radius: 0.75rem 0.75rem 0 0;
}


.sensor-modal-header-icon {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.12);
    border-radius: 0.5rem;
}


.sensor-modal-close {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #CBD5E1;
    border-radius: 0.375rem;
    transition:
        color 0.15s ease,
        background-color 0.15s ease;
}


.sensor-modal-close:hover {
    color: #FFFFFF;
    background: rgba(255, 255, 255, 0.1);
}


/*
|--------------------------------------------------------------------------
| MODAL BODY
|--------------------------------------------------------------------------
*/

.sensor-modal-body {
    padding: 1.5rem;
}


.sensor-form-section {
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #EEF0F2;
}


.sensor-section-title {
    color: #475569;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.055em;
}


.sensor-section-description {
    margin-top: 0.25rem;
    color: #94A3B8;
    font-size: 0.72rem;
}


.sensor-field-label {
    display: block;
    margin-bottom: 0.4rem;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 600;
}


.sensor-field-input {
    width: 100%;
    min-height: 2.7rem;
    padding: 0 0.8rem;
    color: #111827;
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
    border-radius: 0.5rem;
    font-size: 0.875rem;
}


.sensor-field-input:focus {
    border-color: #284B63;
    outline: none;
    box-shadow: 0 0 0 1px #284B63;
}


.sensor-field-help {
    margin-top: 0.45rem;
    color: #94A3B8;
    font-size: 0.7rem;
}


.sensor-field-error {
    margin-top: 0.4rem;
    color: #DC2626;
    font-size: 0.72rem;
}


/*
|--------------------------------------------------------------------------
| CODE PREVIEW
|--------------------------------------------------------------------------
*/

.sensor-code-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1rem;
    padding: 1rem;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 0.625rem;
}


.sensor-preview-label {
    color: #94A3B8;
    font-size: 0.68rem;
    font-weight: 600;
}


.sensor-preview-code {
    margin-top: 0.25rem;
    color: #284B63;
    font-family:
        ui-monospace,
        SFMono-Regular,
        Menlo,
        Monaco,
        Consolas,
        monospace;
    font-size: 1rem;
    font-weight: 700;
}


.sensor-preview-icon {
    width: 2.25rem;
    height: 2.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #284B63;
    background: #EDF3F7;
    border-radius: 0.5rem;
}


/*
|--------------------------------------------------------------------------
| HARDWARE NOTE
|--------------------------------------------------------------------------
*/

.sensor-modal-note {
    display: flex;
    gap: 0.55rem;
    margin-top: 1.25rem;
    padding: 0.8rem 0.9rem;
    color: #52616B;
    background: #F8FAFC;
    border: 1px solid #E5E7EB;
    border-radius: 0.6rem;
    font-size: 0.72rem;
    line-height: 1.5;
}


/*
|--------------------------------------------------------------------------
| MODAL FOOTER
|--------------------------------------------------------------------------
*/

.sensor-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    background: #F8FAFC;
    border-top: 1px solid #E5E7EB;
    border-radius: 0 0 0.75rem 0.75rem;
}


.sensor-modal-cancel,
.sensor-modal-submit {
    min-height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 1rem;
    border-radius: 0.5rem;
    font-size: 0.8125rem;
    font-weight: 600;
}


.sensor-modal-cancel {
    color: #4B5563;
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
}


.sensor-modal-cancel:hover {
    background: #F9FAFB;
}


.sensor-modal-submit {
    color: #FFFFFF;
    background: #284B63;
    border: 1px solid #284B63;
}


.sensor-modal-submit:hover {
    background: #1F3A4C;
    border-color: #1F3A4C;
}


/*
|--------------------------------------------------------------------------
| MOBILE
|--------------------------------------------------------------------------
*/

@media (max-width: 640px) {

    .sensor-modal-overlay {
        padding: 0.75rem;
    }

    .sensor-modal-header,
    .sensor-modal-body {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .sensor-modal-footer {
        padding-left: 1rem;
        padding-right: 1rem;
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

    .sensor-primary-btn,.sensor-modal-submit{background:#243B53!important;border-color:#243B53!important}.sensor-primary-btn:hover,.sensor-modal-submit:hover{background:#334E68!important}
    .sensor-summary-label{color:#64748B!important;font-size:.7rem!important;letter-spacing:.055em!important}.sensor-summary-number{color:#0F172A!important;font-size:2rem!important;letter-spacing:-.035em}.sensor-summary-help{color:#94A3B8!important}
    .sensor-summary-icon{width:2.8rem!important;height:2.8rem!important;border-radius:.8rem!important}.sensor-summary-primary{color:#B96B00!important;background:#FFF7E6!important;border:1px solid #FDE3AE}.sensor-summary-blue{background:#EFF6FF!important;border:1px solid #DBEAFE}.sensor-summary-green{background:#ECFDF5!important;border:1px solid #D1FAE5}
    .sensor-table-header{background:linear-gradient(180deg,#fff,#FCFDFE)!important;border-color:#E2E8F0!important}.sensor-count-badge{background:#F1F5F9!important;border:1px solid #E2E8F0!important;color:#334E68!important}
    .sensor-field-input{border-color:#CBD5E1!important;border-radius:.65rem!important}.sensor-field-input:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal =
        document.getElementById('addSensorModal');

    const sensorSelect =
        document.getElementById('sensor_number');

    const codePreview =
        document.getElementById('sensorCodePreview');


    // ============================================================
    // SENSOR CODE PREVIEW
    // ============================================================

    function updateSensorPreview() {

        if (!sensorSelect || !codePreview) {
            return;
        }

        const number = sensorSelect.value;

        codePreview.textContent =
            number
                ? 'SENSOR-' + number
                : 'SENSOR-—';
    }


    if (sensorSelect) {

        sensorSelect.addEventListener(
            'change',
            updateSensorPreview
        );

        updateSensorPreview();
    }


    // ============================================================
    // CLOSE WHEN BACKDROP IS CLICKED
    // ============================================================

    if (modal) {

        modal.addEventListener('click', function (event) {

            if (event.target === modal) {
                closeAddSensorModal();
            }

        });
    }


    // ============================================================
    // ESCAPE KEY
    // ============================================================

    document.addEventListener('keydown', function (event) {

        if (
            event.key === 'Escape'
            && modal
            && modal.classList.contains('is-open')
        ) {
            closeAddSensorModal();
        }

    });


    // ============================================================
    // REOPEN MODAL WHEN VALIDATION FAILS
    // ============================================================

    @if($errors->has('sensor_number'))

        openAddSensorModal();

    @endif

});


function openAddSensorModal() {

    const modal =
        document.getElementById('addSensorModal');

    if (!modal) {
        return;
    }

    modal.classList.add('is-open');

    modal.setAttribute(
        'aria-hidden',
        'false'
    );

    document.body.style.overflow = 'hidden';


    const select =
        document.getElementById('sensor_number');

    if (select) {

        setTimeout(function () {
            select.focus();
        }, 50);

    }
}


function closeAddSensorModal() {

    const modal =
        document.getElementById('addSensorModal');

    if (!modal) {
        return;
    }

    modal.classList.remove('is-open');

    modal.setAttribute(
        'aria-hidden',
        'true'
    );

    document.body.style.overflow = '';
}
</script>

@endsection