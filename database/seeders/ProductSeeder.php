<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy các categories
        $phoneCategory = Category::where('name', 'Điện thoại & Phụ kiện')->first();
        $laptopCategory = Category::where('name', 'Máy tính & Laptop')->first();
        $menFashionCategory = Category::where('name', 'Thời trang Nam')->first();
        $womenFashionCategory = Category::where('name', 'Thời trang Nữ')->first();
        $babyCategory = Category::where('name', 'Mẹ & Bé')->first();
        $homeCategory = Category::where('name', 'Nhà cửa & Đời sống')->first();

        $products = [
            // Điện thoại & Phụ kiện
            [
                'name' => 'iPhone 15 Pro Max 256GB',
                'description' => 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera 48MP Pro, khung titan cứng cáp',
                'short_description' => 'iPhone 15 Pro Max mới nhất với nhiều tính năng đột phá',
                'price' => 34990000,
                'sale_price' => 32990000,
                'sku' => 'IP15PM256',
                'stock_quantity' => 50,
                'category_id' => $phoneCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&h=800&fit=crop&crop=center',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&h=600&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1695654389269-7c51d7b6c7b8?w=600&h=600&fit=crop&crop=center',
                    'https://images.unsplash.com/photo-1512499617640-c74ae3a79d37?w=600&h=600&fit=crop&crop=center'
                ]),
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 512GB',
                'description' => 'Samsung Galaxy S24 Ultra với bút S Pen, camera 200MP, màn hình Dynamic AMOLED 2X',
                'short_description' => 'Flagship Android mạnh mẽ nhất của Samsung',
                'price' => 31990000,
                'sale_price' => 29990000,
                'sku' => 'SGS24U512',
                'stock_quantity' => 30,
                'category_id' => $phoneCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Tai nghe AirPods Pro 2',
                'description' => 'AirPods Pro thế hệ 2 với chip H2, khử tiếng ồn chủ động, âm thanh Spatial Audio',
                'short_description' => 'Tai nghe không dây cao cấp từ Apple',
                'price' => 6990000,
                'sku' => 'APP2',
                'stock_quantity' => 100,
                'category_id' => $phoneCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?w=800&h=800&fit=crop&crop=center',
            ],

            // Máy tính & Laptop
            [
                'name' => 'MacBook Air M3 13 inch 256GB',
                'description' => 'MacBook Air với chip M3 mạnh mẽ, màn hình Liquid Retina 13.6 inch, pin 18 giờ',
                'short_description' => 'Laptop siêu mỏng nhẹ từ Apple',
                'price' => 32990000,
                'sale_price' => 30990000,
                'sku' => 'MBAM3256',
                'stock_quantity' => 25,
                'category_id' => $laptopCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Dell XPS 13 Plus',
                'description' => 'Dell XPS 13 Plus với Intel Core i7, RAM 16GB, SSD 512GB, màn hình 4K OLED',
                'short_description' => 'Laptop Windows cao cấp từ Dell',
                'price' => 45990000,
                'sku' => 'DXPS13P',
                'stock_quantity' => 15,
                'category_id' => $laptopCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Gaming Chair DXRacer',
                'description' => 'Ghế gaming DXRacer với thiết kế ergonomic, đệm memory foam, điều chỉnh độ cao',
                'short_description' => 'Ghế gaming chuyên nghiệp',
                'price' => 7990000,
                'sale_price' => 6990000,
                'sku' => 'DXRC01',
                'stock_quantity' => 20,
                'category_id' => $laptopCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=800&fit=crop&crop=center',
            ],

            // Thời trang Nam
            [
                'name' => 'Áo polo nam cao cấp',
                'description' => 'Áo polo nam chất liệu cotton 100%, form slimfit, nhiều màu lựa chọn',
                'short_description' => 'Áo polo nam phong cách lịch lãm',
                'price' => 299000,
                'sale_price' => 199000,
                'sku' => 'POLO01',
                'stock_quantity' => 200,
                'category_id' => $menFashionCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Quần jeans nam Levi\'s 511',
                'description' => 'Quần jeans Levi\'s 511 slimfit, chất liệu denim cao cấp, bền đẹp theo thời gian',
                'short_description' => 'Quần jeans nam thương hiệu nổi tiếng',
                'price' => 1290000,
                'sku' => 'JEANS511',
                'stock_quantity' => 150,
                'category_id' => $menFashionCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&h=800&fit=crop&crop=center',
            ],

            // Thời trang Nữ
            [
                'name' => 'Váy maxi hoa nhí',
                'description' => 'Váy maxi họa tiết hoa nhí, chất liệu voan mềm mại, phù hợp dạo phố và đi làm',
                'short_description' => 'Váy maxi nữ tính và thanh lịch',
                'price' => 450000,
                'sale_price' => 350000,
                'sku' => 'VAXY01',
                'stock_quantity' => 80,
                'category_id' => $womenFashionCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Túi xách nữ da thật',
                'description' => 'Túi xách nữ da bò thật 100%, thiết kế sang trọng, nhiều ngăn tiện dụng',
                'short_description' => 'Túi xách nữ cao cấp',
                'price' => 890000,
                'sku' => 'TUI01',
                'stock_quantity' => 60,
                'category_id' => $womenFashionCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800&h=800&fit=crop&crop=center',
            ],

            // Mẹ & Bé
            [
                'name' => 'Xe đẩy em bé 3 trong 1',
                'description' => 'Xe đẩy em bé đa năng 3 trong 1: xe đẩy, ghế ngồi ô tô, nôi em bé',
                'short_description' => 'Xe đẩy đa năng cho bé',
                'price' => 3990000,
                'sale_price' => 3490000,
                'sku' => 'XEDAYBB01',
                'stock_quantity' => 40,
                'category_id' => $babyCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1544905830-ad4500e4728e?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Bộ đồ chơi xếp hình',
                'description' => 'Bộ đồ chơi xếp hình 100 chi tiết, phát triển trí thông minh cho bé từ 3 tuổi',
                'short_description' => 'Đồ chơi giáo dục cho bé',
                'price' => 299000,
                'sku' => 'XEPHINHBB',
                'stock_quantity' => 120,
                'category_id' => $babyCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=800&fit=crop&crop=center',
            ],

            // Nhà cửa & Đời sống
            [
                'name' => 'Nồi cơm điện Philips 1.8L',
                'description' => 'Nồi cơm điện Philips dung tích 1.8L, công nghệ 3D, lòng nồi chống dính',
                'short_description' => 'Nồi cơm điện chất lượng cao',
                'price' => 1590000,
                'sale_price' => 1290000,
                'sku' => 'NOICOM18',
                'stock_quantity' => 75,
                'category_id' => $homeCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=800&fit=crop&crop=center',
            ],
            [
                'name' => 'Bộ dao kéo nhà bếp 7 món',
                'description' => 'Bộ dao kéo nhà bếp 7 món bằng thép không gỉ, có giá đỡ gỗ sang trọng',
                'short_description' => 'Bộ dụng cụ nhà bếp cao cấp',
                'price' => 690000,
                'sku' => 'DAOKEO7M',
                'stock_quantity' => 90,
                'category_id' => $homeCategory->id,
                'featured_image' => 'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=800&h=800&fit=crop&crop=center',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
