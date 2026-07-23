@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-3 gap-4">

    <div class="bg-white p-5 rounded shadow">
        <h3 class="text-gray-500">Total Products</h3>
        <p class="text-2xl font-bold">10</p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <h3 class="text-gray-500">Low Stock</h3>
        <p class="text-2xl font-bold text-red-500">2</p>
    </div>

    <div class="bg-white p-5 rounded shadow">
        <h3 class="text-gray-500">Active Sensors</h3>
        <p class="text-2xl font-bold text-green-500">5</p>
    </div>

</div>

@endsection