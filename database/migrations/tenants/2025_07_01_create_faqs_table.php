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
          # إنشاء جدول الأسئلة الشائعة

          1. الجداول الجديدة
            - `faqs`
              - `id` (bigint, primary key)
              - `question` (string, السؤال)
              - `answer` (text, الإجابة)
              - `category` (string, تصنيف السؤال)
              - `is_active` (boolean, حالة التفعيل)
              - `sort_order` (integer, ترتيب العرض)
              - `user_id` (bigint, foreign key)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - مفتاح خارجي للمستخدم
            - فهرسة على الحقول المهمة
        */

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('عام');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['is_active', 'sort_order']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};