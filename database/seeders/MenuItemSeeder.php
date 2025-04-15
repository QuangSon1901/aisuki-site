<?php
namespace Database\Seeders;

use App\Models\Language;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
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
        
        // Lấy category IDs cho Ki-Lunch
        $kiLunchCategoryEn = MenuCategory::where('slug', 'ki-lunch')
            ->where('language_id', $englishLanguage->id)
            ->first();
            
        $kiLunchCategoryDe = MenuCategory::where('slug', 'ki-lunch')
            ->where('language_id', $germanLanguage->id)
            ->first();
            
        if (!$kiLunchCategoryEn || !$kiLunchCategoryDe) {
            echo "Cần phải chạy MenuCategorySeeder trước.\n";
            return;
        }
        
        $menuItems = [
            // K1 - Ga Curi
            [
                'mass_id' => 1,
                'code' => 'K1',
                'price' => 7.50,
                'image' => 'uploads/ga_curi.jpg',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Chicken Curry',
                        'description' => 'Chicken in Red Curry Sauce',
                        'category_id' => $kiLunchCategoryEn->id,
                    ],
                    'de' => [
                        'name' => 'Ga Curi',
                        'description' => 'Hühnerfleisch in Rot Curry Sauce',
                        'category_id' => $kiLunchCategoryDe->id,
                    ],
                ],
            ],
            
            // K2 - Ga Chua Ngot
            [
                'mass_id' => 2,
                'code' => 'K2',
                'price' => 7.50,
                'image' => 'uploads/ga_chua_ngot.jpg',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Sweet & Sour Chicken',
                        'description' => 'Chicken in Sweet and Sour Sauce',
                        'category_id' => $kiLunchCategoryEn->id,
                    ],
                    'de' => [
                        'name' => 'Ga Chua Ngot',
                        'description' => 'Hühnerfleisch paniert in Süß Sauer Sauce',
                        'category_id' => $kiLunchCategoryDe->id,
                    ],
                ],
            ],
            
            // K3 - My Ga
            [
                'mass_id' => 3,
                'code' => 'K3',
                'price' => 7.50,
                'image' => 'uploads/my_ga.jpg',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Chicken Noodles',
                        'description' => 'Fried Noodles with Chicken',
                        'category_id' => $kiLunchCategoryEn->id,
                    ],
                    'de' => [
                        'name' => 'My Ga',
                        'description' => 'gebratene Nudeln mit Hühnerfleisch',
                        'category_id' => $kiLunchCategoryDe->id,
                    ],
                ],
            ],
            
            // K4 - Toriterdon
            [
                'mass_id' => 4,
                'code' => 'K4',
                'price' => 7.50,
                'image' => 'uploads/toridon.jpg',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
                'translations' => [
                    'en' => [
                        'name' => 'Teriyaki Chicken',
                        'description' => 'Chicken in Teriyaki Sauce',
                        'category_id' => $kiLunchCategoryEn->id,
                    ],
                    'de' => [
                        'name' => 'Toriterdon',
                        'description' => 'Hühnerfleisch in Teriyaki Sauce',
                        'category_id' => $kiLunchCategoryDe->id,
                    ],
                ],
            ],
            
            // K5 - Pho Bo
            [
                'mass_id' => 5,
                'code' => 'K5',
                'price' => 8.50,
                'image' => 'uploads/pho_bo.jpg',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => true,
                'translations' => [
                    'en' => [
                        'name' => 'Beef Pho',
                        'description' => 'Rice noodle soup with beef',
                        'category_id' => $kiLunchCategoryEn->id,
                    ],
                    'de' => [
                        'name' => 'Pho Bo',
                        'description' => 'Reisnudeln-Suppe mit Rindfleisch',
                        'category_id' => $kiLunchCategoryDe->id,
                    ],
                ],
            ],
            
            // Thêm items K6-K10 tương tự
        ];
        
        foreach ($menuItems as $item) {
            $massId = $item['mass_id'];
            
            // Tạo phiên bản tiếng Anh
            MenuItem::create([
                'language_id' => $englishLanguage->id,
                'mass_id' => $massId,
                'category_id' => $item['translations']['en']['category_id'],
                'code' => $item['code'],
                'name' => $item['translations']['en']['name'],
                'description' => $item['translations']['en']['description'],
                'price' => $item['price'],
                'image' => $item['image'],
                'is_active' => $item['is_active'],
                'is_featured' => $item['is_featured'],
                'sort_order' => $item['sort_order'],
            ]);
            
            // Tạo phiên bản tiếng Đức
            MenuItem::create([
                'language_id' => $germanLanguage->id,
                'mass_id' => $massId,
                'category_id' => $item['translations']['de']['category_id'],
                'code' => $item['code'],
                'name' => $item['translations']['de']['name'],
                'description' => $item['translations']['de']['description'],
                'price' => $item['price'],
                'image' => $item['image'],
                'is_active' => $item['is_active'],
                'is_featured' => $item['is_featured'],
                'sort_order' => $item['sort_order'],
            ]);
        }
    }
}