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
        Schema::table('seo_pages', function (Blueprint $table) {
                 $table->string('game_api_id')->nullable()->after('page_key');
        $table->string('game_name')->nullable()->after('game_api_id');
        $table->string('game_slug')->nullable()->after('game_name');
        $table->integer('year')->nullable()->after('game_slug');

        $table->unique(['game_slug', 'year'], 'seo_game_slug_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seo_pages', function (Blueprint $table) {
            //
        });
    }
};
