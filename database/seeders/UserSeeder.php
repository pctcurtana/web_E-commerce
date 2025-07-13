<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        // Create realistic reviewer users
        $reviewerNames = [
            'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Hoàng Cường', 'Phạm Thị Dung',
            'Hoàng Minh Đức', 'Đặng Thị Hoa', 'Võ Văn Hùng', 'Bùi Thị Lan',
            'Đinh Văn Long', 'Phan Thị Mai', 'Tạ Văn Nam', 'Lý Thị Oanh',
            'Vũ Văn Phát', 'Ngô Thị Quỳnh', 'Đỗ Văn Sơn', 'Chu Thị Tâm',
            'Hồ Văn Tuấn', 'Mai Thị Uyên', 'Lại Văn Việt', 'Cao Thị Xuân',
            'Dương Văn Yên', 'Ninh Thị Thảo', 'Kiều Văn Khang', 'Từ Thị Linh',
            'Ưng Văn Minh', 'Bành Thị Ngọc', 'Thạch Văn Phong', 'Lục Thị Thu',
            'Ông Văn Tiến', 'Hà Thị Vân', 'Thân Văn Hải', 'Lâm Thị Diệu',
            'Trương Văn Khánh', 'Pháp Thị Loan', 'Đình Văn Mạnh', 'Triệu Thị Nga',
            'Ân Văn Ơn', 'Lưu Thị Phương', 'Trang Văn Quang', 'Đàm Thị Rạng',
            'Khổng Văn Sáng', 'Hứa Thị Thơm', 'Vương Văn Ung', 'Quan Thị Vui',
            'Lạc Văn Ý', 'Nhâm Thị Ánh', 'Tôn Văn Ân', 'Mạc Thị Điềm'
        ];

        foreach ($reviewerNames as $index => $name) {
            $email = 'user' . ($index + 3) . '@example.com';
            
            User::create([
                'name' => $name,
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'created_at' => now()->subDays(rand(1, 365)),
            ]);
        }
    }
}
