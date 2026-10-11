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
        Schema::create('calibrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

            // Nullable: calibration can be triggered directly by the ESP32
            // during setup, with no authenticated user attached.
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->unsignedInteger('reference_quantity'); // units placed on the shelf for this calibration
            $table->float('reference_weight'); // grams measured for reference_quantity
            $table->float('item_weight_per_unit'); // reference_weight / reference_quantity
            $table->float('calibration_factor')->default(1); // sensor correction multiplier
            $table->timestamp('calibrated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibrations');
    }
};