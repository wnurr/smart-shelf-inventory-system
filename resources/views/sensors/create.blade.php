@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto px-4 py-8">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="mb-6">

        <a
            href="{{ route('sensors.index') }}"
            class="sensor-back-link"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>

            Back to Sensors
        </a>

        <h1 class="text-2xl font-bold text-gray-900">
            Add Sensor
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Register one of the physical sensor slots connected to the ESP32.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('sensors.store') }}"
        class="sensor-form-card"
    >
        @csrf

        <div class="flex items-start gap-3 mb-5">

            <div class="sensor-form-icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-gray-900">
                    Sensor Identity
                </h2>

                <p class="text-xs text-gray-500 mt-0.5">
                    The system generates a consistent code automatically.
                </p>
            </div>

        </div>


        <div>

            <label
                for="sensor_number"
                class="block text-sm font-medium text-gray-700 mb-1.5"
            >
                Sensor Number
            </label>


            <div class="sensor-number-control">

                <span class="sensor-prefix">
                    SENSOR-
                </span>

                <input
                    id="sensor_number"
                    name="sensor_number"
                    type="number"
                    min="1"
                    max="2"
                    step="1"
                    value="{{ old('sensor_number', $availableNumbers->first()) }}"
                    required
                    class="sensor-number-input"
                    aria-describedby="sensor-number-help"
                >

            </div>


            @error('sensor_number')
                <p class="text-red-600 text-xs mt-1.5">
                    {{ $message }}
                </p>
            @enderror


            <p
                id="sensor-number-help"
                class="text-xs text-gray-400 mt-2"
            >
                Available:
                @foreach($availableNumbers as $number)
                    <span class="sensor-available-code">
                        SENSOR-{{ $number }}
                    </span>
                @endforeach
            </p>

        </div>


        <div class="sensor-hardware-note">

            <svg
                class="w-4 h-4 flex-shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>

            <p>
                This prototype supports two physical load-cell channels only:
                <strong>SENSOR-1</strong> and <strong>SENSOR-2</strong>.
                The code must match the sensor identity used by the ESP32.
            </p>

        </div>


        <div class="sensor-form-actions">

            <a
                href="{{ route('sensors.index') }}"
                class="sensor-secondary-btn"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="sensor-primary-btn"
            >
                Register Sensor
            </button>

        </div>

    </form>

</div>


<style>
    .sensor-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        margin-bottom: 1rem;
        color: #6B7280;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
    }

    .sensor-back-link:hover {
        color: #284B63;
    }

    .sensor-form-card {
        padding: 1.5rem;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0.875rem;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .sensor-form-icon {
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #284B63;
        background: #EDF3F7;
        border-radius: 0.5rem;
    }

    .sensor-number-control {
        height: 2.75rem;
        display: flex;
        align-items: stretch;
        overflow: hidden;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        background: #FFFFFF;
    }

    .sensor-number-control:focus-within {
        border-color: #284B63;
        box-shadow: 0 0 0 1px #284B63;
    }

    .sensor-prefix {
        display: flex;
        align-items: center;
        padding: 0 0.8rem;
        color: #284B63;
        background: #EDF3F7;
        border-right: 1px solid #DCE5EA;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .sensor-number-input {
        min-width: 0;
        flex: 1;
        padding: 0 0.8rem;
        border: 0 !important;
        outline: none !important;
        box-shadow: none !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .sensor-available-code {
        display: inline-flex;
        margin-left: 0.25rem;
        padding: 0.15rem 0.35rem;
        color: #284B63;
        background: #EDF3F7;
        border-radius: 0.3rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.65rem;
        font-weight: 700;
    }

    .sensor-hardware-note {
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

    .sensor-form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #EEF0F2;
    }

    .sensor-primary-btn,
    .sensor-secondary-btn {
        min-height: 2.625rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
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

    .sensor-secondary-btn {
        color: #4B5563;
        background: #FFFFFF;
        border: 1px solid #D1D5DB;
    }

    .sensor-secondary-btn:hover {
        background: #F9FAFB;
    }
</style>

@endsection
