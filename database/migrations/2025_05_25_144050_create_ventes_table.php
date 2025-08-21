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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->unsignedBigInteger('client_id');
            $table->integer('quantite');
            $table->float('prix_unitaire');
            $table->float('prix_total');
            $table->date('date_vente');
            $table->timestamps();

            // Clés étrangères
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};
