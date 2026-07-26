@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4">Products</h1>

<a href="/products/create" class="bg-blue-500 text-white px-4 py-2 rounded">
    Add Product
</a>

@if (session('success'))
    <div class="mt-4 bg-green-100 text-green-800 px-4 py-2 rounded">
        {{ session('success') }}
    </div>
@endif

<table class="w-full mt-4 bg-white shadow rounded">
    <tr class="bg-gray-200">
        <th class="p-2">Name</th>
        <th>Code</th>
        <th>Weight</th>
        <th>Min Stock</th>
        <th>Sensor</th>
    </tr>

    @forelse ($products as $product)
        <tr class="border-b">
            <td class="p-2">{{ $product->name }}</td>
            <td>{{ $product->product_code }}</td>
            <td>{{ $product->unit_weight }}g</td>
            <td>{{ $product->min_stock }}</td>
            <td>{{ $product->sensor_id }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="p-4 text-center text-gray-500">No products yet. Add one to get started.</td>
        </tr>
    @endforelse

</table>

@endsection