<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->float('weight'); // raw grams reported by the sensor
            $table->unsignedInteger('quantity'); // weight converted to a unit count
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock']);
            $table->timestamp('recorded_at')->useCurrent();
        });

        Schema::table('inventory_readings', function (Blueprint $table) {
            $table->index(['item_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_readings');
    }
};

