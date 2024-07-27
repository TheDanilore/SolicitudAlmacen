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
        Schema::create('solicitud_unidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidad')->onDelete('cascade');
            $table->string('estado');
            $table->timestamps();
        });

        Schema::create('solicitud_unidad_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_unidad_id')->constrained('solicitud_unidad')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_unidad');
        Schema::dropIfExists('solicitud_unidad_producto');
    }
};
