<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $baseTime = Carbon::parse('2025-10-02 09:00:00');

        $categories = [
            // Kategori level 1
            [
                'name' => 'Elektronik',
                'image' => '/images/categories/pan.webp',
                'description' => 'Produk elektronik dan teknologi',
                'parent_id' => null,
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Smartphone',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Ponsel pintar dan aksesorisnya',
                        'sort_order' => 1,
                        'children' => [
                            ['name' => 'Apple', 'sort_order' => 1, 'image' => '/images/categories/apple.png'],
                            ['name' => 'Samsung', 'sort_order' => 2, 'image' => '/images/categories/samsung.png'],
                            ['name' => 'Xiaomi', 'sort_order' => 3, 'image' => '/images/categories/xiaomi.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Laptop & Komputer',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Laptop, desktop, dan komponen',
                        'sort_order' => 2,
                        'children' => [
                            ['name' => 'Gaming', 'sort_order' => 1, 'image' => '/images/categories/gaming.png'],
                            ['name' => 'Office', 'sort_order' => 2, 'image' => '/images/categories/office.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Aksesori',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Aksesori elektronik',
                        'sort_order' => 3,
                    ]
                ]
            ],
            // Kategori level 1
            [
                'name' => 'Fashion',
                'image' => '/images/categories/pan.webp',
                'description' => 'Pakaian dan mode',
                'parent_id' => null,
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Pakaian Pria',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Koleksi pakaian pria',
                        'sort_order' => 1,
                        'children' => [
                            ['name' => 'Kemeja', 'sort_order' => 1, 'image' => '/images/categories/kemeja.jpg'],
                            ['name' => 'Celana', 'sort_order' => 2, 'image' => '/images/categories/celana.webp'],
                            ['name' => 'Jaket', 'sort_order' => 3, 'image' => '/images/categories/jaket.jpg'],
                        ]
                    ],
                    [
                        'name' => 'Pakaian Wanita',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Koleksi pakaian wanita',
                        'sort_order' => 2,
                        'children' => [
                            ['name' => 'Dress', 'sort_order' => 1, 'image' => '/images/categories/dress.jpg'],
                            ['name' => 'Blouse', 'sort_order' => 2, 'image' => '/images/categories/blouse.jpg'],
                            ['name' => 'Rok', 'sort_order' => 3, 'image' => '/images/categories/rok.webp'],
                        ]
                    ],
                    [
                        'name' => 'Sepatu',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Koleksi sepatu',
                        'sort_order' => 3,
                    ]
                ]
            ],
            // Kategori level 1
            [
                'name' => 'Rumah Tangga',
                'image' => '/images/categories/pan.webp',
                'description' => 'Kebutuhan rumah tangga',
                'parent_id' => null,
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Dapur',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Peralatan dapur',
                        'sort_order' => 1,
                        'children' => [
                            ['name' => 'Panci & Wajan', 'sort_order' => 1, 'image' => '/images/categories/pan.webp'],
                            ['name' => 'Peralatan Masak', 'sort_order' => 2, 'image' => '/images/categories/knife.webp'],
                        ]
                    ],
                    [
                        'name' => 'Kamar Tidur',
                        'image' => '/images/categories/pan.webp',
                        'description' => 'Peralatan kamar tidur',
                        'sort_order' => 2,
                    ]
                ]
            ],
            // Kategori level 1
            [
                'name' => 'Olahraga & Outdoor',
                'image' => '/images/categories/pan.webp',
                'description' => 'Perlengkapan olahraga',
                'parent_id' => null,
                'sort_order' => 4,
            ]
        ];

        $this->insertCategories($categories, $baseTime);
    }

    private function insertCategories($categories, $baseTime, $parentId = null, $minuteOffset = 0)
    {
        foreach ($categories as $index => $category) {
            $categoryData = [
                'id' => (string) Str::uuid(),
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'image' => $category['image'],
                'description' => $category['description'] ?? null,
                'parent_id' => $parentId,
                'sort_order' => $category['sort_order'] ?? 0,
                'is_active' => true,
                'created_at' => $baseTime->copy()->addMinutes($minuteOffset + $index),
                'updated_at' => $baseTime->copy()->addMinutes($minuteOffset + $index),
            ];

            $categoryId = $categoryData['id'];
            DB::table('categories')->insert($categoryData);

            // Insert children jika ada
            if (isset($category['children']) && !empty($category['children'])) {
                $this->insertCategories(
                    $category['children'],
                    $baseTime,
                    $categoryId,
                    $minuteOffset + count($categories) * 10
                );
            }
        }
    }
}