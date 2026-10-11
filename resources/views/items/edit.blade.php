@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <a
            href="{{ route('items.show', $item) }}"
            class="back-link"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Item Details
        </a>

        <div class="flex items-start justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Edit Item
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Update item information and shelf assignment.
                </p>
            </div>

            <span class="item-code-badge">
                {{ $item->item_code }}
            </span>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('items.update', $item) }}"
        class="bg-white
               shadow-sm
               rounded-xl
               border
               border-gray-100
               p-6
               space-y-5"
    >

        @csrf

        @method('PUT')

        <div class="form-section-heading">
            <div class="form-section-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0-8 5-8-5m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5"/>
                </svg>
            </div>
            <div>
                <p class="form-section-title">Item Information</p>
                <p class="form-section-description">Basic identification details for this inventory item.</p>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- ITEM NAME + CODE --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="sm:col-span-2">

                <label
                    for="item_name"
                    class="block
                           text-sm
                           font-medium
                           text-gray-700
                           mb-1"
                >
                    Item Name
                </label>


                <input
                    id="item_name"
                    name="item_name"
                    type="text"
                    value="{{ old(
                        'item_name',
                        $item->item_name
                    ) }}"
                    required
                    data-titlecase
                    class="w-full
                           rounded-lg
                           border-gray-300
                           focus:border-[#284B63]
                           focus:ring-[#284B63]
                           text-sm"
                >


                @error('item_name')

                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>



            <div>

                <label
                    for="item_code"
                    class="block
                           text-sm
                           font-medium
                           text-gray-700
                           mb-1"
                >
                    Item Code
                </label>


                <input
                    id="item_code"
                    name="item_code"
                    type="text"
                    value="{{ old(
                        'item_code',
                        $item->item_code
                    ) }}"
                    required
                    class="w-full
                           rounded-lg
                           border-gray-300
                           focus:border-[#284B63]
                           focus:ring-[#284B63]
                           text-sm
                           font-mono"
                >


                @error('item_code')

                    <p class="text-red-600 text-xs mt-1">
                        {{ $message }}
                    </p>

                @enderror

            </div>

        </div>



        <div class="form-section-heading section-divider">
            <div class="form-section-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m-6 4h3m-7 5h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="form-section-title">Stock Setting</p>
                <p class="form-section-description">Configure the threshold used for low-stock monitoring.</p>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- MINIMUM STOCK --}}
        {{-- ===================================================== --}}

        <div class="max-w-xs">

            <label
                for="min_stock"
                class="block
                       text-sm
                       font-medium
                       text-gray-700
                       mb-1"
            >
                Minimum Stock
            </label>


            <input
                id="min_stock"
                name="min_stock"
                type="number"
                min="0"
                value="{{ old(
                    'min_stock',
                    $item->min_stock
                ) }}"
                required
                class="w-full
                       rounded-lg
                       border-gray-300
                       focus:border-[#284B63]
                       focus:ring-[#284B63]
                       text-sm"
            >


            @error('min_stock')

                <p class="text-red-600 text-xs mt-1">
                    {{ $message }}
                </p>

            @enderror

        </div>



        <div class="form-section-heading section-divider">
            <div class="form-section-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="form-section-title">Shelf Assignment</p>
                <p class="form-section-description">Choose the storage location and monitoring sensor.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- ===================================================== --}}
        {{-- LOCATION --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="location_id"
                class="block
                       text-sm
                       font-medium
                       text-gray-700
                       mb-1"
            >
                Location
            </label>


            <select
                id="location_id"
                name="location_id"
                required
                class="w-full
                       rounded-lg
                       border-gray-300
                       focus:border-[#284B63]
                       focus:ring-[#284B63]
                       text-sm"
            >

                @foreach($locations as $location)

                    <option
                        value="{{ $location->id }}"
                        @selected(
                            old(
                                'location_id',
                                $item->location_id
                            )
                            == $location->id
                        )
                    >

                        {{ $location->label() }}

                        @if(
                            $location->id
                            == $item->location_id
                        )
                            (Current)
                        @endif

                    </option>

                @endforeach

            </select>


            @error('location_id')

                <p class="text-red-600 text-xs mt-1">
                    {{ $message }}
                </p>

            @enderror


            <p class="text-xs text-gray-400 mt-1">
                Only available locations and this item's current location are shown.
            </p>

        </div>



        {{-- ===================================================== --}}
        {{-- SENSOR --}}
        {{-- ===================================================== --}}

        <div>

            <label
                for="sensor_id"
                class="block
                       text-sm
                       font-medium
                       text-gray-700
                       mb-1"
            >
                Sensor

                <span class="font-normal text-gray-400">
                    (Optional)
                </span>
            </label>


            <select
                id="sensor_id"
                name="sensor_id"
                class="w-full
                       rounded-lg
                       border-gray-300
                       focus:border-[#284B63]
                       focus:ring-[#284B63]
                       text-sm"
            >

                <option value="">
                    No Sensor
                </option>


                @foreach($sensors as $sensor)

                    <option
                        value="{{ $sensor->id }}"
                        @selected(
                            old(
                                'sensor_id',
                                $item->sensor?->id
                            )
                            == $sensor->id
                        )
                    >

                        {{ $sensor->sensor_code }}

                        @if(
                            $item->sensor
                            &&
                            $sensor->id
                            == $item->sensor->id
                        )
                            (Current)
                        @endif

                    </option>

                @endforeach

            </select>


            @error('sensor_id')

                <p class="text-red-600 text-xs mt-1">
                    {{ $message }}
                </p>

            @enderror


            <p class="text-xs text-gray-400 mt-1">
                Only available sensors and the currently assigned sensor are shown.
            </p>

        </div>



        </div>

        {{-- ===================================================== --}}
        {{-- ACTION BUTTONS --}}
        {{-- ===================================================== --}}

        <div
            class="flex
                   items-center
                   justify-end
                   gap-3
                   pt-2"
        >

            <a
                href="{{ route('items.show', $item) }}"
                class="text-sm
                       font-medium
                       text-gray-500
                       hover:text-gray-800
                       px-4
                       py-2"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="inline-flex
                       items-center
                       gap-2
                       bg-[#284B63]
                       hover:bg-[#1f3a4c]
                       text-white
                       text-sm
                       font-medium
                       px-4
                       py-2.5
                       rounded-lg
                       transition-colors"
            >
                Save Changes
            </button>

        </div>

    </form>


</div>

<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        margin-bottom: 1rem;
        color: #6B7280;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .back-link:hover {
        color: #284B63;
    }

    .item-code-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.65rem;
        border: 1px solid #DCE5EA;
        border-radius: 0.5rem;
        background: #F5F8FA;
        color: #284B63;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .edit-card {
        padding: 1.5rem;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.875rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .form-section-heading {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .section-divider {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #EEF0F2;
    }

    .form-section-icon {
        display: flex;
        width: 2rem;
        height: 2rem;
        flex: 0 0 2rem;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        background: #EDF3F7;
        color: #284B63;
    }

    .form-section-title {
        color: #1F2937;
        font-size: 0.8125rem;
        font-weight: 700;
    }

    .form-section-description {
        margin-top: 0.15rem;
        color: #9CA3AF;
        font-size: 0.75rem;
        line-height: 1.35;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #EEF0F2;
    }

    .secondary-btn,
    .primary-btn {
        display: inline-flex;
        min-height: 2.625rem;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.15s ease, border-color 0.15s ease;
    }

    .secondary-btn {
        background: #FFFFFF;
        color: #4B5563;
        border: 1px solid #D1D5DB;
    }

    .secondary-btn:hover {
        background: #F9FAFB;
        border-color: #9CA3AF;
    }

    .primary-btn {
        gap: 0.5rem;
        background: #284B63;
        color: #FFFFFF;
        border: 1px solid #284B63;
        cursor: pointer;
    }

    .primary-btn:hover {
        background: #1F3A4C;
        border-color: #1F3A4C;
    }

    @media (max-width: 640px) {
        .edit-card {
            padding: 1.25rem;
        }

        .form-actions {
            align-items: stretch;
            flex-direction: column-reverse;
        }

        .secondary-btn,
        .primary-btn {
            width: 100%;
        }
    }
</style>

@endsection