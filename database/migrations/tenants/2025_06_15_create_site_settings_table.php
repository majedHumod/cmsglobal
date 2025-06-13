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
          # إنشاء جدول إعدادات الموقع

          1. الجداول الجديدة
            - `site_settings`
              - `id` (bigint, primary key)
              - `key` (string, مفتاح الإعداد)
              - `value` (text, قيمة الإعداد)
              - `group` (string, مجموعة الإعداد)
              - `type` (string, نوع الإعداد)
              - `description` (text, وصف الإعداد)
              - `is_public` (boolean, هل الإعداد عام)
              - `is_tenant_specific` (boolean, هل الإعداد خاص بالمستأجر)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - فهرسة على الحقول المهمة
            - قيود فريدة على key
        */

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->string('type')->default('string');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_tenant_specific')->default(true);
            $table->timestamps();
            
            // فهارس للأداء
            $table->index('group');
            $table->index(['is_public', 'is_tenant_specific']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};