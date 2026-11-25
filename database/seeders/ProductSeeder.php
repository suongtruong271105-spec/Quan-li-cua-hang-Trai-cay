<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('product_sqlite')->table('fruits')->truncate();

        $now = now();

        DB::connection('product_sqlite')->table('fruits')->insert([
            [
                'name' => 'Xoài Cát Hòa Lộc',
                'price_per_kg' => 65000,
                'origin' => 'Tiền Giang',
                'description' => 'Xoài chín vàng ươm, thịt chắc, vị ngọt đậm và mùi thơm đặc trưng.',
                'image_url' => '/images/fruits/xoai.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sầu Riêng Ri6',
                'price_per_kg' => 145000,
                'origin' => 'Vĩnh Long',
                'description' => 'Cơm vàng hạt lép, vị béo ngậy, ngọt thanh, chín cây tự nhiên.',
                'image_url' => '/images/fruits/sau-rieng.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Táo Envy Mỹ',
                'price_per_kg' => 120000,
                'origin' => 'Mỹ',
                'description' => 'Quả to tròn, vỏ đỏ sọc, thịt giòn rụm và rất nhiều nước.',
                'image_url' => '/images/fruits/tao-my.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dâu Tây Đà Lạt',
                'price_per_kg' => 180000,
                'origin' => 'Lâm Đồng',
                'description' => 'Dâu giống Nhật trồng thủy canh, trái đỏ mọng, chua ngọt hài hòa.',
                'image_url' => '/images/fruits/dau-tay.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Nho Xanh Không Hạt Úc',
                'price_per_kg' => 165000,
                'origin' => 'Úc',
                'description' => 'Trái giòn, vỏ mỏng, vị ngọt mát không chát, thích hợp ăn tươi.',
                'image_url' => '/images/fruits/nho-xanh.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cam Sành Hàm Yên',
                'price_per_kg' => 35000,
                'origin' => 'Tuyên Quang',
                'description' => 'Cam mọng nước, giàu vitamin C, thích hợp vắt nước giải khát.',
                'image_url' => '/images/fruits/cam-sanh.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bưởi Da Xanh Bến Tre',
                'price_per_kg' => 55000,
                'origin' => 'Bến Tre',
                'description' => 'Tép bưởi hồng đỏ, ráo nước, vị ngọt thanh không he đắng.',
                'image_url' => '/images/fruits/buoi-da-xanh.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Cherry Đỏ Mỹ',
                'price_per_kg' => 290000,
                'origin' => 'Mỹ',
                'description' => 'Trái size lớn, cuống xanh tươi, thịt giòn ngọt đậm.',
                'image_url' => '/images/fruits/cherry.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kiwi Vàng New Zealand',
                'price_per_kg' => 150000,
                'origin' => 'New Zealand',
                'description' => 'Ruột vàng óng, vị ngọt dịu xen chút chua nhẹ, giàu dưỡng chất.',
                'image_url' => '/images/fruits/kiwi-vang.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thanh Long Ruột Đỏ',
                'price_per_kg' => 32000,
                'origin' => 'Bình Thuận',
                'description' => 'Trái to, vỏ đỏ tươi, ruột đỏ thẫm ngọt mát và nhiều chất xơ.',
                'image_url' => '/images/fruits/thanh-long.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dưa Hấu Không Hạt',
                'price_per_kg' => 25000,
                'origin' => 'Long An',
                'description' => 'Vỏ mỏng, ruột đỏ giòn, vị ngọt thanh, giải nhiệt mùa hè.',
                'image_url' => '/images/fruits/dua-hau.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Măng Cụt Lái Thiêu',
                'price_per_kg' => 85000,
                'origin' => 'Bình Dương',
                'description' => 'Vỏ mỏng, múi trắng muốt, vị chua ngọt thanh tao đặc trưng.',
                'image_url' => '/images/fruits/mang-cut.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chôm Chôm Thái',
                'price_per_kg' => 45000,
                'origin' => 'Đồng Nai',
                'description' => 'Cơm dày, giòn, tróc hạt, vị ngọt đậm và rất ít nước chảy.',
                'image_url' => '/images/fruits/chom-chom.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Lê Hàn Quốc',
                'price_per_kg' => 95000,
                'origin' => 'Hàn Quốc',
                'description' => 'Quả to, vỏ vàng sáng, thịt trắng giòn tan và cực kỳ mọng nước.',
                'image_url' => '/images/fruits/le-han-quoc.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Vải Thiều Lục Ngạn',
                'price_per_kg' => 50000,
                'origin' => 'Bắc Giang',
                'description' => 'Cùi dày trắng trong, hạt nhỏ, vị ngọt sắc và mùi thơm nồng.',
                'image_url' => '/images/fruits/vai-thieu.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}