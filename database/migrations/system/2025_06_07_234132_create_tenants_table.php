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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();              // فريد
            $table->string('slug');
            $table->string('domain')->unique()->nullable(); // فريد + يسمح بالقيمة null
            $table->string('subdomain')->unique()->nullable(); // فريد + يسمح بالقيمة null
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('trial_ends_at')->nullable();

            // معلومات قاعدة البيانات
            $table->string('db_name')->unique(); // فريد
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
