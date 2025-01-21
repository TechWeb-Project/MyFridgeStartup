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
        Schema::create('frigo', function (Blueprint $table) {
            $table->id('id_frigo');

            $table->unsignedBigInteger('id_prodotto');
            $table->foreign('id_prodotto')
                ->references('id_prodotto')
                ->on('prodotto')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frigo');
    }
};
