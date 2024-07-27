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
        /*Schema::create('solicitud_dependencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jefe_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('solicitud_unidad_id')->constrained('solicitud_unidad')->onDelete('cascade');
            $table->foreign('unidad_id')->constrained('unidad')->onDelete('cascade');
            $table->string('estado');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_dependencia');
    }
};
