<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price_per_hour');
            $table->timestamps();
        });

        DB::table('services')->insert([
            ['name' => 'Cleaning', 'price_per_hour' => 40],
            ['name' => 'Maintenance', 'price_per_hour' => 50],
            ['name' => 'Inspections', 'price_per_hour' => 70],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
