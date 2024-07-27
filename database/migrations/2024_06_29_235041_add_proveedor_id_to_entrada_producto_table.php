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
        Schema::table('entrada_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('proveedor_id')->after('id'); // Agregar el campo proveedor_id
            $table->foreign('proveedor_id')->references('id')->on('proveedor')->onDelete('cascade'); // Configurar la clave foránea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entrada_producto', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']); // Eliminar la relación de clave foránea
            $table->dropColumn('proveedor_id'); // Eliminar el campo proveedor_id
        });
    }
};
