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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_habitacion')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', ['confirmada', 'cancelada', 'finalizada'])->default('confirmada');

            $table->foreign('id_cliente')->references('id')->on('clientes');
            $table->foreign('id_habitacion')->references('id')->on('habitaciones');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
