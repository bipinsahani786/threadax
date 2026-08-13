<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'body' => '<h2>Who We Are</h2>
<p>ThreadAX is India\'s fastest-growing premium streetwear brand. We design and craft bold, oversized apparel for those who refuse to blend in. Born from a love of street culture, our mission is to bring you clothing that makes a statement every single day.</p>

<h2>Our Story</h2>
<p>Founded in 2024, ThreadAX started with a simple belief: premium quality streetwear shouldn\'t cost a fortune. We source the finest 280GSM+ heavyweight cotton and craft every piece with meticulous attention to detail. From our iconic oversized tees to our signature hoodies, each product is designed in-house and manufactured in India.</p>

<h2>Our Values</h2>
<ul>
<li><strong>Quality First:</strong> We never compromise on materials. Every ThreadAX piece is built to last.</li>
<li><strong>Bold Design:</strong> Our designs are inspired by global street culture, reimagined for the Indian aesthetic.</li>
<li><strong>Accessible Premium:</strong> Luxury quality at honest prices. No middlemen, no markups.</li>
<li><strong>Sustainability:</strong> We\'re committed to reducing our environmental impact through responsible sourcing and manufacturing.</li>
</ul>

<h2>Our Team</h2>
<p>We\'re a passionate team of designers, creatives, and streetwear enthusiasts based in India. Every drop is crafted with love and obsession for detail.</p>',
                'meta_title' => 'About ThreadAX — India\'s Premium Streetwear Brand',
                'meta_description' => 'Learn about ThreadAX, India\'s fastest-growing premium streetwear brand. Quality oversized apparel designed for those who stand out.',
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'body' => '<h2>Get In Touch</h2>
<p>We\'d love to hear from you! Whether you have a question about our products, sizing, or your order — our team is here to help.</p>

<h3>📧 Email</h3>
<p><a href="mailto:support@threadax.co.in">support@threadax.co.in</a></p>

<h3>💬 WhatsApp</h3>
<p>Chat with us on WhatsApp for instant support. Click the WhatsApp button on any page to start a conversation.</p>

<h3>📱 Social Media</h3>
<p>Follow us on Instagram <a href="https://www.instagram.com/threadax.co.in/" target="_blank">@threadax.co.in</a> for the latest drops, style guides, and exclusive offers.</p>

<h3>🕐 Business Hours</h3>
<p>Monday — Saturday: 10:00 AM – 7:00 PM IST<br>Sunday: Closed</p>

