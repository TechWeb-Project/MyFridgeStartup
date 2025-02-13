<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('durata', function (Blueprint $table) {
            $table->id('id_durata');
            $table->string('nome_durata');
            $table->integer('moltiplicatore_durata');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('durata');
    }
};
