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
        Schema::table('post_translations', function (Blueprint $table) {
            // Add the new 'author' column after the 'content' column
            $table->string('author')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_translations', function (Blueprint $table) {
            $table->dropColumn('author');
        });
    }
};
