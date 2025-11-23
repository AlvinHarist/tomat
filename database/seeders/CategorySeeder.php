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
                'description' => 'Produk elektronik dan teknologi',
                'parent_id' => null,
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Smartphone',
                        'description' => 'Ponsel pintar dan aksesorisnya',
                        'sort_order' => 1,
                        'children' => [
                            ['name' => 'Apple', 'sort_order' => 1],
                            ['name' => 'Samsung', 'sort_order' => 2],
                            ['name' => 'Xiaomi', 'sort_order' => 3],
                        ]
                    ],
                    [
                        'name' => 'Laptop & Komputer',
                        'description' => 'Laptop, desktop, dan komponen',
                        'sort_order' => 2,
                        'children' => [
                            ['name' => 'Gaming', 'sort_order' => 1],
                            ['name' => 'Office', 'sort_order' => 2],
                        ]
                    ],
                    [
                        'name' => 'Aksesori',
                        'description' => 'Aksesori elektronik',
                        'sort_order' => 3,
                    ]
                ]
            ],
            // Kategori level 1
            [
                'name' => 'Fashion',
                'description' => 'Pakaian dan mode',
                'parent_id' => null,
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Pakaian Pria',
                        'description' => 'Koleksi pakaian pria',
                        'sort_order' => 1,
                        'children' => [
                            ['name' => 'Kemeja', 'sort_order' => 1],
                            ['name' => 'Celana', 'sort_order' => 2],
                            ['name' => 'Jaket', 'sort_order' => 3],
                        ]
                    ],
                    [
                        'name' => 'Pakaian Wanita',
                        'description' => 'Koleksi pakaian wanita',
                        'sort_order' => 2,
                        'children' => [
                            ['name' => 'Dress', 'sort_order' => 1],
                            ['name' => 'Blouse', 'sort_order' => 2],
                            ['name' => 'Rok', 'sort_order' => 3],
                        ]
                    ],
                    [
                        'name' => 'Sepatu',
                        'description' => 'Koleksi sepatu',
                        'sort_order' => 3,
                    ]
                ]
            ],
            // Kategori level 1
            [
                'name' => 'Rumah Tangga',
                'description' => 'Kebutuhan rumah tangga',
                'parent_id' => null,
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Dapur',
                        'description' => 'Peralatan dapur',
                        'sort_order' => 1,
                        'children' => [
                            ['name' => 'Panci & Wajan', 'sort_order' => 1],
                            ['name' => 'Peralatan Masak', 'sort_order' => 2],
                        ]
                    ],
                    [
                        'name' => 'Kamar Tidur',
                        'description' => 'Peralatan kamar tidur',
                        'sort_order' => 2,
                    ]
                ]
            ],
            // Kategori level 1
            [
                'name' => 'Olahraga & Outdoor',
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