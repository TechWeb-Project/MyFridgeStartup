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
        Schema::create('ai_metrics', function (Blueprint $table) {
            $table->id();
            $table->decimal('generation_time', 8, 2)->comment('Tempo di generazione in secondi');
            $table->decimal('success_rate', 5, 2)->comment('Tasso di successo in percentuale');
            $table->decimal('cpu_usage', 5, 2)->comment('Utilizzo CPU in percentuale');
            $table->decimal('memory_usage', 8, 2)->comment('Utilizzo memoria in MB');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_metrics');
    }
};
