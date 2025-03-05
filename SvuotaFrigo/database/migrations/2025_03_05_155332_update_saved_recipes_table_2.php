<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('saved_recipes', function (Blueprint $table) {
            if (!Schema::hasColumn('saved_recipes', 'user_id')) {
                $table->foreignId('user_id')
                      ->after('id')
                      ->constrained('users')
                      ->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('saved_recipes', 'recipe_name')) {
                $table->string('recipe_name')->after('user_id');
            }
            
            if (!Schema::hasColumn('saved_recipes', 'ingredients')) {
                $table->json('ingredients')->after('recipe_name');
            }
            
            if (!Schema::hasColumn('saved_recipes', 'instructions')) {
                $table->text('instructions')->after('ingredients');
            }
            
            if (!Schema::hasColumn('saved_recipes', 'time')) {
                $table->integer('time')->after('instructions');
            }
            
            if (!Schema::hasColumn('saved_recipes', 'num_people')) {
                $table->integer('num_people')->after('time');
            }
            
            if (!Schema::hasColumn('saved_recipes', 'status')) {
                $table->enum('status', ['accepted', 'rejected'])
                      ->default('accepted')
                      ->after('num_people');
            }
        });
    }

    public function down(): void
    {
        Schema::table('saved_recipes', function (Blueprint $table) {
            $columnsToCheck = [
                'user_id', 'recipe_name', 'ingredients', 
                'instructions', 'time', 'num_people', 'status'
            ];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('saved_recipes', $column)) {
                    if ($column === 'user_id') {
                        $table->dropForeign(['user_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};