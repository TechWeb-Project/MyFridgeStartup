<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('prodotto', function (Blueprint $table) {
            $table->integer('quantita')->default(1)->after('data_scadenza');
            $table->dropColumn('flag_deleted');
        });

        Schema::table('categoria_durata', function (Blueprint $table) {
            $table->dropColumn('immagine_standard');
        });
    }

    public function down() {
        Schema::table('prodotto', function (Blueprint $table) {
            $table->dropColumn('quantita');
            $table->boolean('flag_deleted')->default(0);
        });

        Schema::table('categoria_durata', function (Blueprint $table) {
            $table->string('immagine_standard', 255);
        });
    }
};
