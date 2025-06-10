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
          # إنشاء جدول عضويات المستخدمين

          1. الجداول الجديدة
            - `user_memberships`
              - `id` (bigint, primary key)
              - `user_id` (bigint, foreign key)
              - `membership_type_id` (bigint, foreign key)
              - `starts_at` (timestamp, تاريخ بداية الاشتراك)
              - `expires_at` (timestamp, تاريخ انتهاء الاشتراك)
              - `is_active` (boolean, حالة النشاط)
              - `payment_status` (enum, حالة الدفع)
              - `payment_amount` (decimal, مبلغ الدفع)
              - `payment_reference` (string, مرجع الدفع)
              - `created_at` (timestamp)
              - `updated_at` (timestamp)

          2. الأمان
            - مفاتيح خارجية للمستخدمين وأنواع العضويات
            - فهرسة على الحقول المهمة
            - قيود على حالة الدفع
        */

        Schema::create('user_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_type_id')->constrained()->onDelete('cascade');
            $table->timestamp('starts_at'); // تاريخ بداية الاشتراك
            $table->timestamp('expires_at'); // تاريخ انتهاء الاشتراك
            $table->boolean('is_active')->default(true); // حالة النشاط
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->decimal('payment_amount', 10, 2)->default(0); // مبلغ الدفع
            $table->string('payment_reference')->nullable(); // مرجع الدفع
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();
            
            // فهارس للأداء
            $table->index(['user_id', 'is_active']);
            $table->index(['expires_at', 'is_active']);
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_memberships');
    }
};