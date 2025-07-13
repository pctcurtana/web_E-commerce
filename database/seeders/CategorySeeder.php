<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Điện thoại & Phụ kiện',
                'description' => 'Điện thoại thông minh, tai nghe, ốp lưng và các phụ kiện điện thoại',
                'is_active' => true,
            ],
            [
                'name' => 'Máy tính & Laptop',
                'description' => 'Laptop, máy tính để bàn, linh kiện máy tính',
                'is_active' => true,
            ],
            [
                'name' => 'Thời trang Nam',
                'description' => 'Quần áo, giày dép, phụ kiện thời trang nam',
                'is_active' => true,
            ],
            [
                'name' => 'Thời trang Nữ',
                'description' => 'Quần áo, giày dép, túi xách, phụ kiện thời trang nữ',
                'is_active' => true,
            ],
            [
                'name' => 'Mẹ & Bé',
                'description' => 'Đồ dùng cho mẹ và bé, đồ chơi trẻ em',
                'is_active' => true,
            ],
            [
                'name' => 'Nhà cửa & Đời sống',
                'description' => 'Đồ gia dụng, nội thất, đồ trang trí',
                'is_active' => true,
            ],
            [
                'name' => 'Sách',
                'description' => 'Sách giáo khoa, sách tham khảo, văn học',
                'is_active' => true,
            ],
            [
                'name' => 'Thể thao & Du lịch',
                'description' => 'Dụng cụ thể thao, đồ du lịch, camping',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
