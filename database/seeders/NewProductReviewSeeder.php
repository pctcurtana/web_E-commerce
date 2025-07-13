<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NewProductReviewSeeder extends Seeder
{
    public function run()
    {
        // Lấy các user để làm reviewer
        $users = User::where('role', 'user')->get();
        
        if ($users->count() < 5) {
            $this->command->warn('Cần ít nhất 5 user để tạo reviews');
            return;
        }

        // Lấy sản phẩm mới (ID >= 68) cần tạo reviews
        $newProducts = Product::where('id', '>=', 68)->get();

        // Mẫu review cho từng loại sản phẩm
        $reviewTemplates = [
            'dien-thoai-phu-kien' => [
                'positive' => [
                    'Điện thoại chất lượng tốt, pin trâu, camera đẹp',
                    'Sản phẩm chính hãng, ship nhanh, đóng gói cẩn thận',
                    'Máy đẹp, màn hình sắc nét, hiệu năng mượt mà',
                    'Giá tốt, chất lượng ổn, sẽ ủng hộ shop tiếp',
                    'Pin dùng cả ngày, camera chụp đẹp, recommended',
                ],
                'neutral' => [
                    'Sản phẩm ổn, phù hợp tầm giá',
                    'Chất lượng khá, có vài điểm nhỏ cần cải thiện',
                    'Tạm ổn, đúng như mô tả',
                ],
                'negative' => [
                    'Máy hơi nóng khi chơi game nhiều',
                    'Pin có vẻ không trâu như quảng cáo',
                ]
            ],
            'may-tinh-laptop' => [
                'positive' => [
                    'Laptop chạy mượt, cấu hình tốt, thiết kế đẹp',
                    'Hiệu năng ổn định, phù hợp làm việc và giải trí',
                    'Màn hình đẹp, bàn phím gõ thoải mái',
                    'Laptop tốt trong tầm giá, đáng mua',
                    'Chạy game mượt, rendering nhanh',
                ],
                'neutral' => [
                    'Laptop tạm ổn, phù hợp nhu cầu cơ bản',
                    'Cấu hình ổn, có vài điểm chưa hoàn hảo',
                ],
                'negative' => [
                    'Máy hơi nặng, quạt kêu nhiều',
                    'Pin yếu, cần cắm sạc thường xuyên',
                ]
            ],
            'thoi-trang-nam' => [
                'positive' => [
                    'Áo đẹp, chất vải thoáng mát, form chuẩn',
                    'Size vừa vặn, chất lượng tốt, giá hợp lý',
                    'Đóng gói cẩn thận, giao hàng nhanh',
                    'Chất liệu tốt, thiết kế đẹp, sẽ mua thêm',
                    'Form đẹp, mặc vừa vặn, chất vải mềm mịn',
                ],
                'neutral' => [
                    'Sản phẩm ổn, đúng như hình',
                    'Chất lượng tạm được, giá phù hợp',
                ],
                'negative' => [
                    'Vải hơi mỏng, màu nhạt hơn ảnh',
                    'Size hơi nhỏ so với bảng size',
                ]
            ],
            'thoi-trang-nu' => [
                'positive' => [
                    'Váy đẹp, chất vải mềm mại, thiết kế sang trọng',
                    'Mặc vào rất xinh, form chuẩn, chất lượng tốt',
                    'Giày đẹp, đi êm chân, chất liệu tốt',
                    'Sản phẩm chất lượng, đóng gói cẩn thận',
                    'Thiết kế trendy, phù hợp đi làm và dạo phố',
                ],
                'neutral' => [
                    'Sản phẩm ổn, phù hợp tầm giá',
                    'Chất lượng khá, màu sắc đẹp',
                ],
                'negative' => [
                    'Chất liệu hơi cứng, cần giặt mềm',
                    'Màu sắc nhạt hơn ảnh một chút',
                ]
            ],
            'me-be' => [
                'positive' => [
                    'Sản phẩm chất lượng, an toàn cho bé',
                    'Bé thích lắm, chất liệu tốt, đáng mua',
                    'Đóng gói cẩn thận, giao hàng nhanh',
                    'Chất lượng tốt, giá cả hợp lý',
                    'Bé sử dụng ổn định, sẽ mua thêm',
                ],
                'neutral' => [
                    'Sản phẩm ổn, phù hợp cho bé',
                    'Chất lượng khá, đúng như mô tả',
                ],
                'negative' => [
                    'Hơi to so với tuổi bé',
                    'Chất liệu ổn nhưng mùi hơi nặng',
                ]
            ],
            'nha-cua-doi-song' => [
                'positive' => [
                    'Sản phẩm chất lượng, hoạt động tốt',
                    'Thiết kế đẹp, tiện dụng, dễ sử dụng',
                    'Chất liệu bền, đóng gói cẩn thận',
                    'Giá tốt, chất lượng ổn định',
                    'Dùng ổn định, tiết kiệm điện',
                ],
                'neutral' => [
                    'Sản phẩm tạm ổn, phù hợp nhu cầu',
                    'Chất lượng khá, giá hợp lý',
                ],
                'negative' => [
                    'Hơi ồn khi hoạt động',
                    'Chất liệu ổn nhưng hơi nhẹ',
                ]
            ],
            'sach' => [
                'positive' => [
                    'Sách hay, nội dung bổ ích, đóng gói tốt',
                    'Chất lượng giấy tốt, in rõ nét',
                    'Nội dung thú vị, đáng đọc',
                    'Sách chất lượng, giao hàng nhanh',
                    'Kiến thức bổ ích, dễ hiểu',
                ],
                'neutral' => [
                    'Sách ổn, nội dung tạm được',
                    'Chất lượng in khá, giá hợp lý',
                ],
                'negative' => [
                    'Giấy hơi mỏng, cần cẩn thận khi đọc',
                    'Nội dung ổn nhưng bìa hơi cũ',
                ]
            ],
            'the-thao-du-lich' => [
                'positive' => [
                    'Sản phẩm chất lượng, phù hợp thể thao',
                    'Thiết kế đẹp, chất liệu bền',
                    'Sử dụng thoải mái, hiệu quả tốt',
                    'Giá tốt, chất lượng ổn định',
                    'Dùng ổn định, phù hợp hoạt động ngoài trời',
                ],
                'neutral' => [
                    'Sản phẩm ổn, phù hợp nhu cầu',
                    'Chất lượng khá, giá hợp lý',
                ],
                'negative' => [
                    'Hơi nặng khi di chuyển',
                    'Chất liệu ổn nhưng thiết kế chưa tinh tế',
                ]
            ]
        ];

        foreach ($newProducts as $product) {
            $targetReviews = $product->review_count;
            $categorySlug = $product->category->slug;
            
            // Xóa reviews cũ nếu có (để tránh duplicate)
            $product->reviews()->delete();
            
            // Tính toán phân bố rating dựa trên average_rating
            $avgRating = $product->average_rating;
            $ratingDistribution = $this->calculateRatingDistribution($avgRating, $targetReviews);
            
            $createdReviews = 0;
            $usedUsers = []; // Track users đã được sử dụng cho product này
            
            foreach ($ratingDistribution as $rating => $count) {
                for ($i = 0; $i < $count; $i++) {
                    // Chọn user chưa được sử dụng cho product này
                    $availableUsers = $users->whereNotIn('id', $usedUsers);
                    
                    if ($availableUsers->count() == 0) {
                        // Nếu hết user, break
                        break 2;
                    }
                    
                    $user = $availableUsers->random();
                    $usedUsers[] = $user->id;
                    
                    // Chọn template review phù hợp
                    $templates = $reviewTemplates[$categorySlug] ?? $reviewTemplates['dien-thoai-phu-kien'];
                    
                    if ($rating >= 4) {
                        $reviewText = $templates['positive'][array_rand($templates['positive'])];
                    } elseif ($rating >= 3) {
                        $reviewText = $templates['neutral'][array_rand($templates['neutral'])];
                    } else {
                        $reviewText = $templates['negative'][array_rand($templates['negative'])];
                    }
                    
                    Review::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'rating' => $rating,
                        'comment' => $reviewText,
                        'is_approved' => true,
                        'is_verified_purchase' => true,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 30)),
                    ]);
                    
                    $createdReviews++;
                    
                    if ($createdReviews >= $targetReviews) {
                        break 2;
                    }
                }
            }
            
            $this->command->info("Created {$createdReviews} reviews for {$product->name}");
        }

        $this->command->info('New product review seeder completed!');
    }

    private function calculateRatingDistribution($avgRating, $totalReviews)
    {
        $distribution = [];
        
        // Tính phân bố rating dựa trên average
        if ($avgRating >= 4.5) {
            // Sản phẩm tốt: 60% 5 sao, 30% 4 sao, 10% còn lại
            $distribution[5] = round($totalReviews * 0.6);
            $distribution[4] = round($totalReviews * 0.3);
            $distribution[3] = round($totalReviews * 0.08);
            $distribution[2] = round($totalReviews * 0.02);
        } elseif ($avgRating >= 4.0) {
            // Sản phẩm khá: 40% 5 sao, 40% 4 sao, 20% còn lại
            $distribution[5] = round($totalReviews * 0.4);
            $distribution[4] = round($totalReviews * 0.4);
            $distribution[3] = round($totalReviews * 0.15);
            $distribution[2] = round($totalReviews * 0.05);
        } elseif ($avgRating >= 3.5) {
            // Sản phẩm trung bình: phân bố đều hơn
            $distribution[5] = round($totalReviews * 0.25);
            $distribution[4] = round($totalReviews * 0.35);
            $distribution[3] = round($totalReviews * 0.25);
            $distribution[2] = round($totalReviews * 0.15);
        } else {
            // Sản phẩm kém: nhiều rating thấp
            $distribution[5] = round($totalReviews * 0.15);
            $distribution[4] = round($totalReviews * 0.25);
            $distribution[3] = round($totalReviews * 0.35);
            $distribution[2] = round($totalReviews * 0.25);
        }
        
        // Đảm bảo tổng số reviews đúng
        $currentTotal = array_sum($distribution);
        if ($currentTotal < $totalReviews) {
            $distribution[4] += ($totalReviews - $currentTotal);
        }
        
        return array_filter($distribution, function($count) {
            return $count > 0;
        });
    }
} 