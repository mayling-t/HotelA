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
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade'); // 🔥 Esta línea es clave
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->char('dni', 8)->unique();
            $table->string('email')->unique(); 
            $table->string('celular', 15);
            $table->string('telefono', 15)->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
