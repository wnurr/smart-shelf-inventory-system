@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto px-4 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            Edit Sensor
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Update the sensor code.
        </p>
    </div>


    <form
        method="POST"
        action="{{ route('sensors.update', $sensor) }}"
        class="bg-white border border-gray-100 shadow-sm rounded-xl p-6 space-y-5"
    >

        @csrf
        @method('PUT')


        {{-- Sensor Code --}}
        <div>

            <label
                for="sensor_code"
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Sensor Code
            </label>

            <input
                id="sensor_code"
                type="text"
                name="sensor_code"
                value="{{ old('sensor_code', $sensor->sensor_code) }}"
                placeholder="e.g. SENSOR-01"
                required
                data-uppercase
                class="w-full rounded-lg border-gray-300
                       focus:border-[#284B63]
                       focus:ring-[#284B63]
                       text-sm font-mono"
            >


            @error('sensor_code')
                <p class="text-red-600 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror


            <p class="text-xs text-gray-400 mt-2">
                This code must match the sensor code used by the ESP32.
            </p>

        </div>



        {{-- Current Assignment --}}
        <div>

            <label
                class="block text-sm font-medium text-gray-700 mb-1"
            >
                Current Assignment
            </label>


            @if($sensor->item)

                <div class="rounded-lg bg-blue-50 px-4 py-3">

                    <p class="text-sm text-blue-700 font-medium">
                        {{ $sensor->item->item_name }}
                    </p>

                    <p class="text-xs text-blue-500 mt-1">
                        {{ $sensor->item->item_code }}
                    </p>

                </div>

            @else

                <div class="rounded-lg bg-green-50 px-4 py-3">

                    <p class="text-sm text-green-700">
                        This sensor is currently available.
                    </p>

                </div>

            @endif

        </div>



        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 pt-2">

            <a
                href="{{ route('sensors.index') }}"
                class="px-4 py-2.5
                       text-sm text-gray-600
                       hover:text-gray-900"
            >
                Cancel
            </a>


            <button
                type="submit"
                class="bg-[#284B63]
                       hover:bg-[#1f3a4c]
                       text-white
                       px-4 py-2.5
                       rounded-lg
                       text-sm font-medium
                       transition-colors"
            >
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection