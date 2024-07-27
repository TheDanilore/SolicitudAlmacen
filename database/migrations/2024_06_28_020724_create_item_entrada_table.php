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
        Schema::create('item_entrada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrada_producto_id')->constrained('entrada_producto')->onDelete('cascade');
            $table->bigInteger('cantidad');
            $table->double('p_unitario');
            $table->double('igv');
            $table->double('costo_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_entrada');
    }
};
