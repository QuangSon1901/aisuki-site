<?php
namespace Database\Seeders;

use App\Models\AddonItem;
use App\Models\Language;
use Illuminate\Database\Seeder;

class AddonItemSeeder extends Seeder
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
        
        $addonItems = [
            // Extra Sauce
            [
                'mass_id' => 1,
                'price' => 2.00,
                'sort_order' => 1,
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Extra Sauce'],
                    'de' => ['name' => 'Extra Sauce'],
                ],
            ],
            
            // Pickled Ginger
            [
                'mass_id' => 2,
                'price' => 1.50,
                'sort_order' => 2,
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Pickled Ginger'],
                    'de' => ['name' => 'Eingelegter Ingwer'],
                ],
            ],
            
            // Extra Wasabi
            [
                'mass_id' => 3,
                'price' => 1.00,
                'sort_order' => 3,
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Extra Wasabi'],
                    'de' => ['name' => 'Extra Wasabi'],
                ],
            ],
            
            // Soy Sauce
            [
                'mass_id' => 4,
                'price' => 1.00,
                'sort_order' => 4,
                'is_active' => true,
                'translations' => [
                    'en' => ['name' => 'Soy Sauce'],
                    'de' => ['name' => 'Sojasauce'],
                ],
            ],
        ];
        
        foreach ($addonItems as $item) {
            $massId = $item['mass_id'];
            
            // Tạo phiên bản tiếng Anh
            AddonItem::create([
                'language_id' => $englishLanguage->id,
                'mass_id' => $massId,
                'name' => $item['translations']['en']['name'],
                'price' => $item['price'],
                'is_active' => $item['is_active'],
                'sort_order' => $item['sort_order'],
            ]);
            
            // Tạo phiên bản tiếng Đức
            AddonItem::create([
                'language_id' => $germanLanguage->id,
                'mass_id' => $massId,
                'name' => $item['translations']['de']['name'],
                'price' => $item['price'],
                'is_active' => $item['is_active'],
                'sort_order' => $item['sort_order'],
            ]);
        }
    }
}