<h3>📍 Address</h3>
<p>ThreadAX Pvt. Ltd.<br>India</p>',
                'meta_title' => 'Contact ThreadAX — Customer Support',
                'meta_description' => 'Contact ThreadAX for questions about orders, products, sizing, or returns. Email, WhatsApp, and social media support available.',
            ],
            [
                'title' => 'Track Order',
                'slug' => 'track-order',
                'body' => '<h2>Track Your Order</h2>
<p>Once your order is shipped, you will receive a confirmation email and SMS with your tracking ID and courier partner details.</p>

<h3>How to Track</h3>
<ol>
<li>Log in to your <a href="/account/orders">ThreadAX Account</a>.</li>
<li>Go to <strong>My Orders</strong>.</li>
<li>Click on the order you want to track.</li>
<li>You\'ll see the tracking ID and a link to the courier partner\'s website.</li>
</ol>

<h3>Didn\'t receive a tracking email?</h3>
<p>Please check your spam/junk folder. If you still can\'t find it, contact us at <a href="mailto:support@threadax.co.in">support@threadax.co.in</a> with your order number.</p>

<h3>Estimated Delivery Times</h3>
<ul>
<li><strong>Metro Cities:</strong> 3-5 business days</li>
<li><strong>Tier 2/3 Cities:</strong> 5-7 business days</li>
<li><strong>Remote Areas:</strong> 7-10 business days</li>
</ul>',
                'meta_title' => 'Track Your Order — ThreadAX',
                'meta_description' => 'Track your ThreadAX order status. Get real-time updates on your shipment with our easy order tracking system.',
            ],
            [
                'title' => 'Returns & Exchanges',
                'slug' => 'returns-exchanges',
                'body' => '<h2>Returns & Exchange Policy</h2>
<p>At ThreadAX, your satisfaction is our priority. If you\'re not 100% happy with your purchase, we offer easy returns within 7 days of delivery.</p>

<h3>Return Eligibility</h3>
<ul>
<li>Items must be returned within <strong>7 days</strong> of delivery.</li>
<li>Products must be <strong>unworn, unwashed</strong>, with all tags attached.</li>
<li>Items purchased during <strong>sale/discount</strong> events are eligible for exchange only, not refund.</li>
</ul>

<h3>How to Initiate a Return</h3>
<ol>
<li>Email us at <a href="mailto:support@threadax.co.in">support@threadax.co.in</a> with your order number and reason for return.</li>
<li>Our team will share the return shipping instructions within 24 hours.</li>
<li>Pack the item securely and ship it back.</li>
<li>Once received and inspected, your refund will be processed within 5-7 business days.</li>
</ol>

<h3>Exchanges</h3>
<p>For size exchanges, please contact us within 7 days of delivery. Subject to stock availability.</p>

<h3>Non-Returnable Items</h3>
<p>Innerwear, accessories, and customized products cannot be returned.</p>',
                'meta_title' => 'Returns & Exchanges — ThreadAX',
                'meta_description' => 'Easy 7-day returns and exchanges at ThreadAX. Learn about our hassle-free return policy and how to initiate a return.',
            ],
            [
                'title' => 'Shipping Info',
                'slug' => 'shipping-info',
                'body' => '<h2>Shipping Information</h2>
<p>We ship across India! Here\'s everything you need to know about ThreadAX shipping.</p>

<h3>Free Shipping</h3>
<p>🎉 <strong>FREE shipping</strong> on all orders above ₹999. Orders below ₹999 have a flat shipping charge of ₹79.</p>

<h3>Delivery Timeline</h3>
<ul>
<li><strong>Metro Cities (Delhi, Mumbai, Bengaluru, etc.):</strong> 3-5 business days</li>
<li><strong>Tier 2/3 Cities:</strong> 5-7 business days</li>
<li><strong>Remote / North-East Areas:</strong> 7-10 business days</li>
</ul>

<h3>Courier Partners</h3>
<p>We ship via trusted courier partners including Delhivery, Bluedart, and DTDC to ensure safe and timely delivery.</p>

<h3>Order Processing</h3>
<p>Orders placed before 2:00 PM IST (Mon-Sat) are usually dispatched on the same day. Orders placed after 2:00 PM or on Sundays/holidays are dispatched the next business day.</p>

<h3>International Shipping</h3>
<p>Currently, we only ship within India. International shipping is coming soon!</p>',
                'meta_title' => 'Shipping Policy — ThreadAX',
                'meta_description' => 'Free shipping on orders above ₹999. Fast pan-India delivery in 3-7 business days via trusted courier partners.',
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'body' => '<h2>Frequently Asked Questions</h2>

<h3>What is ThreadAX?</h3>
<p>ThreadAX is India\'s premium streetwear brand, offering bold oversized apparel crafted from 280GSM+ heavyweight cotton.</p>

<h3>What payment methods do you accept?</h3>
<p>We accept UPI, Debit/Credit Cards, Net Banking, and Wallets through our secure Razorpay payment gateway.</p>

<h3>How do I find my size?</h3>
<p>Check our size guide on each product page. We recommend going with your usual size for a standard oversized fit, or size down for a more fitted look.</p>

<h3>Can I cancel my order?</h3>
<p>Orders can be cancelled within 2 hours of placing them. After that, we may have already dispatched your order. Contact us at support@threadax.co.in for assistance.</p>

<h3>How do I contact customer support?</h3>
<p>You can reach us via email at support@threadax.co.in or chat with us on WhatsApp. We typically respond within 24 hours.</p>

<h3>Do you offer Cash on Delivery (COD)?</h3>
<p>Currently, we only accept prepaid orders via our secure Razorpay checkout. COD will be available soon.</p>',
                'meta_title' => 'FAQ — ThreadAX',
                'meta_description' => 'Find answers to frequently asked questions about ThreadAX products, shipping, returns, payments, and more.',
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'body' => '<h2>Privacy Policy</h2>
<p><strong>Last Updated:</strong> August 2026</p>

<p>ThreadAX Pvt. Ltd. ("we", "us", "our") is committed to protecting and respecting your privacy. This policy explains how we collect, use, and protect your personal information.</p>

<h3>Information We Collect</h3>
<ul>
<li><strong>Account Information:</strong> Name, email, phone number when you register.</li>
<li><strong>Order Information:</strong> Delivery address, payment details (processed securely by Razorpay).</li>
<li><strong>Usage Data:</strong> Pages visited, time spent, browser type (via Google Analytics).</li>
</ul>

<h3>How We Use Your Information</h3>
<ul>
<li>To process and deliver your orders.</li>
<li>To send order updates and shipping notifications.</li>
<li>To improve our website and shopping experience.</li>
<li>To send marketing emails (only with your consent).</li>
</ul>

<h3>Data Security</h3>
<p>We use SSL encryption and Razorpay\'s PCI-DSS compliant payment gateway to protect your data. We never store your card details on our servers.</p>

<h3>Your Rights</h3>
<p>You can request access to, correction, or deletion of your personal data at any time by emailing support@threadax.co.in.</p>

<h3>Contact</h3>
<p>For privacy-related inquiries: <a href="mailto:support@threadax.co.in">support@threadax.co.in</a></p>',
                'meta_title' => 'Privacy Policy — ThreadAX',
                'meta_description' => 'Read ThreadAX\'s privacy policy. Learn how we collect, use, and protect your personal information.',
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'body' => '<h2>Terms of Service</h2>
<p><strong>Last Updated:</strong> August 2026</p>

<p>By accessing and using the ThreadAX website (threadax.co.in), you agree to these Terms of Service.</p>

<h3>1. Account</h3>
<p>You are responsible for maintaining the confidentiality of your account credentials. You must be 16 years or older to make a purchase.</p>

<h3>2. Orders & Pricing</h3>
<p>All prices are listed in Indian Rupees (INR) and include applicable taxes. We reserve the right to modify prices at any time without prior notice. Orders are confirmed only after successful payment.</p>

<h3>3. Products</h3>
<p>We make every effort to display product colors and details accurately. However, actual product colors may vary slightly due to monitor settings.</p>

<h3>4. Returns</h3>
<p>Please refer to our <a href="/page/returns-exchanges">Returns & Exchanges Policy</a> for detailed information.</p>

<h3>5. Intellectual Property</h3>
<p>All content on this website — including logos, designs, images, and text — is the property of ThreadAX and protected by Indian copyright law.</p>

<h3>6. Limitation of Liability</h3>
<p>ThreadAX is not liable for any indirect, incidental, or consequential damages arising from the use of our website or products.</p>

<h3>7. Governing Law</h3>
<p>These terms are governed by the laws of India. Any disputes shall be subject to the jurisdiction of courts in India.</p>

<h3>Contact</h3>
<p>Questions? Email us at <a href="mailto:support@threadax.co.in">support@threadax.co.in</a></p>',
                'meta_title' => 'Terms of Service — ThreadAX',
                'meta_description' => 'Read ThreadAX\'s terms of service. Understand your rights and responsibilities when shopping with us.',
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
