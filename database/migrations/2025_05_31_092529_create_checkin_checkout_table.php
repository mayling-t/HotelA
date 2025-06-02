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
        Schema::create('checkin_checkout', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserva');
            $table->dateTime('fecha_checkin')->nullable();
            $table->dateTime('fecha_checkout')->nullable();

            $table->foreign('id_reserva')->references('id')->on('reservas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_checkout');
    }
};
