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
          # إضافة الأعمدة المطلوبة لجدول الصلاحيات

          1. التعديلات
            - إضافة `permission_category_id` (foreign key)
            - إضافة `description` (text)
            - إضافة `level` (enum)
            - إضافة `conditions` (json)
            - إضافة `expires_at` (timestamp)
            - إضافة `is_system` (boolean)
            - إضافة `sort_order` (integer)
            - إضافة `is_active` (boolean)

          2. الهدف
            - دعم نظام الصلاحيات المتقدم
            - إصلاح مشكلة العلاقات بين الجداول
        */

        // التحقق من وجود الجدول أولاً
        if (Schema::hasTable('permissions')) {
            // التحقق من عدم وجود الأعمدة قبل إضافتها
            Schema::table('permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('permissions', 'permission_category_id')) {
                    $table->unsignedBigInteger('permission_category_id')->nullable();
                }
                
                if (!Schema::hasColumn('permissions', 'description')) {
                    $table->text('description')->nullable();
                }
                
                if (!Schema::hasColumn('permissions', 'level')) {
                    $table->enum('level', ['basic', 'intermediate', 'advanced', 'critical'])->default('basic');
                }
                
                if (!Schema::hasColumn('permissions', 'conditions')) {
                    $table->json('conditions')->nullable();
                }
                
                if (!Schema::hasColumn('permissions', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
                
                if (!Schema::hasColumn('permissions', 'is_system')) {
                    $table->boolean('is_system')->default(false);
                }
                
                if (!Schema::hasColumn('permissions', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                }
                
                if (!Schema::hasColumn('permissions', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
                
                // إضافة الفهارس
                if (!Schema::hasColumn('permissions', 'permission_category_id')) {
                    $table->index(['permission_category_id', 'is_active'], 'perm_cat_active_idx');
                }
                
                if (!Schema::hasColumn('permissions', 'level')) {
                    $table->index(['level', 'is_active'], 'perm_level_active_idx');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            Schema::table('permissions', function (Blueprint $table) {
                // حذف الفهارس أولاً
                $table->dropIndex('perm_cat_active_idx');
                $table->dropIndex('perm_level_active_idx');
                
                // حذف الأعمدة
                $table->dropColumn([
                    'permission_category_id',
                    'description',
                    'level',
                    'conditions',
                    'expires_at',
                    'is_system',
                    'sort_order',
                    'is_active'
                ]);
            });
        }
    }
};