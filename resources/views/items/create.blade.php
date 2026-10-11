@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto px-4 py-8">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-900">
            Add Item
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Add a new item and assign an available location and sensor.
        </p>

    </div>



    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        method="POST"
        action="{{ route('items.store') }}"
        class="bg-white
               shadow-sm
               rounded-xl
               border
               border-gray-100
               p-6
               space-y-5"
    >

        @csrf



        {{-- ===================================================== --}}
        {{-- ITEM NAME + ITEM CODE --}}
        {{-- ===================================================== --}}

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Item Name --}}
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
                    value="{{ old('item_name') }}"
                    placeholder="e.g. Hex Bolt M8"
                    required
                    data-titlecase
                    autofocus
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



            {{-- Item Code --}}
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
                    value="{{ old('item_code') }}"
                    placeholder="e.g. BLT001"
                    required
                    data-uppercase
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



        {{-- ===================================================== --}}
        {{-- MINIMUM STOCK --}}
        {{-- ===================================================== --}}

        <div>

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
                value="{{ old('min_stock') }}"
                placeholder="e.g. 20"
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


            <p class="text-xs text-gray-400 mt-1">
                The system will use this value to determine when stock is low.
            </p>

        </div>



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

                <option value="">
                    Select Location
                </option>


                @foreach($locations as $location)

                    <option
                        value="{{ $location->id }}"
                        @selected(
                            old('location_id')
                            == $location->id
                        )
                    >
                        {{ $location->label() }}
                    </option>

                @endforeach

            </select>


            @error('location_id')

                <p class="text-red-600 text-xs mt-1">
                    {{ $message }}
                </p>

            @enderror



            @if($locations->isEmpty())

                <div
                    class="mt-2
                           rounded-lg
                           bg-amber-50
                           px-3
                           py-2"
                >

                    <p class="text-xs text-amber-700">
                        No available locations.
                        Please create a new location or free an occupied location first.
                    </p>

                </div>

            @else

                <p class="text-xs text-gray-400 mt-1">
                    Only available locations are shown.
                    Each location can only contain one item type.
                </p>

            @endif

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

                <span class="text-gray-400 font-normal">
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
                            old('sensor_id')
                            == $sensor->id
                        )
                    >
                        {{ $sensor->sensor_code }}
                    </option>

                @endforeach

            </select>


            @error('sensor_id')

                <p class="text-red-600 text-xs mt-1">
                    {{ $message }}
                </p>

            @enderror



            @if($sensors->isEmpty())

                <div
                    class="mt-2
                           rounded-lg
                           bg-amber-50
                           px-3
                           py-2"
                >

                    <p class="text-xs text-amber-700">
                        No available sensors.
                        You can add the item without a sensor and assign one later.
                    </p>

                </div>

            @else

                <p class="text-xs text-gray-400 mt-1">
                    Only sensors that are not assigned to another item are shown.
                </p>

            @endif

        </div>



        {{-- ===================================================== --}}
        {{-- ACTION BUTTONS --}}
        {{-- ===================================================== --}}

        <div
            class="flex
                   items-center
                   justify-end
                   gap-3
                   pt-3"
        >

            <a
                href="{{ route('items.index') }}"
                class="px-4
                       py-2.5
                       text-sm
                       font-medium
                       text-gray-500
                       hover:text-gray-800
                       transition-colors"
            >
                Cancel
            </a>


            <button
                type="submit"
                @disabled($locations->isEmpty())
                class="inline-flex
                       items-center
                       justify-center
                       gap-2
                       bg-[#284B63]
                       hover:bg-[#1f3a4c]
                       disabled:bg-gray-300
                       disabled:cursor-not-allowed
                       text-white
                       text-sm
                       font-medium
                       px-5
                       py-2.5
                       rounded-lg
                       transition-colors"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4"
                >

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4.5v15m7.5-7.5h-15"
                    />

                </svg>

                Add Item

            </button>

        </div>

    </form>

</div>

@endsection