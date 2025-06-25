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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reserva'); // Relación con reservas
            $table->decimal('monto', 10, 2); // Monto del pago
            $table->dateTime('fecha_pago'); // Fecha y hora del pago
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia']); // Método de pago

            // Definir la clave foránea que relaciona con la tabla reservas
            $table->foreign('id_reserva')->references('id')->on('reservas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
