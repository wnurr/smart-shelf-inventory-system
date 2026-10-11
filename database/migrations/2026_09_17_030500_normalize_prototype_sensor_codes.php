<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /*
         * Safely normalize only the two real hardware channels.
         * Legacy S3/S4/etc. records are intentionally preserved so no
         * assignment is silently deleted. They can be unassigned and
         * removed through the Sensors page.
         */
        if (
            DB::table('sensors')->where('sensor_code', 'S1')->exists()
            && ! DB::table('sensors')->where('sensor_code', 'SENSOR-1')->exists()
        ) {
            DB::table('sensors')
                ->where('sensor_code', 'S1')
                ->update(['sensor_code' => 'SENSOR-1']);
        }

        if (
            DB::table('sensors')->where('sensor_code', 'S2')->exists()
            && ! DB::table('sensors')->where('sensor_code', 'SENSOR-2')->exists()
        ) {
            DB::table('sensors')
                ->where('sensor_code', 'S2')
                ->update(['sensor_code' => 'SENSOR-2']);
        }
    }

    public function down(): void
    {
        if (
            DB::table('sensors')->where('sensor_code', 'SENSOR-1')->exists()
            && ! DB::table('sensors')->where('sensor_code', 'S1')->exists()
        ) {
            DB::table('sensors')
                ->where('sensor_code', 'SENSOR-1')
                ->update(['sensor_code' => 'S1']);
        }

        if (
            DB::table('sensors')->where('sensor_code', 'SENSOR-2')->exists()
            && ! DB::table('sensors')->where('sensor_code', 'S2')->exists()
        ) {
            DB::table('sensors')
                ->where('sensor_code', 'SENSOR-2')
                ->update(['sensor_code' => 'S2']);
        }
    }
};
