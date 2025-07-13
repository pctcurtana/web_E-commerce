<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class AdditionalProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->warn('Không có danh mục nào để thêm sản phẩm. Vui lòng chạy CategorySeeder trước.');
            return;
        }

        $productNamesByCategory = [
            'laptop' => [
                'Laptop ASUS VivoBook 15 X515EA',
                'Laptop Lenovo IdeaPad 3 15ITL6',
                'Laptop HP Pavilion 15-eg0xxx'
            ],
            'smartphone' => [
                'iPhone 15 Pro Max 256GB',
                'Samsung Galaxy S24 Ultra 512GB',
                'Xiaomi 14 Pro 256GB'
            ],
            'tablet' => [
                'iPad Air 5 64GB WiFi',
                'Samsung Galaxy Tab S9 128GB',
                'Xiaomi Pad 6 256GB'
            ],
            'smartphone-mobile' => [
                'OPPO Reno11 Pro 256GB',
                'Vivo X100 Pro 512GB',
                'Realme GT5 Pro 256GB'
            ],
            'audio' => [
                'Sony WH-1000XM5 Headphones',
                'AirPods Pro 2 (USB-C)',
                'JBL Flip 6 Bluetooth Speaker'
            ],
            'gaming' => [
                'PlayStation 5 Digital Edition',
                'Xbox Series X Console',
                'Nintendo Switch OLED Model'
            ],
            'smart-home' => [
                'Amazon Echo Dot (5th Gen)',
                'Google Nest Hub (2nd Gen)',
                'Philips Hue Smart Bulb Set'
            ],
            'accessories' => [
                'Logitech MX Master 3S Mouse',
                'Keychron K2 Mechanical Keyboard',
                'Anker PowerCore 20000mAh'
            ]
        ];

        $descriptions = [
            'Sản phẩm chất lượng cao với thiết kế hiện đại và tính năng vượt trội.',
            'Thiết bị công nghệ tiên tiến mang đến trải nghiệm tuyệt vời cho người dùng.',
            'Sản phẩm được thiết kế tỉ mỉ với chất lượng đảm bảo và hiệu suất cao.',
            'Công nghệ mới nhất kết hợp với thiết kế sang trọng và bền bỉ.',
            'Trải nghiệm người dùng hoàn hảo với tính năng thông minh và hiệu quả.',
        ];

        $imageIndex = 1;

        foreach ($categories as $category) {
            $categorySlug = $category->slug;
            $productNames = $productNamesByCategory[$categorySlug] ?? [
                'Sản phẩm ' . $category->name . ' cao cấp',
                'Sản phẩm ' . $category->name . ' chất lượng',
                'Sản phẩm ' . $category->name . ' mới nhất'
            ];

            foreach ($productNames as $index => $productName) {
                // Skip if product already exists
                if (Product::where('name', $productName)->exists()) {
                    continue;
                }

                $basePrice = rand(500000, 50000000); // 500k to 50M VND
                $hasSale = rand(1, 100) <= 30; // 30% chance of sale
                $salePrice = $hasSale ? $basePrice * (rand(70, 90) / 100) : null;

                Product::create([
                    'name' => $productName,
                    'description' => $descriptions[array_rand($descriptions)] . 
                                   ' ' . $productName . ' là sự lựa chọn hoàn hảo cho những ai tìm kiếm chất lượng và hiệu suất tối ưu.',
                    'short_description' => 'Sản phẩm ' . strtolower($category->name) . ' chất lượng cao với thiết kế hiện đại.',
                    'price' => $basePrice,
                    'sale_price' => $salePrice,
                    'sku' => strtoupper(substr($category->slug, 0, 3)) . '-' . str_pad($imageIndex, 4, '0', STR_PAD_LEFT),
                    'stock_quantity' => rand(10, 100),
                    'sold_count' => rand(0, 50),
                    'average_rating' => rand(35, 50) / 10, // 3.5 to 5.0
                    'review_count' => rand(5, 100),
                    'manage_stock' => true,
                    'in_stock' => true,
                    'is_active' => true,
                    'featured_image' => 'product-' . $imageIndex . '.jpg', // Will use public/images/products/
                    'images' => json_encode([
                        'product-' . ($imageIndex + 20) . '.jpg',
                        'product-' . ($imageIndex + 40) . '.jpg'
                    ]),
                    'category_id' => $category->id,
                ]);

                $imageIndex++;
                
                $this->command->info("Đã tạo sản phẩm: {$productName} cho danh mục {$category->name}");
            }
        }

        $this->command->info('Đã hoàn thành việc thêm sản phẩm cho tất cả danh mục!');
    }
}
