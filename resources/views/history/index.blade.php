@extends('layouts.app')

@section('content')

<div
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8"
    x-data="historyCalendar(
        @js($availableDatesByItem),
        @js((string) request('item_id', '')),
        @js((string) request('date', ''))
    )"
>

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

    <div class="ui-eyebrow"><span></span>TRACK ITEM MOVEMENTS</div>
        <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">
            History
        </h1>

        <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">
            Review stock movement history by item and date.
        </p>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER CARD --}}
    {{-- ========================================================= --}}

    <div class="history-filter-card mb-5 ui-surface">

        <div class="mb-5">

            <div class="flex items-center gap-2">

                <div class="history-section-icon">

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
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 14.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-6.586L3.293 6.707A1 1 0 013 6V4z"
                        />
                    </svg>

                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-900">
                        Stock History Filter
                    </h2>

                    <p class="text-xs text-gray-500 mt-0.5">
                        Select an item and optional date to view its stock movements.
                    </p>
                </div>

            </div>

        </div>


        <form
            method="GET"
            action="{{ route('history.index') }}"
            class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] gap-3 items-start"
        >

            {{-- ITEM --}}
            <div>

                <label
                    for="item_id"
                    class="history-label"
                >
                    Item
                </label>

                <select
                    name="item_id"
                    id="item_id"
                    required
                    x-model="selectedItem"
                    @change="handleItemChange()"
                    class="history-control"
                >
                    <option value="">
                        Select an item
                    </option>

                   @foreach ($items as $item)

    <option
        value="{{ $item->id }}"
        @selected((string) request('item_id') === (string) $item->id)
    >
        {{ $item->item_name }}
        ({{ $item->item_code }})
        @if($item->sensor)
            — {{ $item->sensor->sensor_code }}
        @else
            — No Sensor
        @endif
    </option>

@endforeach
                </select>

            </div>


            {{-- CUSTOM DATE PICKER --}}
            <div
                class="relative"
                @click.outside="calendarOpen = false"
            >

                <label class="history-label">
                    Date
                    <span class="font-normal text-gray-400">
                        (Optional)
                    </span>
                </label>

                <input
                    type="hidden"
                    name="date"
                    :value="selectedDate"
                >

                <div
                    class="history-date-control"
                    :class="{ 'history-date-control-disabled': !selectedItem }"
                >

                    <button
                        type="button"
                        class="history-date-main"
                        @click="toggleCalendar()"
                        :disabled="!selectedItem"
                    >
                        <span
                            :class="selectedDate ? 'text-gray-800' : 'text-gray-400'"
                            x-text="selectedDate ? formatSelectedDate(selectedDate) : 'Select a date'"
                        ></span>
                    </button>

                    <div class="history-date-actions">

                        <button
                            x-cloak
                            x-show="selectedDate"
                            type="button"
                            class="history-date-clear"
                            title="Clear date"
                            aria-label="Clear selected date"
                            @click="clearDate()"
                        >
                            ×
                        </button>

                        <button
                            type="button"
                            class="history-calendar-trigger"
                            @click="toggleCalendar()"
                            :disabled="!selectedItem"
                            aria-label="Open calendar"
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
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </button>

                    </div>

                </div>


                {{-- CALENDAR POPOVER --}}
                <div
                    x-cloak
                    x-show="calendarOpen"
                    x-transition.origin.top
                    class="history-calendar"
                >

                    <div class="history-calendar-header">

                        <button
                            type="button"
                            class="calendar-nav"
                            @click="previousMonth()"
                            aria-label="Previous month"
                        >
                            ‹
                        </button>

                        <div class="calendar-period-selectors">

<select
    :value="currentMonth"
    @change="currentMonth = Number($event.target.value)"
    class="calendar-period-select"
    aria-label="Select month"
>
    <template x-for="(month, index) in monthNames" :key="index">
        <option
            :value="index"
            x-text="month"
            :selected="currentMonth === index"
            :disabled="isMonthOptionDisabled(index)"
        ></option>
    </template>
