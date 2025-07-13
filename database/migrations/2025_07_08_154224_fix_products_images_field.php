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
        // Fix existing products with null or invalid images field
        DB::table('products')
            ->whereNull('images')
            ->orWhere('images', '')
            ->orWhere('images', 'null')
            ->update(['images' => json_encode([])]);
            
        // Ensure images field is properly JSON formatted
        $products = DB::table('products')->get();
        
        foreach ($products as $product) {
            $images = $product->images;
            
            // If images is not valid JSON, set it to empty array
            if (!is_string($images) || json_decode($images) === null) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['images' => json_encode([])]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
