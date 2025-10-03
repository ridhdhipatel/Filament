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
        Schema::create('quote', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->foreignId('service_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete()->cascadeOnDelete();
            $table->dateTime('booking_date');
            $table->integer('duration');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending','approved','rejected','scheduled','invoiced'])->default('pending');
            $table->decimal('price', 10, 2)->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote');
    }
};