</select>

                            <select
                                x-model.number="currentYear"
                                @change="handleYearChange()"
                                class="calendar-period-select calendar-year-select"
                                aria-label="Select year"
                            >
                                <template x-for="year in availableYears()" :key="year">
                                    <option :value="year" x-text="year"></option>
                                </template>
                            </select>

                        </div>

                        <button
                            type="button"
                            class="calendar-nav"
                            @click="nextMonth()"
                            :disabled="isNextMonthDisabled()"
                            aria-label="Next month"
                        >
                            ›
                        </button>

                    </div>


                    <div class="calendar-grid calendar-weekdays">

                        <template x-for="day in weekDays" :key="day">
                            <div x-text="day"></div>
                        </template>

                    </div>


                    <div class="calendar-grid">

                        <template x-for="(day, index) in calendarDays()" :key="index">

                            <div>

                                <button
                                    x-show="day !== null"
                                    type="button"
                                    class="calendar-day"
                                    :class="{
                                        'calendar-day-selected': isSelectedDay(day),
                                        'calendar-day-today': isToday(day),
                                        'calendar-day-disabled': isFutureDay(day)
                                    }"
                                    :disabled="isFutureDay(day)"
                                    @click="selectDay(day)"
                                >
                                    <span x-text="day"></span>

                                    <span
                                        x-show="hasData(day)"
                                        class="calendar-data-dot"
                                        title="History available"
                                    ></span>
                                </button>

                            </div>

                        </template>

                    </div>


                    <div class="history-calendar-footer">

    <div class="history-calendar-legend">
        <span class="calendar-data-dot calendar-data-dot-static"></span>

        <span>
            History available
        </span>
    </div>

    <button
        type="button"
        class="calendar-today-btn"
        @click="goToToday()"
    >
        Today
    </button>

</div>

                </div>

                <div
                    x-show="selectedItem"
                    class="history-date-hint"
                >
                    <span class="calendar-data-dot calendar-data-dot-static"></span>
                    Dates with a dot contain recorded stock movements.
                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="flex items-center gap-2 lg:pt-[1.55rem]">

                <button
                    type="submit"
                    class="history-primary-btn"
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
                            d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"
                        />
                    </svg>

                    View History
                </button>

                @if(request('item_id') || request('date'))

                    <a
                        href="{{ route('history.index') }}"
                        class="history-secondary-btn"
                    >
                        Reset
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- HISTORY RESULTS --}}
    {{-- ========================================================= --}}

    <div data-live-region="history-results" class="history-results-card ui-surface">

        @if (!request('item_id'))

            <div class="history-empty-state">

                <div class="history-empty-icon">

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
                            d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

                <h3 class="text-sm font-semibold text-gray-700 mt-3">
                    Select an item
                </h3>

                <p class="text-xs text-gray-400 mt-1">
                    Choose an item and optional date above to view its stock history.
                </p>

            </div>


        @elseif ($histories->isEmpty())

            <div class="history-empty-state">

                <div class="history-empty-icon">

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
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                </div>

                <h3 class="text-sm font-semibold text-gray-700 mt-3">
                    No history found
                </h3>

                <p class="text-xs text-gray-400 mt-1">
                    No stock movements were found for this item
                    @if(request('date'))
                        on {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                    @endif.
                </p>

            </div>


        @else

            {{-- RESULT HEADER --}}
            <div class="history-results-header">

                <div>

                    <div class="flex items-center gap-2">

                        <h2 class="text-sm font-semibold text-gray-900">
                            History Results
                        </h2>

                        <span class="history-record-count">
                            {{ $histories->total() }}
                        </span>

                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ $histories->first()->item->item_name }}
                        <span class="text-gray-300">•</span>
                        {{ $histories->first()->item->item_code }}

                        @if(request('date'))
                            <span class="text-gray-300">•</span>
                            {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                        @endif
                    </p>

                </div>


                @if(auth()->user()->hasPermission('generate_reports'))

                    <a
                        href="{{ route('reports.history', [
                            'item_id' => request('item_id'),
                            'date' => request('date')
                        ]) }}"
                        class="history-report-btn"
                    >

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
</svg>


                    Generate Report

                    </a>

                @endif

            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>

                        <tr class="history-table-head ui-table-head">

                            <th class="px-5 py-3 text-left w-16">No.</th>
                            <th class="px-5 py-3 text-left">
                                Time
                            </th>

                            <th class="px-5 py-3 text-left">
                                Item
                            </th>

                            <th class="px-5 py-3 text-left">
                                Action
                            </th>

                            <th class="px-5 py-3 text-center">
                                Previous
                            </th>

                            <th class="px-5 py-3 text-center">
                                New
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach ($histories as $history)

                            <tr class="history-row ui-row">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" :paginator="$histories" /></td>

                                {{-- TIME --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ $history->created_at->format('h:i A') }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $history->created_at->format('d M Y') }}
                                    </p>

                                </td>


                                {{-- ITEM --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $history->item->item_name }}
                                    </p>

                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $history->item->item_code }}
                                    </p>

                                </td>


                                {{-- ACTION --}}
                                <td class="px-5 py-4 whitespace-nowrap">

                                    @if ($history->action === 'stock_in')

                                        <span class="history-action history-action-in">
                                            <span class="history-action-dot"></span>
                                            Stock In
                                        </span>

                                    @elseif ($history->action === 'stock_out')

                                        <span class="history-action history-action-out">
                                            <span class="history-action-dot"></span>
                                            Stock Out
                                        </span>

                                    @else

                                        <span class="history-action history-action-neutral">
                                            <span class="history-action-dot"></span>
                                            {{ ucfirst(str_replace('_', ' ', $history->action)) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- PREVIOUS --}}
                                <td class="px-5 py-4 text-center">

                                    <span class="history-quantity history-quantity-previous">
                                        {{ $history->previous_quantity }}
                                    </span>

                                </td>


                                {{-- NEW --}}
                                <td class="px-5 py-4 text-center">

                                    <span class="history-quantity history-quantity-new">
                                        {{ $history->new_quantity }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- REUSABLE PAGINATION --}}
            @if($histories->hasPages())
                <x-simple-pagination :paginator="$histories" />
            @endif

        @endif

    </div>

