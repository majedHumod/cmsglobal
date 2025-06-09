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
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الوجبة
            $table->text('description')->nullable(); // وصف الوجبة
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']); // نوع الوجبة
            $table->integer('calories')->nullable(); // السعرات الحرارية
            $table->text('ingredients'); // المكونات
            $table->text('instructions')->nullable(); // طريقة التحضير
            $table->string('image')->nullable(); // صورة الوجبة
            $table->integer('prep_time')->nullable(); // وقت التحضير بالدقائق
            $table->integer('cook_time')->nullable(); // وقت الطبخ بالدقائق
            $table->integer('servings')->default(1); // عدد الحصص
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy'); // مستوى الصعوبة
            $table->boolean('is_active')->default(true); // حالة النشر
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم الذي أنشأ الوجبة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};