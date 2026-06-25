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
                'content' => null,
            ],
            [
                'page' => 'About',
                'title' => 'About Us - Leecommerce',
                'keywords' => 'about us, history, team, mission, company values',
                'description' => 'Learn more about Leecommerce, our values, our mission to deliver quality, and our history of serving customers with dedication.',
                'media' => null,
                'content' => '<div class="about-section text-center py-5">
    <h2 class="display-5 font-weight-bold mb-3">About Leecommerce</h2>
    <p class="lead text-muted max-width-600 mx-auto">We are a premier online shopping destination dedicated to bringing you high-quality products, unmatched convenience, and exceptional customer support. Our mission is to make online shopping smooth, secure, and enjoyable for everyone.</p>
</div>
<div class="row mt-5">
    <div class="col-md-4 text-center mb-4">
        <div class="p-4 border rounded-3 bg-white h-100 shadow-sm">
            <div class="icon-container mb-3 text-primary" style="font-size: 2rem;"><i class="fas fa-gem"></i></div>
            <h4 class="font-weight-bold">Premium Quality</h4>
            <p class="text-muted small mb-0">We curate only the finest products from trusted manufacturers to ensure complete satisfaction.</p>
        </div>
    </div>
    <div class="col-md-4 text-center mb-4">
        <div class="p-4 border rounded-3 bg-white h-100 shadow-sm">
            <div class="icon-container mb-3 text-success" style="font-size: 2rem;"><i class="fas fa-shipping-fast"></i></div>
            <h4 class="font-weight-bold">Fast Delivery</h4>
            <p class="text-muted small mb-0">Our optimized logistics network ensures your orders reach your doorstep in record time.</p>
        </div>
    </div>
    <div class="col-md-4 text-center mb-4">
        <div class="p-4 border rounded-3 bg-white h-100 shadow-sm">
            <div class="icon-container mb-3 text-warning" style="font-size: 2rem;"><i class="fas fa-headset"></i></div>
            <h4 class="font-weight-bold">24/7 Support</h4>
            <p class="text-muted small mb-0">Our friendly customer support team is always available to assist with any questions or concerns.</p>
        </div>
    </div>
</div>',
            ],
            [
                'page' => 'Contact',
                'title' => 'Contact Us - Get in Touch',
                'keywords' => 'contact, support, customer service, email, phone support',
                'description' => 'Have questions or need assistance? Contact the Leecommerce support team. We are here to help you with your orders and inquiries.',
                'media' => null,
                'content' => '<div class="py-5">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h2 class="display-6 font-weight-bold mb-3">Get In Touch</h2>
            <p class="text-muted mb-4">Have questions about our products, orders, or shipping? Feel free to reach out to us. Our dedicated support team is ready to assist you.</p>
            <ul class="list-unstyled">
                <li class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3 text-primary" style="font-size: 1.2rem; width: 30px;"><i class="fas fa-envelope"></i></div>
                    <div>
                        <h6 class="mb-0 font-weight-bold">Email Support</h6>
                        <span class="text-muted small">sandeep198558@yahoo.com</span>
                    </div>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <div class="icon-box me-3 text-success" style="font-size: 1.2rem; width: 30px;"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <h6 class="mb-0 font-weight-bold">Phone Support</h6>
                        <span class="text-muted small">+91 9664588677</span>
                    </div>
                </li>
                <li class="d-flex align-items-center">
                    <div class="icon-box me-3 text-warning" style="font-size: 1.2rem; width: 30px;"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h6 class="mb-0 font-weight-bold">Headquarters</h6>
                        <span class="text-muted small">Leena IT Solutions, Mumbai, Maharashtra, India</span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-lg-6 mt-5 mt-lg-0">
            <div class="p-4 border rounded-4 bg-white shadow-sm">
                <h4 class="font-weight-bold mb-4">Send Us a Message</h4>
                <form>
                    <div class="mb-3">
                        <label class="form-label small font-weight-bold">Your Name</label>
                        <input type="text" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small font-weight-bold">Email Address</label>
                        <input type="email" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small font-weight-bold">Message</label>
                        <textarea class="form-control" rows="4" placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2.5">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>',
            ],
            [
                'page' => 'Cart',
                'title' => 'Shopping Cart - Leecommerce',
                'keywords' => 'cart, shopping cart, checkout, select products',
                'description' => 'Review the items in your shopping cart, adjust quantities, and proceed to checkout to secure your purchases.',
                'media' => null,
                'content' => null,
            ],
            [
                'page' => 'Wishlist',
                'title' => 'My Wishlist - Saved Items',
                'keywords' => 'wishlist, saved products, favorites, shopping list',
                'description' => 'View and manage your saved products. Keep track of items you love and buy them whenever you are ready.',
                'media' => null,
                'content' => null,
            ],
            [
                'page' => 'Checkout',
                'title' => 'Secure Checkout - Finalize Order',
                'keywords' => 'checkout, payment, shipping address, place order',
                'description' => 'Complete your purchase securely. Enter your shipping address, choose your payment method, and review your final order details.',
                'media' => null,
                'content' => null,
            ],
            [
                'page' => 'Search',
                'title' => 'Product Search Results - Leecommerce',
                'keywords' => 'search, find products, catalog lookup, search catalog',
                'description' => 'Browse search results for your query. Find exactly what you are looking for in our extensive product catalog.',
                'media' => null,
                'content' => null,
            ],
            [
                'page' => 'Return & Replace',
                'title' => 'Return & Replacement Policy',
                'keywords' => 'return policy, replacements, refunds, order support, satisfaction guarantee',
                'description' => 'Read our Return & Replacement Policy to understand our terms for returning purchases, requesting replacements, and obtaining refunds.',
                'media' => null,
                'content' => '<div class="py-5">
    <h2 class="display-6 font-weight-bold mb-4">Return & Replacement Policy</h2>
    <p class="lead text-muted">We want you to be completely satisfied with your purchase. If you receive a product that is damaged, defective, or not as described, we offer a hassle-free return and replacement policy.</p>
    
    <hr class="my-4">
    
    <h4 class="font-weight-bold mb-3">1. Eligibility for Returns & Replacements</h4>
    <p>To qualify for a return or replacement, please ensure that:</p>
    <ul>
        <li>The request is initiated within <strong>7 days</strong> of receiving the delivery.</li>
        <li>The product is unused, unwashed, and in its original packaging.</li>
        <li>All tags, labels, user manuals, and accessories are intact.</li>
    </ul>

    <h4 class="font-weight-bold mb-3 mt-4">2. Non-Returnable Items</h4>
    <p>Certain product categories cannot be returned or replaced due to hygiene and security reasons, including personal care items, innerwear, and customized goods, unless they are delivered damaged or defective.</p>

    <h4 class="font-weight-bold mb-3 mt-4">3. How to Initiate a Request</h4>
    <p>To request a return or replacement, please contact our support desk at <strong>sandeep198558@yahoo.com</strong> with your order ID, photos of the product, and a brief description of the issue. Our team will review the request and schedule a reverse pickup within 48 hours.</p>
</div>',
            ],
            [
                'page' => 'Terms & Condtions',
                'title' => 'Terms & Conditions of Service',
                'keywords' => 'terms of service, user agreement, legal policy, shopping rules',
                'description' => 'Read the official Terms & Conditions governing the use of the Leecommerce website and our online shopping services.',
                'media' => null,
                'content' => '<div class="py-5">
    <h2 class="display-6 font-weight-bold mb-4">Terms & Conditions of Service</h2>
    <p class="lead text-muted">Welcome to Leecommerce. These terms and conditions govern your use of our website and services. By accessing or shopping on our platform, you agree to comply with and be bound by the following terms.</p>
    
    <hr class="my-4">
    
    <h4 class="font-weight-bold mb-3">1. Account Responsibility</h4>
    <p>When you create an account on our website, you are responsible for maintaining the confidentiality of your credentials and restricting unauthorized access to your account. You agree to accept responsibility for all activities that occur under your account.</p>

    <h4 class="font-weight-bold mb-3 mt-4">2. Product Specifications & Pricing</h4>
    <p>We strive to display our products, specifications, and prices as accurately as possible. However, typographical errors or technical pricing glitches may occur. In such events, we reserve the right to cancel any orders placed for products listed at incorrect prices.</p>

    <h4 class="font-weight-bold mb-3 mt-4">3. Intellectual Property</h4>
    <p>All content included on this site, such as text, graphics, logos, icons, and software, is the property of Leecommerce or its content suppliers and is protected by international copyright laws.</p>
</div>',
            ],
            [
                'page' => 'Privacy Policy',
                'title' => 'Privacy Policy - Data Protection',
                'keywords' => 'privacy, security, data collection, user privacy, cookies',
                'description' => 'Understand how Leecommerce collects, protects, and uses your personal data. Read our commitment to keeping your information private and secure.',
                'media' => null,
                'content' => '<div class="py-5">
    <h2 class="display-6 font-weight-bold mb-4">Privacy Policy</h2>
    <p class="lead text-muted">At Leecommerce, we are committed to protecting your privacy and securing your personal data. This privacy policy explains how we collect, use, and safeguard your information when you visit or shop on our platform.</p>
    
    <hr class="my-4">
    
    <h4 class="font-weight-bold mb-3">1. Information We Collect</h4>
    <p>We collect personal information that you provide directly to us when creating an account, placing an order, or contacting support. This includes your name, email address, shipping address, mobile number, and payment details.</p>

    <h4 class="font-weight-bold mb-3 mt-4">2. How We Use Your Information</h4>
    <p>We use the collected information to process transactions, deliver shipments, send order confirmations, respond to customer inquiries, and personalize your overall shopping experience.</p>

    <h4 class="font-weight-bold mb-3 mt-4">3. Data Security & Protection</h4>
    <p>We implement advanced technical and organizational security measures, including SSL encryption and secure databases, to protect your personal data from unauthorized access, alteration, disclosure, or destruction.</p>
</div>',
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
                    'content' => $p['content'],
                ]
            );
        }
    }
}
