<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categoria_durata', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')
                ->references('id_categoria')
                ->on('categorie')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_durata');
            $table->foreign('id_durata')
                ->references('id_durata'
                )->on('durata')
                ->onDelete('cascade');

            $table->integer('durata_standard');
            $table->string('immagine_standard');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_durata');
    }
};
