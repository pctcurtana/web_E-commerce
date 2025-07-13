<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if index exists before dropping
        $indexes = DB::select("SHOW INDEX FROM reviews WHERE Key_name = 'reviews_helpful_count_index'");
        
        if (!empty($indexes)) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropIndex(['helpful_count']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back index if needed
        if (Schema::hasColumn('reviews', 'helpful_count')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index('helpful_count');
            });
        }
    }
}; 