<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categorie', function (Blueprint $table) {
            $table->id('id_categoria'); //autoincrement
            $table->string('nome_categoria');
            $table->integer('giorni_categoria');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorie');
    }
};
