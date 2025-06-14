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
          # تحسين نظام الوصول للصفحات مع العضويات

          1. التعديلات
            - إضافة حقل `required_membership_types` (json) للصفحات
            - تحديث حقل `access_level` ليشمل خيار 'membership'

          2. الهدف
            - ربط الصفحات بأنواع العضويات المطلوبة
            - تحسين نظام التحكم بالوصول
        */

        Schema::table('pages', function (Blueprint $table) {
            // إضافة حقل لتخزين أنواع العضويات المطلوبة
            if (!Schema::hasColumn('pages', 'required_membership_types')) {
                $table->json('required_membership_types')->nullable()->after('access_roles');
            }
        });

        // تحديث القيم الموجودة
        DB::statement("ALTER TABLE pages MODIFY COLUMN access_level ENUM('public', 'authenticated', 'admin', 'user', 'page_manager', 'membership') DEFAULT 'public'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'required_membership_types')) {
                $table->dropColumn('required_membership_types');
            }
        });

        // إعادة الحقل إلى الحالة السابقة
        DB::statement("ALTER TABLE pages MODIFY COLUMN access_level ENUM('public', 'authenticated', 'admin', 'user', 'page_manager') DEFAULT 'public'");
    }
};