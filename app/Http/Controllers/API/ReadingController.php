<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ReadingController extends Controller
{
    /**
     * ESP32 calls this repeatedly with the live shelf weight.
     * Body: { "sensor_id": "ESP32-SHELF-01", "weight": 812.4 }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sensor_id' => 'required|string',
            'weight' => 'required|numeric|min:0',
        ]);

        $product = Product::where('sensor_id', $data['sensor_id'])->first();

        if (!$product) {
            return response()->json(['message' => 'No product linked to this sensor_id'], 404);
        }

        $quantity = 0;
        if ($product->unit_weight > 0) {
            $quantity = max(0, (int) round($data['weight'] / $product->unit_weight));
        }

        $product->update([
            'current_weight' => $data['weight'],
            'current_quantity' => $quantity,
            'last_reading_at' => now(),
        ]);

        return response()->json([
            'quantity' => $quantity,
            'low_stock' => $quantity <= $product->min_stock,
        ]);
    }

    /**
     * ESP32 calls this once during setup, right after placing ONE unit
     * on an otherwise empty shelf. Saves the resulting weight as the
     * product's official unit_weight.
     * Body: { "sensor_id": "ESP32-SHELF-01", "weight": 8.4 }
     */
    public function calibrateUnit(Request $request)
    {
        $data = $request->validate([
            'sensor_id' => 'required|string',
            'weight' => 'required|numeric|min:0.01',
        ]);

        $product = Product::where('sensor_id', $data['sensor_id'])->first();

        if (!$product) {
            return response()->json(['message' => 'No product linked to this sensor_id'], 404);
        }

        $product->update([
            'unit_weight' => $data['weight'],
            'current_weight' => $data['weight'],
            'current_quantity' => 1,
            'last_reading_at' => now(),
        ]);

        return response()->json(['message' => 'Unit weight saved', 'unit_weight' => $data['weight']]);
    }
}