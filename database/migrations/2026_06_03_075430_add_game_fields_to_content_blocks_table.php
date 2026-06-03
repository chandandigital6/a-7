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
        Schema::table('content_blocks', function (Blueprint $table) {
             $table->string('game_api_id')->nullable()->after('key');
        $table->string('game_name')->nullable()->after('game_api_id');
        $table->string('game_slug')->nullable()->after('game_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_blocks', function (Blueprint $table) {
               $table->dropColumn(['game_api_id', 'game_name', 'game_slug']);
        });
    }
};
