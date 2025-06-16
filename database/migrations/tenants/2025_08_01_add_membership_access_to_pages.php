<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        // تحديث القيم الموجودة - استخدام طريقة أكثر توافقية مع مختلف قواعد البيانات
        try {
            // للـ MySQL
            DB::statement("ALTER TABLE pages MODIFY COLUMN access_level ENUM('public', 'authenticated', 'admin', 'user', 'page_manager', 'membership') DEFAULT 'public'");
        } catch (\Exception $e) {
            // للـ SQLite وغيرها
            // إضافة عمود مؤقت
            Schema::table('pages', function (Blueprint $table) {
                $table->string('access_level_new')->default('public')->after('access_level');
            });
            
            // نقل البيانات
            DB::table('pages')->update([
                'access_level_new' => DB::raw('access_level')
            ]);
            
            // حذف العمود القديم
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('access_level');
            });
            
            // إعادة تسمية العمود الجديد
            Schema::table('pages', function (Blueprint $table) {
                $table->renameColumn('access_level_new', 'access_level');
            });
        }
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

        // إعادة الحقل إلى الحالة السابقة بطريقة متوافقة
        try {
            // للـ MySQL
            DB::statement("ALTER TABLE pages MODIFY COLUMN access_level ENUM('public', 'authenticated', 'admin', 'user', 'page_manager') DEFAULT 'public'");
        } catch (\Exception $e) {
            // للـ SQLite وغيرها - نفس الخطوات السابقة
            Schema::table('pages', function (Blueprint $table) {
                $table->string('access_level_new')->default('public')->after('access_level');
            });
            
            DB::table('pages')->update([
                'access_level_new' => DB::raw("CASE WHEN access_level = 'membership' THEN 'authenticated' ELSE access_level END")
            ]);
            
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('access_level');
            });
            
            Schema::table('pages', function (Blueprint $table) {
                $table->renameColumn('access_level_new', 'access_level');
            });
        }
    }
};