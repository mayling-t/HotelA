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
        Schema::create('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Misma que usuarios
            $table->char('dni', 8)->unique();
            $table->string('celular', 15);
            $table->string('telefono', 15)->nullable();
            $table->string('direccion')->nullable();

            $table->foreign('id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
