<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class RealProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            // Điện thoại & Phụ kiện (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name' => 'Xiaomi Redmi Note 13 Pro 128GB',
                'price' => 6990000,
                'sale_price' => 5990000,
                'description' => 'Điện thoại Xiaomi Redmi Note 13 Pro với camera 200MP, pin 5000mAh, sạc nhanh 67W',
                'featured_image' => 'product-21.jpg'
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name' => 'OPPO Reno11 F 5G 256GB',
                'price' => 8990000,
                'sale_price' => null,
                'description' => 'OPPO Reno11 F 5G thiết kế mỏng nhẹ, camera chân dung AI, hiệu năng mạnh mẽ',
                'featured_image' => 'product-22.jpg'
            ],
            [
                'category_slug' => 'dien-thoai-phu-kien',
                'name' => 'Ốp lưng iPhone 15 Pro Max silicon',
                'price' => 299000,
                'sale_price' => 199000,
                'description' => 'Ốp lưng silicon cao cấp cho iPhone 15 Pro Max, bảo vệ toàn diện, nhiều màu sắc',
                'featured_image' => 'product-23.jpg'
            ],

            // Máy tính & Laptop (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'may-tinh-laptop',
                'name' => 'ASUS TUF Gaming A15 Ryzen 7',
                'price' => 19990000,
                'sale_price' => 17990000,
                'description' => 'Laptop gaming ASUS TUF A15 với AMD Ryzen 7, RTX 4060, RAM 16GB, SSD 512GB',
                'featured_image' => 'product-24.jpg'
            ],
            [
                'category_slug' => 'may-tinh-laptop',
                'name' => 'HP Pavilion 15 Intel Core i5',
                'price' => 15990000,
                'sale_price' => null,
                'description' => 'Laptop HP Pavilion 15 với Intel Core i5 Gen 12, RAM 8GB, SSD 256GB, thiết kế hiện đại',
                'featured_image' => 'product-25.jpg'
            ],
            [
                'category_slug' => 'may-tinh-laptop',
                'name' => 'Chuột gaming Logitech G Pro X',
                'price' => 1590000,
                'sale_price' => 1290000,
                'description' => 'Chuột gaming Logitech G Pro X Superlight, cảm biến HERO 25K, trọng lượng 63g',
                'featured_image' => 'product-26.jpg'
            ],

            // Thời trang Nam (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'thoi-trang-nam',
                'name' => 'Áo thun nam basic cotton',
                'price' => 199000,
                'sale_price' => 149000,
                'description' => 'Áo thun nam cotton 100%, form regular fit, nhiều màu sắc cơ bản',
                'featured_image' => 'product-27.jpg'
            ],
            [
                'category_slug' => 'thoi-trang-nam',
                'name' => 'Giày sneaker Nike Air Force 1',
                'price' => 2990000,
                'sale_price' => null,
                'description' => 'Giày sneaker Nike Air Force 1 classic, thiết kế iconic, chất liệu da cao cấp',
                'featured_image' => 'product-28.jpg'
            ],
            [
                'category_slug' => 'thoi-trang-nam',
                'name' => 'Áo sơ mi nam công sở',
                'price' => 450000,
                'sale_price' => 350000,
                'description' => 'Áo sơ mi nam công sở vải cotton, form slim fit, phù hợp đi làm và dự tiệc',
                'featured_image' => 'product-29.jpg'
            ],

            // Thời trang Nữ (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'thoi-trang-nu',
                'name' => 'Áo blouse nữ tay bồng',
                'price' => 350000,
                'sale_price' => 250000,
                'description' => 'Áo blouse nữ tay bồng thanh lịch, chất liệu voan mềm mại, phù hợp đi làm',
                'featured_image' => 'product-30.jpg'
            ],
            [
                'category_slug' => 'thoi-trang-nu',
                'name' => 'Giày cao gót 7cm',
                'price' => 890000,
                'sale_price' => null,
                'description' => 'Giày cao gót nữ 7cm, thiết kế thanh lịch, chất liệu da mềm, phù hợp công sở',
                'featured_image' => 'product-31.jpg'
            ],
            [
                'category_slug' => 'thoi-trang-nu',
                'name' => 'Chân váy midi dáng A',
                'price' => 320000,
                'sale_price' => 280000,
                'description' => 'Chân váy midi dáng A thanh lịch, chất liệu vải thoáng mát, dễ phối đồ',
                'featured_image' => 'product-32.jpg'
            ],

            // Mẹ & Bé (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'me-be',
                'name' => 'Tã quần Pampers size M',
                'price' => 320000,
                'sale_price' => 289000,
                'description' => 'Tã quần Pampers Baby Dry size M (6-11kg), thấm hút tốt, không rò rỉ, gói 58 miếng',
                'featured_image' => 'product-33.jpg'
            ],
            [
                'category_slug' => 'me-be',
                'name' => 'Sữa bột Enfamil A+ số 1',
                'price' => 450000,
                'sale_price' => null,
                'description' => 'Sữa bột Enfamil A+ số 1 (0-6 tháng), DHA+, hỗ trợ phát triển trí não, lon 400g',
                'featured_image' => 'product-34.jpg'
            ],
            [
                'category_slug' => 'me-be',
                'name' => 'Bình sữa Pigeon PP 240ml',
                'price' => 180000,
                'sale_price' => 150000,
                'description' => 'Bình sữa Pigeon PP 240ml, núm ty silicon mềm, chống đổ ngược, BPA free',
                'featured_image' => 'product-35.jpg'
            ],

            // Nhà cửa & Đời sống (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'nha-cua-doi-song',
                'name' => 'Máy giặt Electrolux 8kg',
                'price' => 7990000,
                'sale_price' => 6990000,
                'description' => 'Máy giặt Electrolux 8kg inverter, tiết kiệm điện, giặt sạch sâu, bảo hành 2 năm',
                'featured_image' => 'product-36.jpg'
            ],
            [
                'category_slug' => 'nha-cua-doi-song',
                'name' => 'Tủ lạnh Samsung 208L',
                'price' => 5490000,
                'sale_price' => null,
                'description' => 'Tủ lạnh Samsung 208L inverter, công nghệ Digital Inverter, tiết kiệm điện',
                'featured_image' => 'product-37.jpg'
            ],
            [
                'category_slug' => 'nha-cua-doi-song',
                'name' => 'Chảo chống dính Tefal 24cm',
                'price' => 890000,
                'sale_price' => 720000,
                'description' => 'Chảo chống dính Tefal 24cm, lớp phủ Titanium, đáy từ, phù hợp mọi bếp',
                'featured_image' => 'product-38.jpg'
            ],

            // Sách (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'sach',
                'name' => 'Đắc Nhân Tâm - Dale Carnegie',
                'price' => 89000,
                'sale_price' => 75000,
                'description' => 'Sách Đắc Nhân Tâm - Dale Carnegie, nghệ thuật giao tiếp và ứng xử, bìa mềm',
                'featured_image' => 'product-39.jpg'
            ],
            [
                'category_slug' => 'sach',
                'name' => 'Nhà Giả Kim - Paulo Coelho',
                'price' => 79000,
                'sale_price' => null,
                'description' => 'Tiểu thuyết Nhà Giả Kim của Paulo Coelho, câu chuyện về hành trình tìm kiếm ước mơ',
                'featured_image' => 'product-40.jpg'
            ],
            [
                'category_slug' => 'sach',
                'name' => 'Atomic Habits - James Clear',
                'price' => 199000,
                'sale_price' => 159000,
                'description' => 'Sách Atomic Habits - Thay đổi tí hon hiệu quả bất ngờ, phương pháp xây dựng thói quen tốt',
                'featured_image' => 'product-41.jpg'
            ],

            // Thể thao & Du lịch (thêm 2-3 sản phẩm)
            [
                'category_slug' => 'the-thao-du-lich',
                'name' => 'Giày chạy bộ Adidas Ultraboost',
                'price' => 4590000,
                'sale_price' => 3990000,
                'description' => 'Giày chạy bộ Adidas Ultraboost 22, công nghệ BOOST, đệm êm ái, thoáng khí',
                'featured_image' => 'product-42.jpg'
            ],
            [
                'category_slug' => 'the-thao-du-lich',
                'name' => 'Balo du lịch The North Face 40L',
                'price' => 2990000,
                'sale_price' => null,
                'description' => 'Balo du lịch The North Face 40L, chống nước, nhiều ngăn tiện dụng, phù hợp trekking',
                'featured_image' => 'product-43.jpg'
            ],
            [
                'category_slug' => 'the-thao-du-lich',
                'name' => 'Xe đạp thể thao Giant ATX',
                'price' => 8990000,
                'sale_price' => 7990000,
                'description' => 'Xe đạp thể thao Giant ATX 27.5", khung nhôm, phuộc trước, phù hợp đường phố và dã ngoại',
                'featured_image' => 'product-44.jpg'
            ],
        ];

        $imageIndex = 21; // Bắt đầu từ product-21.jpg

        foreach ($products as $productData) {
            $category = $categories[$productData['category_slug']];
            
            if (!$category) {
                $this->command->warn("Category {$productData['category_slug']} not found");
                continue;
            }

            $product = Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'],
                'featured_image' => $productData['featured_image'],
                'images' => json_encode([
                    $productData['featured_image'],
                    'product-' . ($imageIndex + 1) . '.jpg',
                    'product-' . ($imageIndex + 2) . '.jpg'
                ]),
                'category_id' => $category->id,
                'stock_quantity' => rand(10, 100),
                'is_active' => true,
                'in_stock' => true,
                'average_rating' => round(rand(35, 50) / 10, 1), // 3.5 - 5.0
                'review_count' => rand(10, 200),
                'sold_count' => rand(5, 500),
                'sku' => 'PRD-' . strtoupper(Str::random(8)),
                'weight' => rand(100, 5000) / 1000, // Convert to kg
            ]);

            $imageIndex += 3;
            
            $this->command->info("Created product: {$product->name}");
        }

        $this->command->info('Real product seeder completed!');
    }
} 