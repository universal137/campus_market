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
    Schema::create('items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 关联卖家
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); // 关联分类
        $table->string('title'); // 商品标题
        $table->text('description'); // 商品描述
        $table->decimal('price', 10, 2); // 价格
        $table->string('image')->nullable(); // 图片路径
        $table->enum('status', ['on_sale', 'sold'])->default('on_sale'); // 状态
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
