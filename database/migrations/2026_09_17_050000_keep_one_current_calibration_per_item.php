<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Preserve only the newest calibration for each item.
        $itemIds = DB::table('calibrations')
            ->select('item_id')
            ->distinct()
            ->pluck('item_id');

        foreach ($itemIds as $itemId) {
            $keepId = DB::table('calibrations')
                ->where('item_id', $itemId)
                ->orderByDesc('calibrated_at')
                ->orderByDesc('id')
                ->value('id');

            DB::table('calibrations')
                ->where('item_id', $itemId)
                ->where('id', '!=', $keepId)
                ->delete();
        }

        Schema::table('calibrations', function (Blueprint $table) {
            $table->unique('item_id', 'calibrations_item_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('calibrations', function (Blueprint $table) {
            $table->dropUnique('calibrations_item_id_unique');
        });
    }
};
