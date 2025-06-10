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
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('access_level', ['public', 'authenticated', 'admin', 'user', 'page_manager'])->default('public')->after('show_in_menu');
            $table->boolean('is_premium')->default(false)->after('access_level');
            $table->text('access_roles')->nullable()->after('is_premium'); // JSON array of allowed roles
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['access_level', 'is_premium', 'access_roles']);
        });
    }
};