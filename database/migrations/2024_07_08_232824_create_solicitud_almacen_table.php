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
        /*Schema::create('solicitud_almacen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jefe_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('solicitud_dependencia_id')->constrained('solicitud_dependencia')->onDelete('cascade');
            $table->foreign('salida_producto_id')->constrained('salida_producto')->onDelete('cascade');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_almacen');
    }
};
