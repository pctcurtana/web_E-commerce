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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->json('images')->nullable(); // Array of review images
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_recommended')->default(true);
            $table->unsignedInteger('helpful_count')->default(0); // Số lượng "hữu ích"
            $table->boolean('is_approved')->default(true);
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['product_id', 'is_approved']);
            $table->index(['product_id', 'rating']);
            $table->index(['user_id', 'created_at']);
            $table->index('rating');
            
            // Unique constraint: one review per user per product
            $table->unique(['product_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
