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
        Schema::create('calibration_commands', function (Blueprint $table) {
            $table->id();

            // The item being calibrated (its sensor is what actually
            // receives the command, but we key the UI/history by item).
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

            // Denormalised so the ESP32 can poll with a single simple
            // query using only the sensor_code it already knows about
            // itself, without needing to know its own item_id.
            $table->string('sensor_code');

            // tare              -> zero the scale, no payload needed
            // calibrate_scale   -> hardware raw-to-gram factor, needs reference_weight
            // set_unit_weight   -> per-item weight/unit, needs reference_quantity
            $table->enum('type', ['tare', 'calibrate_scale', 'set_unit_weight']);

            // Inputs supplied from the web UI when the command is created.
            $table->float('reference_weight')->nullable();
            $table->unsignedInteger('reference_quantity')->nullable();

            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])
                ->default('pending');

            // Raw values the ESP32 reports back once it has executed the command.
            $table->json('result')->nullable();

            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            $table->index(['sensor_code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calibration_commands');
    }
};