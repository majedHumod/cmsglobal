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
          # إنشاء جدول قصص النجاح/الشهادات

          1. الجداول الجديدة
            - `testimonials`
              - `id` (bigint, primary key)
              - `name` (string, اسم الشخص)
              - `story_content` (text, محتوى القصة)
              - `image` (string, صورة عامة)
              - `is_visible` (boolean, حالة الإظهار)
              - `sort_order` (integer, ترتيب العرض)
              - `user_id` (bigint, foreign key)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - مفتاح خارجي للمستخدم
            - فهرسة على الحقول المهمة
        */

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الشخص
            $table->text('story_content'); // محتوى القصة
            $table->string('image')->nullable(); // صورة عامة
            $table->boolean('is_visible')->default(true); // حالة الإظهار
            $table->integer('sort_order')->default(0); // ترتيب العرض
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المدير الذي أضاف القصة
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
        Schema::dropIfExists('testimonials');
    }
};