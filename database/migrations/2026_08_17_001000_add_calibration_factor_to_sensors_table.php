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
        Schema::table('sensors', function (Blueprint $table) {
            // Last known HX711 raw-to-gram factor reported by the ESP32
            // after a "calibrate scale" command. Purely informational.
            $table->float('calibration_factor')->nullable()->after('sensor_code');
            $table->timestamp('last_calibrated_at')->nullable()->after('calibration_factor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropColumn(['calibration_factor', 'last_calibrated_at']);
        });
    }
};