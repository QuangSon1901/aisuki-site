<?php
namespace Database\Seeders;

use App\Models\Language;
use App\Models\MenuCategory;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    public function run()
    {
        // Lấy language IDs
        $englishLanguage = Language::where('code', 'en')->first();
        $germanLanguage = Language::where('code', 'de')->first();
        
        if (!$englishLanguage || !$germanLanguage) {
            echo "Cần phải chạy LanguageSeeder trước.\n";
            return;
        }
        
        $categories = [
            // Ki-Lunch category
            [
                'mass_id' => 1,
                'slug' => 'ki-lunch',
                'name' => 'Ki-Lunch',
                'image' => 'uploads/ki-lunch.jpg',
                'sort_order' => 1,
                'is_active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Ki-Lunch',
                        'slug' => 'ki-lunch',
                    ],
                    'de' => [
                        'name' => 'Ki-Lunch',
                        'slug' => 'ki-lunch',
                    ],
                ],
            ],
            
            // Sushi category
            [
                'mass_id' => 2,
                'slug' => 'sushi',
                'name' => 'Sushi',
                'image' => 'uploads/sushi.jpg',
                'sort_order' => 2,
                'is_active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Sushi',
                        'slug' => 'sushi',
                    ],
                    'de' => [
                        'name' => 'Sushi',
                        'slug' => 'sushi',
                    ],
                ],
            ],
            
            // Ramen category
            [
                'mass_id' => 3,
                'slug' => 'ramen',
                'name' => 'Ramen & Noodles',
                'image' => 'uploads/ramen.jpg',
                'sort_order' => 3,
                'is_active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Ramen & Noodles',
                        'slug' => 'ramen',
                    ],
                    'de' => [
                        'name' => 'Ramen & Nudeln',
                        'slug' => 'ramen',
                    ],
                ],
            ],
            
            // Tempura category
            [
                'mass_id' => 4,
                'slug' => 'tempura',
                'name' => 'Tempura',
                'image' => 'uploads/tempura.jpg',
                'sort_order' => 4,
                'is_active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Tempura',
                        'slug' => 'tempura',
                    ],
                    'de' => [
                        'name' => 'Tempura',
                        'slug' => 'tempura',
                    ],
                ],
            ],
            
            // Appetizers category
            [
                'mass_id' => 5,
                'slug' => 'appetizers',
                'name' => 'Appetizers',
                'image' => 'uploads/appetizers.jpg',
                'sort_order' => 5,
                'is_active' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Appetizers',
                        'slug' => 'appetizers',
                    ],
                    'de' => [
                        'name' => 'Vorspeisen',
                        'slug' => 'vorspeisen',
                    ],
                ],
            ],
        ];

        foreach ($categories as $category) {
            $massId = $category['mass_id'];
            
            // Tạo phiên bản tiếng Anh
            MenuCategory::create([
                'language_id' => $englishLanguage->id,
                'mass_id' => $massId,
                'slug' => $category['translations']['en']['slug'],
                'name' => $category['translations']['en']['name'],
                'image' => $category['image'],
                'sort_order' => $category['sort_order'],
                'is_active' => $category['is_active'],
            ]);
            
            // Tạo phiên bản tiếng Đức
            MenuCategory::create([
                'language_id' => $germanLanguage->id,
                'mass_id' => $massId,
                'slug' => $category['translations']['de']['slug'],
                'name' => $category['translations']['de']['name'],
                'image' => $category['image'],
                'sort_order' => $category['sort_order'],
                'is_active' => $category['is_active'],
            ]);
        }
    }
}