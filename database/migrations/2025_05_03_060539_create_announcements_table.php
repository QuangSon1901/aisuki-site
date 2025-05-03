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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id'); // ID ngôn ngữ
            $table->unsignedBigInteger('mass_id'); // ID nhóm các thông báo cùng nội dung nhưng khác ngôn ngữ
            $table->string('title');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_dismissible')->default(true);
            $table->integer('priority')->default(0); // Higher number = higher priority
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Tạo khóa kết hợp cho mass_id và language_id (mỗi mass_id chỉ có 1 bản ghi cho mỗi ngôn ngữ)
            $table->unique(['mass_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
