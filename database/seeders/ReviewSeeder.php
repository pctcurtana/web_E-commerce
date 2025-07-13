<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::where('email', '!=', 'admin@example.com')->get();
        
        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No products or users found. Run ProductSeeder and UserSeeder first.');
            return;
        }

        $reviewTemplates = [
            5 => [
                'titles' => [
                    'Tuyệt vời!', 'Rất hài lòng', 'Chất lượng tốt', 'Đáng tiền',
                    'Sản phẩm tuyệt vời', 'Quá hài lòng', 'Rất tốt', 'Xuất sắc'
                ],
                'comments' => [
                    'Sản phẩm chất lượng tốt, đúng như mô tả. Giao hàng nhanh, đóng gói cẩn thận.',
                    'Mình rất hài lòng với sản phẩm này. Chất lượng vượt mong đợi, sẽ mua lại.',
                    'Sản phẩm tốt, giá cả hợp lý. Shop giao hàng nhanh, hỗ trợ nhiệt tình.',
                    'Chất lượng tuyệt vời, đúng như hình. Đóng gói kỹ càng, giao hàng đúng hẹn.',
                    'Sản phẩm vượt ngoài mong đợi. Chất liệu tốt, thiết kế đẹp, rất hài lòng.',
                    'Mua về sử dụng rất ổn. Chất lượng tốt, giá cả phải chăng. Recommend!',
                    'Shop uy tín, sản phẩm chất lượng. Giao hàng nhanh, đóng gói cẩn thận.',
                    'Rất hài lòng với lần mua hàng này. Sản phẩm tốt, service chu đáo.'
                ]
            ],
            4 => [
                'titles' => [
                    'Tốt', 'Hài lòng', 'Ổn', 'Đáng mua',
                    'Sản phẩm tốt', 'Chất lượng', 'OK', 'Khá tốt'
                ],
                'comments' => [
                    'Sản phẩm tốt, đúng mô tả. Giao hàng hơi chậm nhưng chất lượng ổn.',
                    'Chất lượng khá tốt, giá cả hợp lý. Có thể cải thiện thêm về đóng gói.',
                    'Sản phẩm ổn, đúng như mong đợi. Giao hàng đúng hẹn, shop hỗ trợ tốt.',
                    'Mình khá hài lòng với sản phẩm. Chất lượng tốt, chỉ có màu sắc hơi khác hình.',
                    'Sản phẩm tương đối tốt. Chất liệu ổn, thiết kế đẹp. Giao hàng nhanh.',
                    'Chất lượng khá, giá cả phù hợp. Có thể mua để sử dụng.',
                    'Sản phẩm đúng mô tả, chất lượng tạm ổn. Shop giao hàng đúng hẹn.',
                    'Tổng thể ổn, có vài điểm nhỏ cần cải thiện nhưng vẫn đáng mua.'
                ]
            ],
            3 => [
                'titles' => [
                    'Bình thường', 'Tạm ổn', 'Cũng được', 'Trung bình',
                    'Không tệ', 'Bình thường thôi', 'Tạm', 'Có thể'
                ],
                'comments' => [
                    'Sản phẩm tạm ổn, không quá xuất sắc nhưng cũng không tệ.',
                    'Chất lượng trung bình, phù hợp với tầm giá. Giao hàng đúng hẹn.',
                    'Sản phẩm bình thường, đúng mô tả. Có thể cải thiện thêm về chất lượng.',
                    'Mình thấy sản phẩm tạm ổn, không có gì đặc biệt nhưng cũng đủ dùng.',
                    'Chất lượng trung bình khá, giá cả hợp lý. Giao hàng hơi chậm.',
                    'Sản phẩm cũng được, không xuất sắc lắm nhưng đáp ứng được nhu cầu.',
                    'Tạm ổn thôi, chất lượng không ấn tượng lắm nhưng cũng đủ xài.',
                    'Sản phẩm bình thường, đúng giá tiền. Có thể mua để dùng tạm.'
                ]
            ],
            2 => [
                'titles' => [
                    'Không như mong đợi', 'Hơi thất vọng', 'Chưa tốt', 'Cần cải thiện',
                    'Không ổn lắm', 'Hơi tệ', 'Chất lượng kém', 'Thất vọng'
                ],
                'comments' => [
                    'Sản phẩm không như mong đợi. Chất lượng kém hơn mô tả.',
                    'Hơi thất vọng về chất lượng. Giao hàng chậm, đóng gói không cẩn thận.',
                    'Sản phẩm không tốt lắm, khác so với hình ảnh. Cần cải thiện.',
                    'Chất lượng không được như quảng cáo. Hơi tiếc tiền.',
                    'Sản phẩm tạm được nhưng có nhiều điểm cần cải thiện.',
                    'Không hài lòng lắm, chất lượng kém. Giao hàng cũng chậm.',
                    'Sản phẩm không đạt yêu cầu, khác xa so với mô tả.',
                    'Thất vọng về chất lượng, không đáng đồng tiền bát gạo.'
                ]
            ],
            1 => [
                'titles' => [
                    'Rất tệ', 'Thất vọng hoàn toàn', 'Không nên mua', 'Chất lượng kém',
                    'Tệ', 'Không đáng tiền', 'Rất kém', 'Không hài lòng'
                ],
                'comments' => [
                    'Sản phẩm rất tệ, hoàn toàn không giống hình. Không nên mua.',
                    'Chất lượng kém, khác hoàn toàn so với mô tả. Rất thất vọng.',
                    'Sản phẩm tệ, giao hàng chậm. Shop không hỗ trợ tốt.',
                    'Rất thất vọng, sản phẩm kém chất lượng. Phí tiền.',
                    'Không nên mua sản phẩm này. Chất lượng quá tệ.',
                    'Sản phẩm không đáng đồng tiền, chất lượng rất kém.',
                    'Thất vọng hoàn toàn, sản phẩm không như mong đợi.',
                    'Rất tệ, không khuyến khích mua. Chất lượng quá kém.'
                ]
            ]
        ];

        foreach ($products as $product) {
            // Determine review count based on product characteristics
            $baseReviewCount = $this->getBaseReviewCount($product);
            $reviewCount = rand($baseReviewCount - 5, $baseReviewCount + 10);
            $reviewCount = max(2, $reviewCount); // At least 2 reviews
            
            // Get random users for this product
            $productReviewers = $users->random(min($reviewCount, $users->count()));
            
            foreach ($productReviewers as $index => $user) {
                $rating = $this->generateRatingForProduct($product);
                $template = $reviewTemplates[$rating];
                
                $review = Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => $rating,
                    'title' => $template['titles'][array_rand($template['titles'])],
                    'comment' => $template['comments'][array_rand($template['comments'])],
                    'is_verified_purchase' => rand(1, 100) <= 70, // 70% verified purchases
                    'is_recommended' => $rating >= 4 ? true : (rand(1, 100) <= 30), // 100% for 4-5 stars, 30% for 1-3 stars
                    'is_approved' => true,
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }

            // Update sold count based on reviews and product characteristics
            $soldCount = $this->generateSoldCount($product, $reviewCount);
            $product->update(['sold_count' => $soldCount]);
        }

        $this->command->info('Reviews seeded successfully!');
    }

    private function getBaseReviewCount(Product $product): int
    {
        // Higher priced products tend to have more reviews
        if ($product->price > 2000000) return 25;
        if ($product->price > 1000000) return 20;
        if ($product->price > 500000) return 15;
        return 10;
    }

    private function generateRatingForProduct(Product $product): int
    {
        // Premium products (higher price) tend to have better ratings
        $baseRating = 3;
        
        if ($product->price > 2000000 || strpos(strtolower($product->name), 'premium') !== false) {
            $baseRating = 4.5;
        } elseif ($product->price > 1000000 || strpos(strtolower($product->name), 'pro') !== false) {
            $baseRating = 4;
        } elseif ($product->price > 500000) {
            $baseRating = 3.5;
        }

        // Add some randomness
        $variance = rand(-1, 1);
        $finalRating = $baseRating + $variance + (rand(0, 10) / 10);
        
        return max(1, min(5, round($finalRating)));
    }

    private function generateSoldCount(Product $product, int $reviewCount): int
    {
        // Reviews represent about 5-10% of total sales
        $salesMultiplier = rand(10, 20);
        $baseSold = $reviewCount * $salesMultiplier;
        
        // Add variance based on product characteristics
        if ($product->price < 200000) {
            $baseSold *= rand(150, 300) / 100; // Cheaper products sell more
        } elseif ($product->price > 1000000) {
            $baseSold *= rand(50, 100) / 100; // Expensive products sell less
        }
        
        return max(1, round($baseSold));
    }
}
