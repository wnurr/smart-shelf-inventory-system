<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_readings', function (Blueprint $table) {
            $table->string('event_id', 120)->nullable()->unique()->after('item_id');
        });
    }

    public function down(): void
    {
        Schema::table('inventory_readings', function (Blueprint $table) {
            $table->dropUnique(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
