<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultThemeColors = [
            "primary" => "#2d208d",
            "secondary" => "#2f6a60",
            "success" => "#00b315",
            "info" => "#00d5ff",
            "warning" => "#f1b83b",
            "danger" => "#db0000",
            "light" => "#ededed",
            "dark" => "#000000"
        ];

        $settings = [
            // General & Branding
            ['key' => 'App Name', 'val' => 'LeeCommerce'],
            ['key' => 'Show App Name', 'val' => 'Enable'],
            ['key' => 'Tag Line', 'val' => 'Your Ultimate Shopping Destination'],
            ['key' => 'Show Tag Line', 'val' => 'Disable'],
            ['key' => 'Logo', 'val' => ''],
            ['key' => 'Show Logo', 'val' => 'Disable'],
            ['key' => 'Theme Color', 'val' => json_encode($defaultThemeColors)],

            // Store Policies & Limits
            ['key' => 'Buy Quantity', 'val' => '10'],
            ['key' => 'Minimum Order Amount', 'val' => '100'],
            ['key' => 'Order Cancellation', 'val' => 'Disable'],
            ['key' => 'Order Replacement', 'val' => 'Disable'],
            ['key' => 'Replacement Hours', 'val' => '24'],
            ['key' => 'Return Hours', 'val' => '24'],

            // Logistics & Delivery
            ['key' => 'Address', 'val' => 'Leena IT Solutions, Mumbai, Maharashtra, India'],
            ['key' => 'Delivery Timing', 'val' => '5 to 7 Days'],
            ['key' => 'Delivery Charges', 'val' => '100'],
            ['key' => 'Free Delivery Amount', 'val' => '500'],

            // Payments & Integrations
            ['key' => 'COD', 'val' => 'Enable'],
            ['key' => 'Online payment', 'val' => 'Enable'],
            ['key' => 'Razorpay API Key', 'val' => 'rzp_test_key_here'],
            ['key' => 'Razorpay API Secret', 'val' => 'rzp_test_secret_here'],
            ['key' => 'Google Map API Key', 'val' => '000'],

            // Contact & Social Links
            ['key' => 'Support Email', 'val' => 'sandeep198558@yahoo.com'],
            ['key' => 'Support Mobile', 'val' => '9664588677'],
            ['key' => 'WhatsApp', 'val' => '9664588677'],
            ['key' => 'Facebook', 'val' => 'https://facebook.com'],
            ['key' => 'Instagram', 'val' => 'https://instagram.com'],
            ['key' => 'Youtube', 'val' => 'https://youtube.com'],

            // Welcome Content
            ['key' => 'Welcome Title', 'val' => 'Welcome to Leecommerce'],
            ['key' => 'Welcome Call', 'val' => 'Shop the Best Deals Online'],
            ['key' => 'Welcome Note', 'val' => 'Explore our wide range of premium products, curated just for you. From fashion to electronics, we bring the best quality items straight to your doorstep with fast, secure delivery and 24/7 customer support.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['val' => $setting['val']]
            );
        }
    }
}
