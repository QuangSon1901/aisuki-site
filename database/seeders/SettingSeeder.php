<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Translation;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General settings
            [
                'key' => 'site_name',
                'value' => 'AISUKI',
                'group' => 'general',
                'is_translatable' => false,
            ],
            [
                'key' => 'theme_mode',
                'value' => 'light',
                'group' => 'general',
                'is_translatable' => false,
            ],
            [
                'key' => 'logo',
                'value' => 'uploads/logo.png',
                'group' => 'general',
                'is_translatable' => false,
            ],
            [
                'key' => 'currency',
                'value' => '€',
                'group' => 'general',
                'is_translatable' => false,
            ],
            
            // Contact settings
            [
                'key' => 'phone',
                'value' => '+49 89 12345678',
                'group' => 'contact',
                'is_translatable' => false,
            ],
            [
                'key' => 'email',
                'value' => 'info@aisuki.de',
                'group' => 'contact',
                'is_translatable' => false,
            ],
            [
                'key' => 'address',
                'value' => 'Leopoldstraße 123, 80802 Munich, Germany',
                'group' => 'contact',
                'is_translatable' => true,
            ],
            [
                'key' => 'opening_hours',
                'value' => '10:00 - 22:00 (Mon-Sun)',
                'group' => 'contact',
                'is_translatable' => true,
            ],
            [
                'key' => 'google_maps_iframe',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2661.568533668056!2d11.576603776692168!3d48.15362284666392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x479e75eb52a19259%3A0x1e31a9efc1aaaed3!2sLeopoldstra%C3%9Fe%2C%2080802%20M%C3%BCnchen%2C%20Germany!5e0!3m2!1sen!2sus!4v1681484909322!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'group' => 'contact',
                'is_translatable' => false,
            ],
            
            // Social media links
            [
                'key' => 'facebook',
                'value' => 'https://facebook.com/aisuki',
                'group' => 'social',
                'is_translatable' => false,
            ],
            [
                'key' => 'instagram',
                'value' => 'https://instagram.com/aisuki',
                'group' => 'social',
                'is_translatable' => false,
            ],
            [
                'key' => 'twitter',
                'value' => 'https://twitter.com/aisuki',
                'group' => 'social',
                'is_translatable' => false,
            ],
            [
                'key' => 'whatsapp',
                'value' => '+4989123456789',
                'group' => 'social',
                'is_translatable' => false,
            ],
            [
                'key' => 'youtube',
                'value' => 'https://youtube.com/aisuki',
                'group' => 'social',
                'is_translatable' => false,
            ],
            
            // SEO settings
            [
                'key' => 'meta_title',
                'value' => 'AISUKI - Japanese Restaurant',
                'group' => 'seo',
                'is_translatable' => true,
            ],
            [
                'key' => 'meta_description',
                'value' => 'Authentic Japanese restaurant in Munich with traditional flavors and cozy atmosphere.',
                'group' => 'seo',
                'is_translatable' => true,
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'japanese restaurant, sushi, munich, ramen, authentic japanese food',
                'group' => 'seo',
                'is_translatable' => true,
            ],
            [
                'key' => 'delivery_fee',
                'value' => '2.50',
                'group' => 'order',
                'is_translatable' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Add translations for translatable settings
        $translations = [
            // Address translations
            [
                'group' => 'settings',
                'key' => 'address',
                'language_code' => 'de',
                'value' => 'Leopoldstraße 123, 80802 München, Deutschland',
            ],
            
            // Opening hours translations
            [
                'group' => 'settings',
                'key' => 'opening_hours',
                'language_code' => 'de',
                'value' => '10:00 - 22:00 (Mo-So)',
            ],
            
            // SEO translations
            [
                'group' => 'settings',
                'key' => 'meta_title',
                'language_code' => 'de',
                'value' => 'AISUKI - Japanisches Restaurant',
            ],
            [
                'group' => 'settings',
                'key' => 'meta_description',
                'language_code' => 'de',
                'value' => 'Authentisches japanisches Restaurant in München mit traditionellen Aromen und gemütlicher Atmosphäre.',
            ],
            [
                'group' => 'settings',
                'key' => 'meta_keywords',
                'language_code' => 'de',
                'value' => 'japanisches restaurant, sushi, münchen, ramen, authentisches japanisches essen',
            ],
        ];

        foreach ($translations as $translation) {
            Translation::updateOrCreate(
                [
                    'group' => $translation['group'],
                    'key' => $translation['key'],
                    'language_code' => $translation['language_code'],
                ],
                $translation
            );
        }
    }
}