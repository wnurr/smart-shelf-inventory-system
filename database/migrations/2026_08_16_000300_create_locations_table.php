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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('aisle', 50);
            $table->string('bay', 50);
            $table->string('shelf', 50);
            $table->string('bin', 50);
            $table->timestamps();

            // The same physical slot should only be registered once.
            $table->unique(['aisle', 'bay', 'shelf', 'bin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};