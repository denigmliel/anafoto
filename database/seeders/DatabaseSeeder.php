<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@anafotocopy.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $products = [
            [
                'name' => 'Kertas HVS A4 70gr (Rim)',
                'unit' => 'rim',
                'price' => 35000,
                'stock' => 50,
            ],
            [
                'name' => 'Kertas HVS F4 70gr (Rim)',
                'unit' => 'rim',
                'price' => 40000,
                'stock' => 30,
            ],
            [
                'name' => 'Pulpen Standard Hitam',
                'unit' => 'pcs',
                'price' => 2000,
                'stock' => 100,
            ],
            [
                'name' => 'Pensil 2B',
                'unit' => 'pcs',
                'price' => 2500,
                'stock' => 80,
            ],
            [
                'name' => 'Fotocopy A4 BW',
                'unit' => 'lembar',
                'price' => 200,
                'stock' => 9999,
            ],
            [
                'name' => 'Fotocopy A4 Color',
                'unit' => 'lembar',
                'price' => 1000,
                'stock' => 9999,
            ],
            [
                'name' => 'Print A4 BW',
                'unit' => 'lembar',
                'price' => 500,
                'stock' => 9999,
            ],
            [
                'name' => 'Print A4 Color',
                'unit' => 'lembar',
                'price' => 1500,
                'stock' => 9999,
            ],
            [
                'name' => 'Jilid Spiral',
                'unit' => 'pcs',
                'price' => 5000,
                'stock' => 9999,
            ],
            [
                'name' => 'Laminating A4',
                'unit' => 'lembar',
                'price' => 3000,
                'stock' => 9999,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $settings = [
            ['key_name' => 'store_name', 'value' => 'ANA FOTOCOPY', 'description' => 'Nama Toko'],
            ['key_name' => 'store_address', 'value' => 'Jl. Benda Raya, Maruga Rt.005/04 Kel. Serua, Kec. Ciputat, Tangerang Selatan', 'description' => 'Alamat Toko'],
            ['key_name' => 'store_phone', 'value' => '0823-1094-6322', 'description' => 'Telepon Toko'],
            ['key_name' => 'tax_percentage', 'value' => '0', 'description' => 'Persentase Pajak (%)'],
            ['key_name' => 'receipt_footer', 'value' => 'Terima kasih atas kunjungan Anda!', 'description' => 'Footer Struk'],
            ['key_name' => 'currency', 'value' => 'Rp', 'description' => 'Simbol Mata Uang'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
