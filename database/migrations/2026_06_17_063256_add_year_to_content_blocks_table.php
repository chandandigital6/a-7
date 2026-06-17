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
             $table->integer('year')->nullable()->after('game_slug');
            $table->index(['game_slug', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_blocks', function (Blueprint $table) {
               $table->dropIndex(['game_slug', 'year']);
            $table->dropColumn('year');
        });
    }
};
