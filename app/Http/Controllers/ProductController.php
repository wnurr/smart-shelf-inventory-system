<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'product_code' => 'required',
        'unit_weight' => 'required',
        'min_stock' => 'required',
        'sensor_id' => 'required',
    ]);

    Product::create([
        'name' => $request->name,
        'product_code' => $request->product_code,
        'unit_weight' => $request->unit_weight,
        'min_stock' => $request->min_stock,
        'sensor_id' => $request->sensor_id,
    ]);

    return redirect('/products')->with('success', 'Product added successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
