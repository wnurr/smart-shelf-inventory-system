@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4">Products</h1>

<a href="/products/create" class="bg-blue-500 text-white px-4 py-2 rounded">
    Add Product
</a>

<table class="w-full mt-4 bg-white shadow rounded">
    <tr class="bg-gray-200">
        <th class="p-2">Name</th>
        <th>Code</th>
        <th>Weight</th>
        <th>Min Stock</th>
        <th>Sensor</th>
    </tr>

    <tr>
        <td class="p-2">Sample Product</td>
        <td>P001</td>
        <td>100g</td>
        <td>5</td>
        <td>SENSOR-1</td>
    </tr>

</table>

@endsection