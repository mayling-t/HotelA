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
        Schema::create('reserva_servicios_extras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserva');
            $table->unsignedBigInteger('id_servicio');

            $table->foreign('id_reserva')->references('id')->on('reservas');
            $table->foreign('id_servicio')->references('id')->on('servicios_extras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva_servicios_extras');
    }
};
