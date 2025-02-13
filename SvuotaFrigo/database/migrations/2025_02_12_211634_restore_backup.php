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
        //Percorso del file SQL
        // Percorso del file SQL
        $path = base_path('database/backup/database_backup.sql');

        if (File::exists($path)) {
            // Leggi il contenuto del file SQL
            $sql = File::get($path);

            // Esegui il comando SQL
            DB::unprepared($sql);
        } else {
            // Se il file non esiste, lancia un errore
            throw new \Exception("Il file di backup non esiste.");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
