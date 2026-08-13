<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Do you offer free shipping?',
                'answer' => 'Yes! We offer free shipping on all orders above ₹999. Orders below ₹999 have a flat shipping fee of ₹79.',
                'sort_order' => 1,
            ],
            [
                'question' => 'What is your return policy?',
                'answer' => 'We offer a 7-day hassle-free return policy. If you\'re not satisfied with your purchase, contact us and we\'ll make it right — no questions asked.',
                'sort_order' => 2,
            ],
            [
                'question' => 'How do I find the right size?',
                'answer' => 'Each product page includes a detailed size chart. Our oversized fits are designed to be worn loose — we recommend checking the measurements before ordering.',
                'sort_order' => 3,
            ],
            [
                'question' => 'How long does delivery take?',
                'answer' => 'Standard delivery takes 3-7 business days across India. Express delivery options are available at checkout.',
                'sort_order' => 4,
            ],
            [
                'question' => 'Are your products 100% cotton?',
                'answer' => 'Yes! All ThreadAX apparel is crafted from 100% premium cotton (280GSM+). We never compromise on fabric quality.',
                'sort_order' => 5,
            ],
            [
                'question' => 'Can I track my order?',
                'answer' => 'Absolutely. Once shipped, you\'ll receive a tracking link via email and SMS. You can also track your order from your account dashboard.',
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create(array_merge($faq, ['is_active' => true]));
        }
    }
}
