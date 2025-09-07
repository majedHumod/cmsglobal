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
          # إنشاء جدول التمارين الرياضية

          1. الجداول الجديدة
            - `workouts`
              - `id` (bigint, primary key)
              - `name` (string, اسم التمرين)
              - `description` (text, وصف التمرين)
              - `duration` (integer, مدة التمرين بالدقائق)
              - `difficulty` (enum, سهل/متوسط/صعب)
              - `video_url` (string, رابط فيديو التمرين)
              - `status` (boolean, نشط/غير نشط)
              - `user_id` (bigint, foreign key)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - مفتاح خارجي للمستخدم
            - فهرسة على الحقول المهمة
        */

        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم التمرين
            $table->text('description'); // وصف التمرين
            $table->integer('duration'); // مدة التمرين بالدقائق
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy'); // مستوى الصعوبة
            $table->string('video_url')->nullable(); // رابط فيديو التمرين
            $table->boolean('status')->default(true); // نشط/غير نشط
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المدرب الذي أنشأ التمرين
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['status', 'difficulty']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};