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
        /*
          # إضافة حقل نوع العضوية للمستخدمين

          1. التعديلات
            - إضافة `membership_type_id` (foreign key) للمستخدمين
            - إضافة `membership_expires_at` (timestamp) للمستخدمين

          2. الهدف
            - ربط المستخدمين بأنواع العضويات
            - تحديد تاريخ انتهاء العضوية
        */

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'membership_type_id')) {
                $table->unsignedBigInteger('membership_type_id')->nullable()->after('profile_photo_path');
                $table->foreign('membership_type_id')->references('id')->on('membership_types')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'membership_expires_at')) {
                $table->timestamp('membership_expires_at')->nullable()->after('membership_type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'membership_type_id')) {
                $table->dropForeign(['membership_type_id']);
                $table->dropColumn('membership_type_id');
            }
            
            if (Schema::hasColumn('users', 'membership_expires_at')) {
                $table->dropColumn('membership_expires_at');
            }
        });
    }
};