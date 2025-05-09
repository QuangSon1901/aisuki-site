<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            SettingSeeder::class,
            TranslationSeeder::class,
            MenuCategorySeeder::class,
            MenuItemSeeder::class,
            AddonItemSeeder::class,
            PageSeeder::class, 
            UserSeeder::class, 
            AnnouncementSeeder::class,
        ]);
    }
}
