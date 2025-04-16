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
                'value' => 'AISUKI - Asian Cooking - Sushi & Bowls',
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
                'key' => 'site_logo',
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
                'value' => '+49 176 57904182',
                'group' => 'contact',
                'is_translatable' => false,
            ],
            [
                'key' => 'email',
                'value' => 'contact@aisuki.de',
                'group' => 'contact',
                'is_translatable' => false,
            ],
            [
                'key' => 'address',
                'value' => 'Korbinianstraße 60 (Ecke Frankfurter Ring 12). 80807 München',
                'group' => 'contact',
                'is_translatable' => true,
            ],
            [
                'key' => 'opening_hours',
                'value' => '11:00 - 14:30 (Mon-Fri)',
                'group' => 'contact',
                'is_translatable' => true,
            ],
            [
                'key' => 'google_maps_iframe',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d877.2329059688166!2d11.568594733635424!3d48.18711584303955!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x479e7427925783ab%3A0xcaec8c4a43effb96!2sFrankfurter%20Ring%2012%2C%2080807%20M%C3%BCnchen%2C%20Germany!5e0!3m2!1sen!2s!4v1744798556211!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
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
                'value' => '+4917657904182',
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
                'value' => 'AISUKI - Asian Cooking - Sushi & Bowls',
                'group' => 'seo',
                'is_translatable' => true,
            ],
            [
                'key' => 'meta_description',
                'value' => 'AISUKI - Asian Cooking - Sushi & Bowls',
                'group' => 'seo',
                'is_translatable' => true,
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'asian cooking, sushi, munich, ramen, pho viet nam',
                'group' => 'seo',
                'is_translatable' => true,
            ],
            [
                'key' => 'delivery_fee',
                'value' => '0.00',
                'group' => 'order',
                'is_translatable' => false,
            ],
            [
                'key' => 'enable_delivery',
                'value' => '1',
                'group' => 'order',
                'is_translatable' => false,
            ],
            [
                'key' => 'enable_pickup',
                'value' => '1',
                'group' => 'order',
                'is_translatable' => false,
            ],
            [
                'key' => 'min_order_amount',
                'value' => '0.00',
                'group' => 'order',
                'is_translatable' => false,
            ],
            [
                'key' => 'store_address',
                'value' => 'Korbinianstraße 60 (Ecke Frankfurter Ring 12). 80807 München',
                'group' => 'order',
                'is_translatable' => true,
            ],
            [
                'key' => 'pickup_instructions',
                'value' => 'Please come to the restaurant and give your name at the counter.',
                'group' => 'order',
                'is_translatable' => true,
            ],
            // Mail settings - NEW
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.gmail.com',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@aisuki.de',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'AISUKI Restaurant',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_reply_to',
                'value' => 'info@aisuki.de',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_contact_to',
                'value' => 'contact@aisuki.de',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_enable_contact_form',
                'value' => '1',
                'group' => 'mail',
                'is_translatable' => false,
            ],
            [
                'key' => 'mail_enable_notification',
                'value' => '1',
                'group' => 'mail',
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
                'value' => 'Korbinianstraße 60 (Ecke Frankfurter Ring 12). 80807 München',
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
                'value' => 'AISUKI - Asian Cooking - Sushi & Bowls',
            ],
            [
                'group' => 'settings',
                'key' => 'meta_description',
                'language_code' => 'de',
                'value' => 'AISUKI - Asian Cooking - Sushi & Bowls',
            ],
            [
                'group' => 'settings',
                'key' => 'meta_keywords',
                'language_code' => 'de',
                'value' => 'asian cooking, sushi, munich, ramen, pho viet nam',
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