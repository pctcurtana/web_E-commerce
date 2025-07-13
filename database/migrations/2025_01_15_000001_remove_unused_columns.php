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
        // Remove unused columns from categories table
        Schema::table('categories', function (Blueprint $table) {
            // Check if column exists before dropping
            if (Schema::hasColumn('categories', 'image')) {
                $table->dropColumn('image'); // Hoàn toàn không được sử dụng
            }
        });

        // Remove unused columns from products table
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'dimensions')) {
                $table->dropColumn('dimensions'); // Chỉ có fake data, không có logic business
            }
        });

        // Remove unused columns from reviews table
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'images')) {
                $table->dropColumn('images'); // Không được sử dụng trong UI
            }
            if (Schema::hasColumn('reviews', 'helpful_count')) {
                $table->dropColumn('helpful_count'); // Không được sử dụng trong UI
            }
            if (Schema::hasColumn('reviews', 'purchased_at')) {
                $table->dropColumn('purchased_at'); // Không được sử dụng trong UI
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: Add back removed columns
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable();
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable();
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'images')) {
                $table->json('images')->nullable();
            }
            if (!Schema::hasColumn('reviews', 'helpful_count')) {
                $table->unsignedInteger('helpful_count')->default(0);
            }
            if (!Schema::hasColumn('reviews', 'purchased_at')) {
                $table->timestamp('purchased_at')->nullable();
            }
        });
    }
}; 