</div>


{{-- ========================================================= --}}
{{-- PAGE STYLES --}}
{{-- ========================================================= --}}

<style>
    [x-cloak] {
        display: none !important;
    }

    .history-filter-card,
    .history-results-card {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
    }

    .history-filter-card {
        padding: 1.25rem;
    }

    .history-results-card {
        overflow: visible;
    }

    .history-section-icon {
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

    .history-label {
        display: block;
        margin-bottom: 0.4rem;
        color: #4B5563;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .history-control,
    .history-date-control {
        width: 100%;
        height: 2.625rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        background: #FFFFFF;
        font-size: 0.875rem;
    }

    .history-control {
        padding-left: 0.75rem;
        padding-right: 2.25rem;
    }

    .history-control:focus {
        border-color: #284B63;
        box-shadow: 0 0 0 1px #284B63;
        outline: none;
    }

    .history-date-control {
        display: flex;
        align-items: center;
        overflow: hidden;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .history-date-control:focus-within {
        border-color: #284B63;
        box-shadow: 0 0 0 1px #284B63;
    }

    .history-date-control-disabled {
        background: #F9FAFB;
        opacity: 0.75;
    }

    .history-date-main {
        min-width: 0;
        height: 100%;
        flex: 1;
        padding: 0 0.75rem;
        text-align: left;
        outline: none;
    }

    .history-date-main:disabled {
        cursor: not-allowed;
    }

    .history-date-actions {
        height: 100%;
        display: flex;
        align-items: center;
        gap: 0.1rem;
        padding-right: 0.35rem;
    }

    .history-date-clear,
    .history-calendar-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #9CA3AF;
        border-radius: 0.375rem;
    }

    .history-date-clear {
        width: 1.5rem;
        height: 1.5rem;
        font-size: 1rem;
        line-height: 1;
    }

    .history-calendar-trigger {
        width: 2rem;
        height: 2rem;
        color: #6B7280;
    }

    .history-date-clear:hover,
    .history-calendar-trigger:hover:not(:disabled) {
        color: #284B63;
        background: #F3F4F6;
    }

    .history-calendar-trigger:disabled {
        cursor: not-allowed;
    }

    .history-date-hint {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        min-height: 1rem;
        margin-top: 0.45rem;
        color: #9CA3AF;
        font-size: 0.68rem;
    }

    .history-primary-btn,
    .history-secondary-btn,
    .history-report-btn {
        min-height: 2.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        padding: 0 1rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
        white-space: nowrap;
        text-decoration: none;
    }

    .history-primary-btn,
    .history-report-btn {
        color: #FFFFFF;
        background: #284B63;
        border: 1px solid #284B63;
    }

    .history-primary-btn:hover,
    .history-report-btn:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
    }

    .history-secondary-btn {
        color: #4B5563;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
    }

    .history-secondary-btn:hover {
        background: #F9FAFB;
    }


    .history-calendar {
        position: absolute;
        z-index: 50;
        top: calc(100% + 0.5rem);
        left: 0;
        width: 19rem;
        padding: 0.85rem;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
    }

    .history-calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .calendar-period-selectors {
        min-width: 0;
        flex: 1;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 5.25rem;
        gap: 0.4rem;
        margin: 0 0.45rem;
    }

    .calendar-period-select {
        width: 100%;
        height: 2rem;
        padding: 0 1.7rem 0 0.55rem;
        color: #374151;
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.45rem;
        font-size: 0.72rem;
        font-weight: 600;
        outline: none;
        cursor: pointer;
    }

    .calendar-period-select:hover {
        border-color: #CBD5E1;
        background-color: #F8FAFC;
    }

    .calendar-period-select:focus {
        border-color: #284B63;
        box-shadow: 0 0 0 1px #284B63;
    }

    .calendar-year-select {
        font-variant-numeric: tabular-nums;
    }

    .calendar-nav {
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #4B5563;
        border: 1px solid #E5E7EB;
        border-radius: 0.45rem;
        background: #FFFFFF;
        font-size: 1.25rem;
        line-height: 1;
    }

    .calendar-nav:hover:not(:disabled) {
        color: #284B63;
        background: #F8FAFC;
    }

    .calendar-nav:disabled {
        color: #D1D5DB;
        cursor: not-allowed;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.2rem;
    }

    .calendar-weekdays {
        margin-bottom: 0.25rem;
        color: #9CA3AF;
        font-size: 0.65rem;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
    }

    .calendar-day {
        position: relative;
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4B5563;
        border-radius: 0.45rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .calendar-day:hover:not(:disabled) {
        color: #284B63;
        background: #EDF3F7;
    }

    .calendar-day-today {
        font-weight: 800;
        box-shadow: inset 0 0 0 1px #D7E1E7;
    }

    .calendar-day-selected {
        color: #FFFFFF !important;
        background: #284B63 !important;
    }

    .calendar-day-disabled {
        color: #D1D5DB;
        cursor: not-allowed;
    }

    .calendar-data-dot {
        width: 0.3rem;
        height: 0.3rem;
        display: block;
        background: #284B63;
        border-radius: 9999px;
    }

    .calendar-day .calendar-data-dot {
        position: absolute;
        left: 50%;
        bottom: 0.2rem;
        transform: translateX(-50%);
    }

    .calendar-day-selected .calendar-data-dot {
        background: #FFFFFF;
    }

    .calendar-data-dot-static {
        position: static !important;
        flex-shrink: 0;
        transform: none !important;
    }

    .history-calendar-legend {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-top: 0.65rem;
        padding-top: 0.65rem;
        color: #9CA3AF;
        border-top: 1px solid #F3F4F6;
        font-size: 0.65rem;
    }

    .history-results-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #F3F4F6;
    }

    .history-record-count {
        min-width: 1.4rem;
        height: 1.4rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 0.4rem;
        color: #284B63;
        background: #EDF3F7;
        border-radius: 9999px;
        font-size: 0.65rem;
        font-weight: 700;
    }

    .history-table-head {
        color: #6B7280;
        background: #F9FAFB;
        border-bottom: 1px solid #F3F4F6;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.035em;
    }

    .history-row {
        transition: background-color 0.15s ease;
    }

    .history-row:hover {
        background: #FAFAFA;
    }

    .history-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.6rem;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .history-action-dot {
        width: 0.38rem;
        height: 0.38rem;
        background: currentColor;
        border-radius: 9999px;
    }

    .history-action-in {
        color: #15803D;
        background: #DCFCE7;
    }

    .history-action-out {
        color: #B91C1C;
        background: #FEE2E2;
    }

    .history-action-neutral {
        color: #6B7280;
        background: #F3F4F6;
    }

    .history-quantity {
        min-width: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0.5rem;
        border-radius: 0.4rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .history-quantity-previous {
        color: #6B7280;
        background: #F3F4F6;
    }

    .history-quantity-new {
        color: #284B63;
        background: #EDF3F7;
    }

    .history-empty-state {
        padding: 4rem 1.5rem;
        text-align: center;
    }

    .history-empty-icon {
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

    .history-calendar-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: 0.65rem;
    padding-top: 0.65rem;
    border-top: 1px solid #F3F4F6;
}

.history-calendar-footer .history-calendar-legend {
    margin-top: 0;
    padding-top: 0;
    border-top: 0;
    justify-content: flex-start;
}

.calendar-today-btn {
    flex-shrink: 0;
    padding: 0.3rem 0.6rem;
    color: #284B63;
    background: #EDF3F7;
    border-radius: 0.4rem;
    font-size: 0.68rem;
    font-weight: 700;
    transition: 0.15s ease;
}

.calendar-today-btn:hover {
    color: #FFFFFF;
    background: #284B63;
}

    @media (max-width: 640px) {
        .history-results-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .history-report-btn {
            width: 100%;
        }

        .history-calendar {
            width: min(19rem, calc(100vw - 3rem));
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

    .history-filter-card,.history-results-card{border-color:#E2E8F0!important;border-radius:1rem!important;box-shadow:0 3px 12px rgba(15,23,42,.04)!important}.history-section-icon{background:#FFF7E6!important;color:#B96B00!important;border:1px solid #FDE3AE!important}
    .history-control,.history-date-control,.calendar-period-select{border-color:#CBD5E1!important;border-radius:.65rem!important}.history-control:focus,.calendar-period-select:focus{border-color:#F59E0B!important;box-shadow:0 0 0 3px rgba(245,158,11,.12)!important}
    .history-primary-btn,.history-report-btn{background:#243B53!important;border-color:#243B53!important}.history-primary-btn:hover,.history-report-btn:hover{background:#334E68!important}.history-calendar{border-color:#E2E8F0!important;border-radius:.9rem!important;box-shadow:0 18px 45px rgba(15,23,42,.14)!important}

</style>


{{-- ========================================================= --}}
{{-- CUSTOM CALENDAR --}}
{{-- ========================================================= --}}

<script>
    function historyCalendar(
        availableDatesByItem,
        initialItem,
        initialDate
    ) {
        const today = new Date();

        let initialMonth = initialDate
            ? new Date(initialDate + 'T00:00:00')
            : today;

        return {
            availableDatesByItem,
            selectedItem: initialItem,
            selectedDate: initialDate,
            calendarOpen: false,

            currentYear: initialMonth.getFullYear(),
            currentMonth: initialMonth.getMonth(),

            weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            monthNames: [
                'January', 'February', 'March', 'April',
                'May', 'June', 'July', 'August',
                'September', 'October', 'November', 'December'
            ],

            handleItemChange() {
                this.selectedDate = '';
                this.calendarOpen = false;

                const dates = this.availableDates();

                if (dates.length > 0) {
                    const latest = new Date(
                        dates[dates.length - 1] + 'T00:00:00'
                    );

                    this.currentYear = latest.getFullYear();
                    this.currentMonth = latest.getMonth();
                } else {
                    const now = new Date();

                    this.currentYear = now.getFullYear();
                    this.currentMonth = now.getMonth();
                }
            },

            toggleCalendar() {
                if (!this.selectedItem) {
                    return;
                }

                if (!this.calendarOpen) {
                    this.focusUsefulMonth();
                }

                this.calendarOpen = !this.calendarOpen;
            },

            focusUsefulMonth() {
                if (this.selectedDate) {
                    const date = new Date(
                        this.selectedDate + 'T00:00:00'
                    );

                    this.currentYear = date.getFullYear();
                    this.currentMonth = date.getMonth();

                    return;
                }

                const dates = this.availableDates();

                if (dates.length > 0) {
                    const latest = new Date(
                        dates[dates.length - 1] + 'T00:00:00'
                    );

                    this.currentYear = latest.getFullYear();
                    this.currentMonth = latest.getMonth();
                }
            },

            availableDates() {
                return this.availableDatesByItem[this.selectedItem] ?? [];
            },

            availableYears() {
                const years = this.availableDates()
                    .map(date => Number(date.slice(0, 4)))
                    .filter(year => Number.isInteger(year));

                const now = new Date();
                years.push(now.getFullYear());

                if (this.selectedDate) {
                    years.push(Number(this.selectedDate.slice(0, 4)));
                }

                return [...new Set(years)]
                    .sort((a, b) => b - a);
            },

            handleYearChange() {
                const now = new Date();

                // If the user jumps to the current year while viewing
                // a future month, move back to the current month.
                if (
                    this.currentYear === now.getFullYear() &&
                    this.currentMonth > now.getMonth()
                ) {
                    this.currentMonth = now.getMonth();
                }
            },

            isMonthOptionDisabled(monthIndex) {
                const now = new Date();

                return (
                    this.currentYear === now.getFullYear() &&
                    monthIndex > now.getMonth()
                );
            },

            monthLabel() {
                return new Intl.DateTimeFormat('en-MY', {
                    month: 'long',
                    year: 'numeric'
                }).format(
                    new Date(this.currentYear, this.currentMonth, 1)
                );
            },

            calendarDays() {
                const firstDay = new Date(
                    this.currentYear,
                    this.currentMonth,
                    1
                ).getDay();

                const daysInMonth = new Date(
                    this.currentYear,
                    this.currentMonth + 1,
                    0
                ).getDate();

                const days = Array(firstDay).fill(null);

                for (let day = 1; day <= daysInMonth; day++) {
                    days.push(day);
                }

                while (days.length % 7 !== 0) {
                    days.push(null);
                }

                return days;
            },

            dateKey(day) {
                const month = String(this.currentMonth + 1).padStart(2, '0');
                const date = String(day).padStart(2, '0');

                return `${this.currentYear}-${month}-${date}`;
            },

            hasData(day) {
                if (!day) {
                    return false;
                }

                return this.availableDates().includes(
                    this.dateKey(day)
                );
            },

            isSelectedDay(day) {
                return day && this.selectedDate === this.dateKey(day);
            },

            isToday(day) {
                if (!day) {
                    return false;
                }

                const now = new Date();

                return (
                    day === now.getDate() &&
                    this.currentMonth === now.getMonth() &&
                    this.currentYear === now.getFullYear()
                );
            },

            isFutureDay(day) {
    if (!day) {
        return true;
    }

    const date = new Date(
        this.currentYear,
        this.currentMonth,
        day
    );

    const today = new Date();

    // Compare DATE only, not current time
    date.setHours(0, 0, 0, 0);
    today.setHours(0, 0, 0, 0);

    return date > today;
},

            selectDay(day) {
                if (!day || this.isFutureDay(day)) {
                    return;
                }

                this.selectedDate = this.dateKey(day);
                this.calendarOpen = false;
            },

            clearDate() {
                this.selectedDate = '';
            },

            previousMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
            },

            nextMonth() {
                if (this.isNextMonthDisabled()) {
                    return;
                }

                if (this.currentMonth === 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
            },

            isNextMonthDisabled() {
                const now = new Date();

                return (
                    this.currentYear > now.getFullYear() ||
                    (
                        this.currentYear === now.getFullYear() &&
                        this.currentMonth >= now.getMonth()
                    )
                );
            },

goToToday() {
    const today = new Date();

    this.currentYear = today.getFullYear();
    this.currentMonth = today.getMonth();

    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');

    this.selectedDate = `${today.getFullYear()}-${month}-${day}`;
},

            formatSelectedDate(dateString) {
                const date = new Date(dateString + 'T00:00:00');

                return new Intl.DateTimeFormat('en-GB', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).format(date);
            }
        };
    }
</script>

@endsection
