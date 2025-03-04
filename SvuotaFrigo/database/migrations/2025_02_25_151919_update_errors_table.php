<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the column without constraints
        // Schema::table('errors', function (Blueprint $table) {
        //     $table->bigInteger('user_id')->unsigned()->nullable()->after('id');
        // });

        // Get the first user id or create a default user
        // $defaultUserId = User::first()?->id ?? User::create(['name' => 'System', 'email' => 'system@example.com', 'password' => bcrypt('password')])->id;

        // Update existing records
        // DB::table('errors')->whereNull('user_id')->update(['user_id' => $defaultUserId]);

        // Now add the foreign key constraint
        // Schema::table('errors', function (Blueprint $table) {
        //     $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        //     // Make the column non-nullable after updating existing records
        //     $table->bigInteger('user_id')->unsigned()->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('errors', function (Blueprint $table) {
        //     $table->dropForeign(['user_id']);
        //     $table->dropColumn('user_id');
        // });
    }
};
