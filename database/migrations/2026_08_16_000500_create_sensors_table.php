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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('sensor_code')->unique(); // ESP32 / HX711 ID

            // A sensor belongs directly to the item it weighs, not to a
            // location, so a reading can always be traced to a single item.
            // Nullable so a sensor can be registered before it's assigned;
            // unique so an item can never have more than one sensor.
            $table->foreignId('item_id')->nullable()->unique()
                ->constrained('items')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};