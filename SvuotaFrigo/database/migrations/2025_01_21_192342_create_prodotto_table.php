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
        Schema::create('prodotto', function (Blueprint $table) {
            $table->id('id_prodotto');
            $table->string('nome_prodotto');
            $table->date('data_scadenza');
            $table->boolean('flag_deleted')->default(false);

            $table->unsignedBigInteger('id_categoria_durata');
            $table->foreign('id_categoria_durata')
                ->references('id')
                ->on('categoria_durata')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prodotto');
    }
};
