<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        if ($products->isEmpty()) return;

        // Create some fake buyers
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::firstOrCreate(
                ['email' => "buyer{$i}@example.com"],
                [
                    'name' => "Verified Buyer {$i}",
                    'password' => Hash::make('password'),
                    'phone' => "987654321{$i}",
                ]
            );
        }

        $comments = [
            'Absolutely amazing quality. Fits perfectly.',
            'Best streetwear brand I have found in India.',
            'The 280GSM fabric is no joke. Super heavy and premium.',
            'Delivery was quick. The packaging is aesthetic.',
            'Worth every penny. The drop shoulder fit is perfect.',
            'Color is exactly as shown. Highly recommend.',
        ];

        foreach ($products as $product) {
            // Give 2-4 reviews per product
            $numReviews = rand(2, 4);
            $selectedUsers = collect($users)->random($numReviews);

            foreach ($selectedUsers as $user) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'rating' => rand(4, 5),
                    'title' => 'Great product!',
                    'body' => $comments[array_rand($comments)],
                    'status' => 'approved',
                ]);
            }
        }
    }
}
