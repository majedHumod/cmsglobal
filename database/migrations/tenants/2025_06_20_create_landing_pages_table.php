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
          # إنشاء جدول الصفحات الرئيسية

          1. الجداول الجديدة
            - `landing_pages`
              - `id` (bigint, primary key)
              - `title` (string, عنوان الصفحة)
              - `subtitle` (string, العنوان الفرعي)
              - `header_image` (string, صورة الهيدر)
              - `header_text_color` (string, لون نص الهيدر)
              - `show_join_button` (boolean, إظهار زر الانضمام)
              - `join_button_text` (string, نص زر الانضمام)
              - `join_button_url` (string, رابط زر الانضمام)
              - `join_button_color` (string, لون زر الانضمام)
              - `content` (text, محتوى الصفحة)
              - `meta_title` (string, عنوان ميتا)
              - `meta_description` (string, وصف ميتا)
              - `is_active` (boolean, حالة التفعيل)
              - `user_id` (bigint, foreign key)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - مفتاح خارجي للمستخدم
            - فهرسة على الحقول المهمة
        */

        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('header_image');
            $table->string('header_text_color', 7)->default('#ffffff');
            $table->boolean('show_join_button')->default(true);
            $table->string('join_button_text', 50)->nullable();
            $table->string('join_button_url')->nullable();
            $table->string('join_button_color', 7)->default('#3b82f6');
            $table->text('content');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // فهارس للأداء
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};