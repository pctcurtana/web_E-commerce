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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('sold_count')->default(0)->after('stock_quantity');
            $table->decimal('average_rating', 3, 2)->default(0)->after('sold_count'); // 0.00 to 5.00
            $table->unsignedInteger('review_count')->default(0)->after('average_rating');
            
            // Indexes for performance
            $table->index('sold_count'); // For sorting by popularity
            $table->index('average_rating'); // For rating filters
            $table->index(['average_rating', 'sold_count']); // For combined sorts
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['products_sold_count_index']);
            $table->dropIndex(['products_average_rating_index']);
            $table->dropIndex(['products_average_rating_sold_count_index']);
            $table->dropColumn(['sold_count', 'average_rating', 'review_count']);
        });
    }
};
