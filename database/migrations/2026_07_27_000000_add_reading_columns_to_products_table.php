<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->float('current_weight')->default(0)->after('sensor_id');
            $table->unsignedInteger('current_quantity')->default(0)->after('current_weight');
            $table->timestamp('last_reading_at')->nullable()->after('current_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['current_weight', 'current_quantity', 'last_reading_at']);
        });
    }
};