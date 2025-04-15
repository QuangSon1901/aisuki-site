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
        Schema::create('addon_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id'); // ID ngôn ngữ
            $table->unsignedBigInteger('mass_id'); // ID nhóm (record cùng nhóm = cùng nội dung nhưng khác ngôn ngữ)
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Tạo khóa kết hợp cho mass_id và language_id
            $table->unique(['mass_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_items');
    }
};
