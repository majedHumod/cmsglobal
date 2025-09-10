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
          # إضافة حقول التغذية للجداول الغذائية

          1. التعديلات
            - إضافة `protein` (integer, البروتين بالجرام)
            - إضافة `carbs` (integer, الكربوهيدرات بالجرام)
            - إضافة `fats` (integer, الدهون بالجرام)

          2. الهدف
            - تحسين نظام التغذية
            - إضافة معلومات غذائية مفصلة
        */

        Schema::table('meal_plans', function (Blueprint $table) {
            $table->integer('protein')->nullable()->after('calories'); // البروتين بالجرام
            $table->integer('carbs')->nullable()->after('protein'); // الكربوهيدرات بالجرام
            $table->integer('fats')->nullable()->after('carbs'); // الدهون بالجرام
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->dropColumn(['protein', 'carbs', 'fats']);
        });
    }
};