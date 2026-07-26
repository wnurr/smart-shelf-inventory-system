@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto">

    <a href="/products" class="inline-flex items-center gap-1 text-sm text-graphite/60 hover:text-yale-blue transition-colors mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Back to products
    </a>

    <div class="bg-white rounded-xl shadow-sm border border-grey overflow-hidden">

        <!-- Header strip -->
        <div class="bg-yale-blue px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-white/10 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-white">Add Product</h1>
                    <p class="text-sm text-white/60">Register a new item and link it to a shelf sensor</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mx-6 mt-5 bg-red/10 border border-red/30 text-red text-sm rounded-lg px-4 py-3">
                <p class="font-medium mb-1">Check the fields below:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/products" class="px-6 py-6 space-y-5">
            @csrf

            <!-- Identity -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-graphite mb-1">Product name</label>
                    <input id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Basmati Rice 5kg"
                        class="w-full rounded-lg border-grey focus:border-yale-blue focus:ring-yale-blue text-sm">
                </div>
                <div>
                    <label for="product_code" class="block text-sm font-medium text-graphite mb-1">Product code</label>
                    <input id="product_code" name="product_code" value="{{ old('product_code') }}" placeholder="P001"
                        class="w-full rounded-lg border-grey focus:border-yale-blue focus:ring-yale-blue text-sm font-mono">
                </div>
            </div>

            <!-- Spec sheet: weight + threshold -->
            <div class="bg-grey/30 rounded-lg p-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="unit_weight" class="block text-sm font-medium text-graphite mb-1">Unit weight</label>
                    <div class="relative">
                        <input id="unit_weight" name="unit_weight" type="number" value="{{ old('unit_weight') }}" placeholder="100"
                            class="w-full rounded-lg border-grey focus:border-yale-blue focus:ring-yale-blue text-sm font-mono pr-10">
                        <span class="absolute inset-y-0 right-3 flex items-center text-xs text-graphite/50">g</span>
                    </div>
                    <p class="text-xs text-graphite/50 mt-1">Weight of a single item</p>
                </div>
                <div>
                    <label for="min_stock" class="block text-sm font-medium text-graphite mb-1">Minimum stock</label>
                    <input id="min_stock" name="min_stock" type="number" value="{{ old('min_stock') }}" placeholder="5"
                        class="w-full rounded-lg border-grey focus:border-yale-blue focus:ring-yale-blue text-sm font-mono">
                    <p class="text-xs text-graphite/50 mt-1">Alert threshold</p>
                </div>
            </div>

            <!-- Sensor link -->
            <div>
                <label for="sensor_id" class="block text-sm font-medium text-graphite mb-1">Sensor ID</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-teal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <input id="sensor_id" name="sensor_id" value="{{ old('sensor_id') }}" placeholder="ESP32-SHELF-01"
                        class="w-full rounded-lg border-grey focus:border-yale-blue focus:ring-yale-blue text-sm font-mono pl-9">
                </div>
                <p class="text-xs text-graphite/50 mt-1">The ESP32 / HX711 module reporting this shelf's weight</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="/products" class="text-sm font-medium text-graphite/60 hover:text-graphite px-4 py-2">Cancel</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-yale-blue hover:bg-teal text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Save product
                </button>
            </div>
        </form>
    </div>
</div>

@endsection