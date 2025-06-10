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
          # إضافة حقول العضوية للصفحات

          1. التعديلات
            - إضافة `required_membership_types` (json) للصفحات
            - إضافة `is_premium_content` (boolean) للصفحات

          2. الهدف
            - ربط المحتوى بأنواع العضويات المطلوبة
            - تحديد المحتوى المدفوع
        */

        Schema::table('pages', function (Blueprint $table) {
            $table->json('required_membership_types')->nullable()->after('access_roles'); // أنواع العضويات المطلوبة
            $table->boolean('is_premium_content')->default(false)->after('required_membership_types'); // محتوى مدفوع
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['required_membership_types', 'is_premium_content']);
        });
    }
};