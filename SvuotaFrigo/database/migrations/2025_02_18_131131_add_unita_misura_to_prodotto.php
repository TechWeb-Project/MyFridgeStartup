<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('prodotto', function (Blueprint $table) {
            $table->string('unita_misura', 10)->default('pezzi')->after('quantita');
        });
    }

    public function down()
    {
        Schema::table('prodotto', function (Blueprint $table) {
            $table->dropColumn('unita_misura');
        });
    }
};
