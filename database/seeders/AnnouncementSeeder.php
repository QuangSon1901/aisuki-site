<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Language;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get default language ID
        $englishId = Language::where('code', 'en')->first()->id;
        $germanId = Language::where('code', 'de')->first()->id;
        
        // Create announcements
        
        // Announcement 1 - English
        Announcement::create([
            'language_id' => $englishId,
            'mass_id' => 1,
            'title' => 'Holiday Closure Notice',
            'content' => '<div style="text-align:center;">
                <p>Please note that we will be closed on December 25th for Christmas.</p>
                <p>We will reopen on December 26th with regular hours.</p>
                <p>Happy Holidays from the entire AISUKI team!</p>
            </div>',
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_dismissible' => true,
            'priority' => 10,
            'sort_order' => 0,
        ]);
        
        // Announcement 1 - German
        Announcement::create([
            'language_id' => $germanId,
            'mass_id' => 1,
            'title' => 'Hinweis zur Feiertagsschließung',
            'content' => '<div style="text-align:center;">
                <p>Bitte beachten Sie, dass wir am 25. Dezember wegen Weihnachten geschlossen haben.</p>
                <p>Wir öffnen am 26. Dezember wieder zu den regulären Öffnungszeiten.</p>
                <p>Frohe Feiertage wünscht das gesamte AISUKI-Team!</p>
            </div>',
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_dismissible' => true,
            'priority' => 10,
            'sort_order' => 0,
        ]);

        // Announcement 2 - English
        Announcement::create([
            'language_id' => $englishId,
            'mass_id' => 2,
            'title' => 'Special Promotion',
            'content' => '<div style="text-align:center;">
                <h2 style="color:#e61c23;">Buy One Get One Free!</h2>
                <p>On all sushi platters every Tuesday this month.</p>
                <p>Just mention this promotion when ordering to receive the discount.</p>
                <img src="https://via.placeholder.com/400x200" alt="Promotion" style="max-width:100%;height:auto;margin:10px 0;">
                <p><small>Terms and conditions apply. Cannot be combined with other offers.</small></p>
            </div>',
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_dismissible' => true,
            'priority' => 5,
            'sort_order' => 1,
        ]);
        
        // Announcement 2 - German
        Announcement::create([
            'language_id' => $germanId,
            'mass_id' => 2,
            'title' => 'Sonderangebot',
            'content' => '<div style="text-align:center;">
                <h2 style="color:#e61c23;">Kaufe Eins, Bekomme Eins Gratis!</h2>
                <p>Auf alle Sushi-Platten jeden Dienstag in diesem Monat.</p>
                <p>Erwähnen Sie einfach diese Aktion bei der Bestellung, um den Rabatt zu erhalten.</p>
                <img src="https://via.placeholder.com/400x200" alt="Promotion" style="max-width:100%;height:auto;margin:10px 0;">
                <p><small>Es gelten die allgemeinen Geschäftsbedingungen. Nicht mit anderen Angeboten kombinierbar.</small></p>
            </div>',
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'is_dismissible' => true,
            'priority' => 5,
            'sort_order' => 1,
        ]);
    }
}