<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_ratings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignUuid('visitor_id')->constrained('visitors')->onDelete('cascade');
            
            $table->text('comment')->nullable();
            $table->integer('rating');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_ratings');
    }
};