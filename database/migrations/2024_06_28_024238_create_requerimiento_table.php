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
        Schema::create('requerimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estado_solicitud_id')->constrained('estado_solicitud')->onDelete('cascade');
            $table->foreignId('unidad_id')->constrained('unidad')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requerimiento');
    }
};
