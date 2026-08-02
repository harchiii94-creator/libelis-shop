<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Beras Premium 5kg',
                'category' => 'sembako',
                'description' => 'Beras premium untuk kebutuhan dapur sehari-hari, wangi dan pulen.',
                'image_url' => 'https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?w=600&q=80',
                'price' => 85000,
                'is_best_seller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Minyak Goreng Botol 2L',
                'category' => 'sembako',
                'description' => 'Minyak goreng kemasan praktis, cocok untuk masakan harian.',
                'image_url' => 'https://images.unsplash.com/photo-1553621042-f6e147245754?w=600&q=80',
                'price' => 32000,
                'is_best_seller' => true,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Gula Pasir 1kg',
                'category' => 'sembako',
                'description' => 'Gula pasir berkualitas untuk memasak dan minuman manis.',
                'image_url' => 'https://images.unsplash.com/photo-1541534741688-6078e3a0717d?w=600&q=80',
                'price' => 14500,
                'is_best_seller' => false,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Telur Lokal 1 Kg',
                'category' => 'sembako',
                'description' => 'Telur segar lokal, cocok untuk sarapan dan kebutuhan masak sehari-hari.',
                'image_url' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&q=80',
                'price' => 27000,
                'is_best_seller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Susu UHT Cokelat 1L',
                'category' => 'sembako',
                'description' => 'Susu UHT siap minum, praktis untuk anak dan keluarga.',
                'image_url' => 'https://images.unsplash.com/photo-1516042172472-2b97f3d1a95f?w=600&q=80',
                'price' => 19000,
                'is_best_seller' => false,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Sabun Cuci Piring',
                'category' => 'kebutuhan rumah',
                'description' => 'Sabun cuci piring lembut, bersihkan noda minyak tanpa ribet.',
                'image_url' => 'https://images.unsplash.com/photo-1512990452136-691d1b0e6f28?w=600&q=80',
                'price' => 12000,
                'is_best_seller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Tisu Dapur',
                'category' => 'kebutuhan rumah',
                'description' => 'Tisu dapur tebal dan cepat serap, siap untuk bersih-bersih rumah.',
                'image_url' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c16?w=600&q=80',
                'price' => 10500,
                'is_best_seller' => false,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Sapu Lidi',
                'category' => 'kebutuhan rumah',
                'description' => 'Sapu lidi kuat untuk membersihkan halaman dan sudut rumah.',
                'image_url' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=600&q=80',
                'price' => 15000,
                'is_best_seller' => false,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Pewangi Pakaian Botol',
                'category' => 'kebutuhan rumah',
                'description' => 'Pewangi pakaian aroma segar untuk semua jenis kain.',
                'image_url' => 'https://images.unsplash.com/photo-1520975912127-6b67e6d95e57?w=600&q=80',
                'price' => 22000,
                'is_best_seller' => true,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Kaos Polos Pria',
                'category' => 'pakaian',
                'description' => 'Kaos polos nyaman dipakai sehari-hari, bahan katun adem.',
                'image_url' => 'https://images.unsplash.com/photo-1521334884684-d80222895322?w=600&q=80',
                'price' => 65000,
                'is_best_seller' => true,
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Celana Panjang Jeans',
                'category' => 'pakaian',
                'description' => 'Celana jeans modern dengan potongan nyaman untuk semua suasana.',
                'image_url' => 'https://images.unsplash.com/photo-1523348837702-0fa86c1913ff?w=600&q=80',
                'price' => 125000,
                'is_best_seller' => false,
                'is_new_arrival' => true,
            ],
            [
                'name' => 'Jaket Parasut Ringan',
                'category' => 'pakaian',
                'description' => 'Jaket parasut ringan, cocok untuk aktivitas outdoor dan hujan ringan.',
                'image_url' => 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?w=600&q=80',
                'price' => 145000,
                'is_best_seller' => false,
                'is_new_arrival' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
