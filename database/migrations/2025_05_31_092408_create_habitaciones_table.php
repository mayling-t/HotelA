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
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 10)->unique();
            $table->enum('tipo', ['simple', 'doble', 'suite']);
            $table->decimal('precio', 10, 2);
            $table->integer('capacidad');
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->enum('estado', ['disponible', 'ocupada', 'mantenimiento', 'inactiva'])->default('disponible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};
