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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الصفحة
            $table->string('slug')->unique(); // الرابط المخصص
            $table->longText('content'); // محتوى الصفحة
            $table->text('excerpt')->nullable(); // مقتطف قصير
            $table->string('meta_title')->nullable(); // عنوان SEO
            $table->text('meta_description')->nullable(); // وصف SEO
            $table->string('featured_image')->nullable(); // صورة مميزة
            $table->boolean('is_published')->default(true); // حالة النشر
            $table->boolean('show_in_menu')->default(false); // إظهار في القائمة
            $table->integer('menu_order')->default(0); // ترتيب في القائمة
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المؤلف
            $table->timestamp('published_at')->nullable(); // تاريخ النشر
            $table->timestamps();
            
            $table->index(['is_published', 'published_at']);
            $table->index(['show_in_menu', 'menu_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};