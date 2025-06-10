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
          # إنشاء جدول أنواع العضويات

          1. الجداول الجديدة
            - `membership_types`
              - `id` (bigint, primary key)
              - `name` (string, اسم نوع العضوية)
              - `slug` (string, معرف فريد)
              - `description` (text, وصف العضوية)
              - `price` (decimal, سعر الاشتراك)
              - `duration_days` (integer, مدة الاشتراك بالأيام)
              - `features` (json, مميزات العضوية)
              - `is_active` (boolean, حالة النشاط)
              - `is_protected` (boolean, محمي من التعديل/الحذف)
              - `sort_order` (integer, ترتيب العرض)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - فهرسة على الحقول المهمة
            - قيود فريدة على slug
            - قيم افتراضية مناسبة
        */

        Schema::create('membership_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم نوع العضوية
            $table->string('slug')->unique(); // معرف فريد للعضوية
            $table->text('description')->nullable(); // وصف العضوية
            $table->decimal('price', 10, 2)->default(0); // سعر الاشتراك
            $table->integer('duration_days')->default(30); // مدة الاشتراك بالأيام
            $table->json('features')->nullable(); // مميزات العضوية
            $table->boolean('is_active')->default(true); // حالة النشاط
            $table->boolean('is_protected')->default(false); // محمي من التعديل/الحذف
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['is_active', 'sort_order']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_types');
    }
};