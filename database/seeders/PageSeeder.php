<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'page' => 'Home',
                'title' => 'Home - Welcome to Leecommerce',
                'keywords' => 'ecommerce, online store, shopping, retail, home, deals',
                'description' => 'Welcome to Leecommerce, your one-stop destination for premium products, exclusive deals, and an exceptional shopping experience.',
                'media' => null,
            ],
            [
                'page' => 'About',
                'title' => 'About Us - Leecommerce',
                'keywords' => 'about us, history, team, mission, company values',
                'description' => 'Learn more about Leecommerce, our values, our mission to deliver quality, and our history of serving customers with dedication.',
                'media' => null,
            ],
            [
                'page' => 'Contact',
                'title' => 'Contact Us - Get in Touch',
                'keywords' => 'contact, support, customer service, email, phone support',
                'description' => 'Have questions or need assistance? Contact the Leecommerce support team. We are here to help you with your orders and inquiries.',
                'media' => null,
            ],
            [
                'page' => 'Cart',
                'title' => 'Shopping Cart - Leecommerce',
                'keywords' => 'cart, shopping cart, checkout, select products',
                'description' => 'Review the items in your shopping cart, adjust quantities, and proceed to checkout to secure your purchases.',
                'media' => null,
            ],
            [
                'page' => 'Wishlist',
                'title' => 'My Wishlist - Saved Items',
                'keywords' => 'wishlist, saved products, favorites, shopping list',
                'description' => 'View and manage your saved products. Keep track of items you love and buy them whenever you are ready.',
                'media' => null,
            ],
            [
                'page' => 'Checkout',
                'title' => 'Secure Checkout - Finalize Order',
                'keywords' => 'checkout, payment, shipping address, place order',
                'description' => 'Complete your purchase securely. Enter your shipping address, choose your payment method, and review your final order details.',
                'media' => null,
            ],
            [
                'page' => 'Search',
                'title' => 'Product Search Results - Leecommerce',
                'keywords' => 'search, find products, catalog lookup, search catalog',
                'description' => 'Browse search results for your query. Find exactly what you are looking for in our extensive product catalog.',
                'media' => null,
            ],
            [
                'page' => 'Return & Replace',
                'title' => 'Return & Replacement Policy',
                'keywords' => 'return policy, replacements, refunds, order support, satisfaction guarantee',
                'description' => 'Read our Return & Replacement Policy to understand our terms for returning purchases, requesting replacements, and obtaining refunds.',
                'media' => null,
            ],
            [
                'page' => 'Terms & Condtions',
                'title' => 'Terms & Conditions of Service',
                'keywords' => 'terms of service, user agreement, legal policy, shopping rules',
                'description' => 'Read the official Terms & Conditions governing the use of the Leecommerce website and our online shopping services.',
                'media' => null,
            ],
            [
                'page' => 'Privacy Policy',
                'title' => 'Privacy Policy - Data Protection',
                'keywords' => 'privacy, security, data collection, user privacy, cookies',
                'description' => 'Understand how Leecommerce collects, protects, and uses your personal data. Read our commitment to keeping your information private and secure.',
                'media' => null,
            ],
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(
                ['page' => $p['page']],
                [
                    'title' => $p['title'],
                    'keywords' => $p['keywords'],
                    'description' => $p['description'],
                    'media' => $p['media'],
                ]
            );
        }
    }
}
