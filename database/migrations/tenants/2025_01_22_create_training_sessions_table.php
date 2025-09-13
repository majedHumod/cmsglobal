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
          # إنشاء جدول جلسات التدريب الخاصة

          1. الجداول الجديدة
            - `training_sessions`
              - `id` (bigint, primary key)
              - `title` (string, عنوان الجلسة)
              - `description` (text, وصف الجلسة)
              - `price` (decimal, سعر الجلسة)
              - `duration_hours` (integer, مدة الجلسة بالساعات)
              - `image` (string, صورة الجلسة)
              - `is_visible` (boolean, حالة الإظهار)
              - `sort_order` (integer, ترتيب العرض)
              - `user_id` (bigint, foreign key)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - مفتاح خارجي للمستخدم
            - فهرسة على الحقول المهمة
        */

        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الجلسة
            $table->text('description'); // وصف الجلسة
            $table->decimal('price', 10, 2)->default(0); // سعر الجلسة
            $table->integer('duration_hours')->default(1); // مدة الجلسة بالساعات
            $table->string('image')->nullable(); // صورة الجلسة
            $table->boolean('is_visible')->default(true); // حالة الإظهار
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المدرب الذي أنشأ الجلسة
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['is_visible', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};