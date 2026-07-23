@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4">Add Product</h1>

<form method="POST" action="/products">
    @csrf

    <input name="name" placeholder="Product Name">

    <input name="product_code" placeholder="Product Code">

    <input name="unit_weight" placeholder="Unit Weight">

    <input name="min_stock" placeholder="Min Stock">

    <input name="sensor_id" placeholder="Sensor ID">

    <button type="submit">Save</button>
</form>

@endsection