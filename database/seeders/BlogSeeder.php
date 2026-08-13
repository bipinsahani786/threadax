<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'The Ultimate Guide to Oversized T-Shirts in 2026',
                'slug' => 'ultimate-guide-oversized-tshirts-2026',
                'excerpt' => 'Everything you need to know about styling oversized tees — from casual street looks to layered fits.',
                'featured_image' => 'images/about/grid1.png',
                'body' => '<p>Oversized t-shirts have evolved from a 90s hip-hop staple to a modern wardrobe essential. In 2026, the oversized silhouette isn\'t just about comfort — it\'s about making a statement.</p>
<p>At ThreadAX, we use 280GSM+ heavyweight cotton for our oversized tees. This means they drape beautifully without looking shapeless, and they hold up wash after wash.</p>

<h2>Sizing Tips</h2>
<p>If you want the classic oversized drop-shoulder look, go with your regular size. For an even more relaxed fit, size up once. Our size guides on each product page are your best friend.</p>

<p><strong>Shop our <a href="/shop">Oversized Collection</a> and find your perfect fit.</strong></p>',
                'author' => 'ThreadAX',
                'category' => 'Style Guide',
                'tags' => ['oversized', 'styling', 'streetwear', 't-shirts'],
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => '5 Streetwear Trends That Will Dominate 2026',
                'slug' => '5-streetwear-trends-2026',
                'excerpt' => 'From utility wear to retro revival — here are the trends shaping streetwear this year.',
                'featured_image' => 'images/about/grid2.png',
                'body' => '<p>Streetwear is constantly evolving, and 2026 is bringing some exciting shifts. Here are the top 5 trends to watch out for:</p>

<h2>1. Heavyweight Essentials</h2>
<p>Thin, fast-fashion tees are out. Consumers are demanding thick, heavyweight cotton (240-300GSM) that feels premium and lasts longer. This is exactly what we\'ve been championing at ThreadAX since day one.</p>

<h2>2. Earth Tones & Muted Palettes</h2>
<p>While bold graphics aren\'t going anywhere, there\'s a massive shift towards earthy tones — olive, clay, sand, charcoal, and off-white. These colors pair effortlessly and feel premium.</p>

<h2>3. Utility & Cargo Details</h2>
<p>Pockets everywhere! Cargo pants, utility jackets, and multi-pocket shorts are having a major moment. Function meets fashion in the most satisfying way.</p>

<h2>4. Retro Sports Aesthetics</h2>
<p>Think 90s basketball shorts, vintage team graphics, and retro-inspired cuts. Nostalgia continues to be a powerful force in streetwear.</p>

<h2>5. Gender-Neutral Drops</h2>
<p>Unisex collections are becoming the norm rather than the exception. Oversized fits naturally lend themselves to gender-fluid styling, making them more inclusive than ever.</p>

<p><strong>Stay ahead of the curve. <a href="/shop">Explore our latest collection</a>.</strong></p>',
                'author' => 'ThreadAX',
                'category' => 'Fashion',
                'tags' => ['trends', 'streetwear', '2026', 'fashion'],
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Why 280GSM Cotton is the Gold Standard',
                'slug' => 'why-280gsm-cotton-gold-standard',
                'excerpt' => 'What makes heavyweight cotton superior to regular tees? Let\'s break it down.',
                'featured_image' => 'images/about/grid4.png',
                'body' => '<p>Not all t-shirts are created equal. The difference between a ₹200 mall tee and a ThreadAX heavyweight lies in one crucial metric: GSM (Grams per Square Meter).</p>

<h2>What is GSM?</h2>
<p>GSM measures the weight and density of fabric. A higher GSM means thicker, more durable material.</p>
<ul>
<li><strong>120-150 GSM:</strong> Thin, see-through tees (common in fast fashion)</li>
<li><strong>180-200 GSM:</strong> Standard mid-weight tees</li>
<li><strong>240-280 GSM:</strong> Heavyweight premium tees (ThreadAX territory)</li>
<li><strong>300+ GSM:</strong> Ultra-heavy, almost sweatshirt-like</li>
</ul>

<h2>Why We Choose 280 GSM</h2>
<p>280GSM hits the sweet spot between structure and comfort. Our tees:</p>
<ul>
<li>✅ Hold their shape — no warping or shrinking</li>
<li>✅ Feel substantial — you can feel the quality instantly</li>
<li>✅ Drape beautifully — especially in oversized cuts</li>
<li>✅ Last longer — they survive dozens of washes without losing quality</li>
<li>✅ No see-through — solid opacity in every color</li>
</ul>

<h2>The ThreadAX Difference</h2>
<p>We combine premium 280GSM cotton with bio-washed finishing, double-stitched seams, and custom-dyed colors for a product that truly feels luxury. Once you wear a ThreadAX tee, there\'s no going back to regular cotton.</p>

<p><strong><a href="/shop">Experience the difference yourself →</a></strong></p>',
                'author' => 'ThreadAX',
                'category' => 'Behind the Scenes',
                'tags' => ['quality', 'cotton', '280gsm', 'manufacturing'],
                'status' => 'published',
                'published_at' => now()->subDays(14),
            ],
        ];

        foreach ($blogs as $blog) {
            Blog::updateOrCreate(['slug' => $blog['slug']], $blog);
        }
    }
}
