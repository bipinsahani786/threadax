<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            // Video Testimonials
            [
                'name' => 'Rahul Sharma',
                'designation' => 'Verified Buyer',
                'photo_url' => 'https://i.pravatar.cc/150?u=rahul',
                'video_url' => 'https://www.youtube.com/watch?v=yAoLSRbwxL8', // Demo dummy youtube link for testing
                'content' => 'Absolutely love the quality! The oversized tee feels premium and the fabric is super heavy. Worth every rupee.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Aditya Verma',
                'designation' => 'Verified Buyer',
                'photo_url' => 'https://i.pravatar.cc/150?u=aditya',
                'video_url' => 'https://www.youtube.com/watch?v=tgbNymZ7vqY', 
                'content' => 'Best fit I have ever bought. The oversized look is on point and the 280GSM fabric feels insanely good.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kunal Singh',
                'designation' => 'Fashion Enthusiast',
                'photo_url' => 'https://i.pravatar.cc/150?u=kunal',
                'video_url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ', 
                'content' => 'If you are looking for authentic streetwear in India, this is it. Period. Highly recommend the hoodies.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 3,
            ],
            
            // Text Testimonials
            [
                'name' => 'Priya Mehta',
                'designation' => 'Fashion Blogger',
                'photo_url' => 'https://i.pravatar.cc/150?u=priya',
                'video_url' => null,
                'content' => 'ThreadAX pieces are unmatched. I\'ve bought from many streetwear brands but the GSM quality here is genuinely better. My hoodie still looks brand new after 6 washes!',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Arjun Kapoor',
                'designation' => 'Verified Buyer',
                'photo_url' => 'https://i.pravatar.cc/150?u=arjun',
                'video_url' => null,
                'content' => 'The delivery was super fast and packaging was clean. Size chart was accurate too. Ordered M and it fits perfectly oversized. 10/10 would recommend!',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Sneha Patel',
                'designation' => 'Style Enthusiast',
                'photo_url' => 'https://i.pravatar.cc/150?u=sneha',
                'video_url' => null,
                'content' => 'Love the designs! Very unique and street-ready. The return process was smooth too when I had to exchange a size. Great customer service overall.',
                'rating' => 4,
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Karan Singh',
                'designation' => 'Verified Buyer',
                'photo_url' => 'https://i.pravatar.cc/150?u=karan',
                'video_url' => null,
                'content' => 'Been wearing ThreadAX for 8 months now. Every piece holds up beautifully. The stitching quality is exceptional for this price point. Highly recommend.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Ananya Joshi',
                'designation' => 'Fitness Influencer',
                'photo_url' => 'https://i.pravatar.cc/150?u=ananya',
                'video_url' => null,
                'content' => 'Finally found a brand that gets streetwear right in India. The fits are perfect, fabric is top notch, and the designs are fire! Already gifted 3 pieces to friends.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Vikram Roy',
                'designation' => 'Verified Buyer',
                'photo_url' => 'https://i.pravatar.cc/150?u=vikram',
                'video_url' => null,
                'content' => 'Just received my order today and I am blown away. The weight of the tshirt itself tells you it is premium. Best purchase of the year.',
                'rating' => 5,
                'is_active' => true,
                'sort_order' => 9,
            ]
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
