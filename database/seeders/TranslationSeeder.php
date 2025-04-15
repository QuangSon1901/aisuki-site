<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run()
    {
        $translations = [
            // Hero Section
            [
                'group' => 'sections',
                'key' => 'hero_subtitle',
                'language_code' => 'en',
                'value' => 'Authentic Japanese restaurant with traditional flavors and cozy atmosphere'
            ],
            [
                'group' => 'sections',
                'key' => 'hero_subtitle',
                'language_code' => 'de',
                'value' => 'Authentisches japanisches Restaurant mit traditionellen Aromen und gemütlicher Atmosphäre'
            ],
            [
                'group' => 'sections',
                'key' => 'hero_button_reservation',
                'language_code' => 'en',
                'value' => 'Book a Table Now'
            ],
            [
                'group' => 'sections',
                'key' => 'hero_button_reservation',
                'language_code' => 'de',
                'value' => 'Jetzt einen Tisch buchen'
            ],
            [
                'group' => 'sections',
                'key' => 'hero_button_menu',
                'language_code' => 'en',
                'value' => 'View Menu'
            ],
            [
                'group' => 'sections',
                'key' => 'hero_button_menu',
                'language_code' => 'de',
                'value' => 'Speisekarte ansehen'
            ],

            // Quick Contact
            [
                'group' => 'sections',
                'key' => 'quick_contact_reservation_title',
                'language_code' => 'en',
                'value' => 'Reservation Contact'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_contact_reservation_title',
                'language_code' => 'de',
                'value' => 'Reservierungskontakt'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_contact_hours_title',
                'language_code' => 'en',
                'value' => 'Opening Hours'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_contact_hours_title',
                'language_code' => 'de',
                'value' => 'Öffnungszeiten'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_contact_address_title',
                'language_code' => 'en',
                'value' => 'Address'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_contact_address_title',
                'language_code' => 'de',
                'value' => 'Adresse'
            ],

            // Menu Section
            [
                'group' => 'sections',
                'key' => 'menu_title',
                'language_code' => 'en',
                'value' => 'Menu'
            ],
            [
                'group' => 'sections',
                'key' => 'menu_title',
                'language_code' => 'de',
                'value' => 'Speisekarte'
            ],
            [
                'group' => 'sections',
                'key' => 'call_hotline',
                'language_code' => 'en',
                'value' => 'Call Hotline'
            ],
            [
                'group' => 'sections',
                'key' => 'call_hotline',
                'language_code' => 'de',
                'value' => 'Hotline anrufen'
            ],
            [
                'group' => 'sections',
                'key' => 'view_full_menu',
                'language_code' => 'en',
                'value' => 'View Full Menu'
            ],
            [
                'group' => 'sections',
                'key' => 'view_full_menu',
                'language_code' => 'de',
                'value' => 'Vollständige Speisekarte'
            ],
            [
                'group' => 'sections',
                'key' => 'order_now',
                'language_code' => 'en',
                'value' => 'Order Now'
            ],
            [
                'group' => 'sections',
                'key' => 'order_now',
                'language_code' => 'de',
                'value' => 'Jetzt bestellen'
            ],

            // Call Action Section
            [
                'group' => 'sections',
                'key' => 'cta_title',
                'language_code' => 'en',
                'value' => 'Book a table today'
            ],
            [
                'group' => 'sections',
                'key' => 'cta_title',
                'language_code' => 'de',
                'value' => 'Buchen Sie heute einen Tisch'
            ],
            [
                'group' => 'sections',
                'key' => 'cta_subtitle',
                'language_code' => 'en',
                'value' => 'Make a reservation to choose your favorite seat and experience authentic Japanese cuisine at AISUKI'
            ],
            [
                'group' => 'sections',
                'key' => 'cta_subtitle',
                'language_code' => 'de',
                'value' => 'Machen Sie eine Reservierung, um Ihren Lieblingsplatz zu wählen und die authentische japanische Küche bei AISUKI zu erleben'
            ],
            [
                'group' => 'sections',
                'key' => 'call',
                'language_code' => 'en',
                'value' => 'Call'
            ],
            [
                'group' => 'sections',
                'key' => 'call',
                'language_code' => 'de',
                'value' => 'Anrufen'
            ],
            [
                'group' => 'sections',
                'key' => 'online_reservation',
                'language_code' => 'en',
                'value' => 'Online Reservation'
            ],
            [
                'group' => 'sections',
                'key' => 'online_reservation',
                'language_code' => 'de',
                'value' => 'Online-Reservierung'
            ],

            // About Section
            [
                'group' => 'sections',
                'key' => 'about_title',
                'language_code' => 'en',
                'value' => 'About AISUKI'
            ],
            [
                'group' => 'sections',
                'key' => 'about_title',
                'language_code' => 'de',
                'value' => 'Über AISUKI'
            ],
            [
                'group' => 'sections',
                'key' => 'about_paragraph_1',
                'language_code' => 'en',
                'value' => 'AISUKI started as a small sushi cart, and through much effort, for which the AISUKI brand is always grateful.'
            ],
            [
                'group' => 'sections',
                'key' => 'about_paragraph_1',
                'language_code' => 'de',
                'value' => 'AISUKI begann als kleiner Sushi-Wagen und entwickelte sich durch viel Mühe, wofür die Marke AISUKI stets dankbar ist.'
            ],
            [
                'group' => 'sections',
                'key' => 'about_paragraph_2',
                'language_code' => 'en',
                'value' => 'With the dedicated purpose of finding the best Japanese food to satisfy our customers at reasonable prices. More accessible to the German people compared to traditional Japanese restaurants.'
            ],
            [
                'group' => 'sections',
                'key' => 'about_paragraph_2',
                'language_code' => 'de',
                'value' => 'Mit dem Ziel, die beste japanische Küche zu finden, um unsere Kunden zu vernünftigen Preisen zufriedenzustellen. Zugänglicher für die deutschen Gäste im Vergleich zu traditionellen japanischen Restaurants.'
            ],
            [
                'group' => 'sections',
                'key' => 'about_paragraph_3',
                'language_code' => 'en',
                'value' => 'Thanks to our commitment to quality and our passion for authentic Japanese cuisine, AISUKI has received support and love from customers. We are always grateful and wish to express our sincere thanks to our customers until the next encounter.'
            ],
            [
                'group' => 'sections',
                'key' => 'about_paragraph_3',
                'language_code' => 'de',
                'value' => 'Dank unseres Engagements für Qualität und unserer Leidenschaft für authentische japanische Küche hat AISUKI Unterstützung und Liebe von seinen Kunden erhalten. Wir sind stets dankbar und möchten unseren Kunden bis zum nächsten Besuch unseren aufrichtigen Dank aussprechen.'
            ],
            [
                'group' => 'sections',
                'key' => 'about_button',
                'language_code' => 'en',
                'value' => 'Learn More'
            ],
            [
                'group' => 'sections',
                'key' => 'about_button',
                'language_code' => 'de',
                'value' => 'Mehr erfahren'
            ],

            // Reservation Section
            [
                'group' => 'sections',
                'key' => 'reservation_title',
                'language_code' => 'en',
                'value' => 'Reservation'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_title',
                'language_code' => 'de',
                'value' => 'Reservierung'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_title',
                'language_code' => 'en',
                'value' => 'Reservation Information'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_title',
                'language_code' => 'de',
                'value' => 'Reservierungsinformationen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_name',
                'language_code' => 'en',
                'value' => 'Full Name'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_name',
                'language_code' => 'de',
                'value' => 'Vollständiger Name'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_name_placeholder',
                'language_code' => 'en',
                'value' => 'Enter your full name'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_name_placeholder',
                'language_code' => 'de',
                'value' => 'Geben Sie Ihren vollständigen Namen ein'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_phone',
                'language_code' => 'en',
                'value' => 'Phone Number'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_phone',
                'language_code' => 'de',
                'value' => 'Telefonnummer'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_phone_placeholder',
                'language_code' => 'en',
                'value' => 'Enter your phone number'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_phone_placeholder',
                'language_code' => 'de',
                'value' => 'Geben Sie Ihre Telefonnummer ein'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_email',
                'language_code' => 'en',
                'value' => 'Email'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_email',
                'language_code' => 'de',
                'value' => 'E-Mail'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_email_placeholder',
                'language_code' => 'en',
                'value' => 'Enter your email'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_email_placeholder',
                'language_code' => 'de',
                'value' => 'Geben Sie Ihre E-Mail ein'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_date',
                'language_code' => 'en',
                'value' => 'Date'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_date',
                'language_code' => 'de',
                'value' => 'Datum'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_time',
                'language_code' => 'en',
                'value' => 'Time'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_time',
                'language_code' => 'de',
                'value' => 'Zeit'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests',
                'language_code' => 'en',
                'value' => 'Number of Guests'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests',
                'language_code' => 'de',
                'value' => 'Anzahl der Gäste'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_1',
                'language_code' => 'en',
                'value' => '1 person'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_1',
                'language_code' => 'de',
                'value' => '1 Person'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_2',
                'language_code' => 'en',
                'value' => '2 people'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_2',
                'language_code' => 'de',
                'value' => '2 Personen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_3',
                'language_code' => 'en',
                'value' => '3 people'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_3',
                'language_code' => 'de',
                'value' => '3 Personen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_4',
                'language_code' => 'en',
                'value' => '4 people'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_4',
                'language_code' => 'de',
                'value' => '4 Personen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_5',
                'language_code' => 'en',
                'value' => '5 people'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_5',
                'language_code' => 'de',
                'value' => '5 Personen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_6plus',
                'language_code' => 'en',
                'value' => '6 or more people'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_guests_6plus',
                'language_code' => 'de',
                'value' => '6 oder mehr Personen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_notes',
                'language_code' => 'en',
                'value' => 'Notes'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_notes',
                'language_code' => 'de',
                'value' => 'Anmerkungen'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_notes_placeholder',
                'language_code' => 'en',
                'value' => 'Enter your notes'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_notes_placeholder',
                'language_code' => 'de',
                'value' => 'Geben Sie Ihre Anmerkungen ein'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_submit',
                'language_code' => 'en',
                'value' => 'Confirm Reservation'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation_form_submit',
                'language_code' => 'de',
                'value' => 'Reservierung bestätigen'
            ],

            // Footer
            [
                'group' => 'sections',
                'key' => 'footer_about_text',
                'language_code' => 'en',
                'value' => 'AISUKI is an authentic Japanese restaurant, bringing diners the true culinary experiences of the cherry blossom country.'
            ],
            [
                'group' => 'sections',
                'key' => 'footer_about_text',
                'language_code' => 'de',
                'value' => 'AISUKI ist ein authentisches japanisches Restaurant, das seinen Gästen die wahren kulinarischen Erlebnisse des Landes der Kirschblüten bietet.'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_links',
                'language_code' => 'en',
                'value' => 'Quick Links'
            ],
            [
                'group' => 'sections',
                'key' => 'quick_links',
                'language_code' => 'de',
                'value' => 'Schnelllinks'
            ],
            [
                'group' => 'sections',
                'key' => 'home',
                'language_code' => 'en',
                'value' => 'Home'
            ],
            [
                'group' => 'sections',
                'key' => 'home',
                'language_code' => 'de',
                'value' => 'Startseite'
            ],
            [
                'group' => 'sections',
                'key' => 'about_us',
                'language_code' => 'en',
                'value' => 'About Us'
            ],
            [
                'group' => 'sections',
                'key' => 'about_us',
                'language_code' => 'de',
                'value' => 'Über uns'
            ],
            [
                'group' => 'sections',
                'key' => 'menu',
                'language_code' => 'en',
                'value' => 'Menu'
            ],
            [
                'group' => 'sections',
                'key' => 'menu',
                'language_code' => 'de',
                'value' => 'Speisekarte'
            ],
            [
                'group' => 'sections',
                'key' => 'contact',
                'language_code' => 'en',
                'value' => 'Contact'
            ],
            [
                'group' => 'sections',
                'key' => 'contact',
                'language_code' => 'de',
                'value' => 'Kontakt'
            ],
            [
                'group' => 'sections',
                'key' => 'contact_info',
                'language_code' => 'en',
                'value' => 'Contact Info'
            ],
            [
                'group' => 'sections',
                'key' => 'contact_info',
                'language_code' => 'de',
                'value' => 'Kontaktinformationen'
            ],
            [
                'group' => 'sections',
                'key' => 'newsletter',
                'language_code' => 'en',
                'value' => 'Newsletter'
            ],
            [
                'group' => 'sections',
                'key' => 'newsletter',
                'language_code' => 'de',
                'value' => 'Newsletter'
            ],
            [
                'group' => 'sections',
                'key' => 'newsletter_text',
                'language_code' => 'en',
                'value' => 'Subscribe to receive information about promotions and latest updates from AISUKI'
            ],
            [
                'group' => 'sections',
                'key' => 'newsletter_text',
                'language_code' => 'de',
                'value' => 'Abonnieren Sie, um Informationen über Aktionen und neueste Updates von AISUKI zu erhalten'
            ],
            [
                'group' => 'sections',
                'key' => 'email_placeholder',
                'language_code' => 'en',
                'value' => 'Your email'
            ],
            [
                'group' => 'sections',
                'key' => 'email_placeholder',
                'language_code' => 'de',
                'value' => 'Ihre E-Mail'
            ],
            [
                'group' => 'sections',
                'key' => 'subscribe',
                'language_code' => 'en',
                'value' => 'Subscribe'
            ],
            [
                'group' => 'sections',
                'key' => 'subscribe',
                'language_code' => 'de',
                'value' => 'Abonnieren'
            ],
            [
                'group' => 'sections',
                'key' => 'all_rights_reserved',
                'language_code' => 'en',
                'value' => 'All rights reserved.'
            ],
            [
                'group' => 'sections',
                'key' => 'all_rights_reserved',
                'language_code' => 'de',
                'value' => 'Alle Rechte vorbehalten.'
            ],

            // Header
            [
                'group' => 'sections',
                'key' => 'news',
                'language_code' => 'en',
                'value' => 'News'
            ],
            [
                'group' => 'sections',
                'key' => 'news',
                'language_code' => 'de',
                'value' => 'Neuigkeiten'
            ],
            [
                'group' => 'sections',
                'key' => 'mode',
                'language_code' => 'en',
                'value' => 'Mode'
            ],
            [
                'group' => 'sections',
                'key' => 'mode',
                'language_code' => 'de',
                'value' => 'Modus'
            ],
            [
                'group' => 'sections',
                'key' => 'cart',
                'language_code' => 'en',
                'value' => 'Cart'
            ],
            [
                'group' => 'sections',
                'key' => 'cart',
                'language_code' => 'de',
                'value' => 'Warenkorb'
            ],
            [
                'group' => 'sections',
                'key' => 'your_cart',
                'language_code' => 'en',
                'value' => 'Your Cart'
            ],
            [
                'group' => 'sections',
                'key' => 'your_cart',
                'language_code' => 'de',
                'value' => 'Ihr Warenkorb'
            ],
            [
                'group' => 'sections',
                'key' => 'items',
                'language_code' => 'en',
                'value' => 'items'
            ],
            [
                'group' => 'sections',
                'key' => 'items',
                'language_code' => 'de',
                'value' => 'Artikel'
            ],
            [
                'group' => 'sections',
                'key' => 'view_cart',
                'language_code' => 'en',
                'value' => 'View Cart'
            ],
            [
                'group' => 'sections',
                'key' => 'view_cart',
                'language_code' => 'de',
                'value' => 'Warenkorb anzeigen'
            ],
            [
                'group' => 'sections',
                'key' => 'checkout',
                'language_code' => 'en',
                'value' => 'Checkout'
            ],
            [
                'group' => 'sections',
                'key' => 'checkout',
                'language_code' => 'de',
                'value' => 'Zur Kasse'
            ],
            [
                'group' => 'sections',
                'key' => 'call_now',
                'language_code' => 'en',
                'value' => 'Call Now'
            ],
            [
                'group' => 'sections',
                'key' => 'call_now',
                'language_code' => 'de',
                'value' => 'Jetzt anrufen'
            ],
            [
                'group' => 'sections',
                'key' => 'display_mode',
                'language_code' => 'en',
                'value' => 'Display Mode'
            ],
            [
                'group' => 'sections',
                'key' => 'display_mode',
                'language_code' => 'de',
                'value' => 'Anzeigemodus'
            ],
            [
                'group' => 'sections',
                'key' => 'light',
                'language_code' => 'en',
                'value' => 'Light'
            ],
            [
                'group' => 'sections',
                'key' => 'light',
                'language_code' => 'de',
                'value' => 'Hell'
            ],
            [
                'group' => 'sections',
                'key' => 'dark',
                'language_code' => 'en',
                'value' => 'Dark'
            ],
            [
                'group' => 'sections',
                'key' => 'dark',
                'language_code' => 'de',
                'value' => 'Dunkel'
            ],
            [
                'group' => 'sections',
                'key' => 'language',
                'language_code' => 'en',
                'value' => 'Language'
            ],
            [
                'group' => 'sections',
                'key' => 'language',
                'language_code' => 'de',
                'value' => 'Sprache'
            ],
            [
                'group' => 'sections',
                'key' => 'total',
                'language_code' => 'en',
                'value' => 'Total'
            ],
            [
                'group' => 'sections',
                'key' => 'total',
                'language_code' => 'de',
                'value' => 'Gesamt'
            ],

            // Mobile CTA
            [
                'group' => 'sections',
                'key' => 'reservation',
                'language_code' => 'en',
                'value' => 'Reservation'
            ],
            [
                'group' => 'sections',
                'key' => 'reservation',
                'language_code' => 'de',
                'value' => 'Reservierung'
            ],

            // Follow Us
            [
                'group' => 'sections',
                'key' => 'follow_us',
                'language_code' => 'en',
                'value' => 'Follow Us'
            ],
            [
                'group' => 'sections',
                'key' => 'follow_us',
                'language_code' => 'de',
                'value' => 'Folgen Sie uns'
            ],

            // Menu Items - Sample Data
            [
                'group' => 'menu_items',
                'key' => 'salmon_sushi_name',
                'language_code' => 'en',
                'value' => 'Salmon Sushi'
            ],
            [
                'group' => 'menu_items',
                'key' => 'salmon_sushi_name',
                'language_code' => 'de',
                'value' => 'Lachs Sushi'
            ],
            [
                'group' => 'menu_items',
                'key' => 'salmon_sushi_desc',
                'language_code' => 'en',
                'value' => 'Fresh Norwegian salmon with traditional vinegar rice'
            ],
            [
                'group' => 'menu_items',
                'key' => 'salmon_sushi_desc',
                'language_code' => 'de',
                'value' => 'Frischer norwegischer Lachs mit traditionellem Essig-Reis'
            ],
            [
                'group' => 'menu_items',
                'key' => 'california_roll_name',
                'language_code' => 'en',
                'value' => 'California Roll'
            ],
            [
                'group' => 'menu_items',
                'key' => 'california_roll_name',
                'language_code' => 'de',
                'value' => 'California Roll'
            ],
            [
                'group' => 'menu_items',
                'key' => 'california_roll_desc',
                'language_code' => 'en',
                'value' => 'Crab stick, avocado, cucumber, salmon roe, seaweed'
            ],
            [
                'group' => 'menu_items',
                'key' => 'california_roll_desc',
                'language_code' => 'de',
                'value' => 'Krabben, Avocado, Gurke, Lachskaviar, Seetang'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tonkotsu_ramen_name',
                'language_code' => 'en',
                'value' => 'Tonkotsu Ramen'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tonkotsu_ramen_name',
                'language_code' => 'de',
                'value' => 'Tonkotsu Ramen'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tonkotsu_ramen_desc',
                'language_code' => 'en',
                'value' => '12-hour pork bone broth, grilled pork, egg, seaweed'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tonkotsu_ramen_desc',
                'language_code' => 'de',
                'value' => '12 Stunden gekochte Schweinebrühe, gegrilltes Schweinefleisch, Ei, Seetang'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tempura_shrimp_name',
                'language_code' => 'en',
                'value' => 'Shrimp Tempura'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tempura_shrimp_name',
                'language_code' => 'de',
                'value' => 'Garnelen Tempura'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tempura_shrimp_desc',
                'language_code' => 'en',
                'value' => 'Fresh shrimp in crispy Japanese batter, with special tempura sauce'
            ],
            [
                'group' => 'menu_items',
                'key' => 'tempura_shrimp_desc',
                'language_code' => 'de',
                'value' => 'Frische Garnelen in knusprigem japanischem Teig, mit spezieller Tempura-Soße'
            ],
            // Menu Page
            [
                'group' => 'sections',
                'key' => 'menu_page_title',
                'language_code' => 'en',
                'value' => 'Our Menu'
            ],
            [
                'group' => 'sections',
                'key' => 'menu_page_title',
                'language_code' => 'de',
                'value' => 'Unsere Speisekarte'
            ],
            [
                'group' => 'sections',
                'key' => 'menu_page_subtitle',
                'language_code' => 'en',
                'value' => 'Discover authentic Japanese flavors'
            ],
            [
                'group' => 'sections',
                'key' => 'menu_page_subtitle',
                'language_code' => 'de',
                'value' => 'Entdecken Sie authentische japanische Aromen'
            ],
            [
                'group' => 'sections',
                'key' => 'lunch_special_note',
                'language_code' => 'en',
                'value' => 'Available from 11:00 to 14:30'
            ],
            [
                'group' => 'sections',
                'key' => 'lunch_special_note',
                'language_code' => 'de',
                'value' => 'Verfügbar von 11:00 bis 14:30 Uhr'
            ],
            [
                'group' => 'sections',
                'key' => 'all_categories',
                'language_code' => 'en',
                'value' => 'All Categories'
            ],
            [
                'group' => 'sections',
                'key' => 'all_categories',
                'language_code' => 'de',
                'value' => 'Alle Kategorien'
            ],
            [
                'group' => 'sections',
                'key' => 'special_note',
                'language_code' => 'en',
                'value' => 'Special Note'
            ],
            [
                'group' => 'sections',
                'key' => 'special_note',
                'language_code' => 'de',
                'value' => 'Besondere Anmerkung'
            ],
            [
                'group' => 'sections',
                'key' => 'add_to_cart',
                'language_code' => 'en',
                'value' => 'Add to Cart'
            ],
            [
                'group' => 'sections',
                'key' => 'add_to_cart',
                'language_code' => 'de',
                'value' => 'In den Warenkorb'
            ],
            [
                'group' => 'sections',
                'key' => 'order_now',
                'language_code' => 'en',
                'value' => 'Order Now'
            ],
            [
                'group' => 'sections',
                'key' => 'order_now',
                'language_code' => 'de',
                'value' => 'Jetzt bestellen'
            ],
            [
                'group' => 'sections',
                'key' => 'quantity',
                'language_code' => 'en',
                'value' => 'Quantity'
            ],
            [
                'group' => 'sections',
                'key' => 'quantity',
                'language_code' => 'de',
                'value' => 'Menge'
            ],
            [
                'group' => 'sections',
                'key' => 'addons',
                'language_code' => 'en',
                'value' => 'Add-ons'
            ],
            [
                'group' => 'sections',
                'key' => 'addons',
                'language_code' => 'de',
                'value' => 'Extras'
            ],
            [
                'group' => 'sections',
                'key' => 'total',
                'language_code' => 'en',
                'value' => 'Total'
            ],
            [
                'group' => 'sections',
                'key' => 'total',
                'language_code' => 'de',
                'value' => 'Gesamt'
            ],
            [
                'group' => 'sections',
                'key' => 'no_items_found',
                'language_code' => 'en',
                'value' => 'No items found in this category.'
            ],
            [
                'group' => 'sections',
                'key' => 'no_items_found',
                'language_code' => 'de',
                'value' => 'Keine Artikel in dieser Kategorie gefunden.'
            ],
            // Cart Section
            [
                'group' => 'sections',
                'key' => 'your_cart',
                'language_code' => 'en',
                'value' => 'Your Cart'
            ],
            [
                'group' => 'sections',
                'key' => 'your_cart',
                'language_code' => 'de',
                'value' => 'Ihr Warenkorb'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_items',
                'language_code' => 'en',
                'value' => 'Cart Items'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_items',
                'language_code' => 'de',
                'value' => 'Warenkorb Artikel'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_empty',
                'language_code' => 'en',
                'value' => 'Your cart is empty'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_empty',
                'language_code' => 'de',
                'value' => 'Ihr Warenkorb ist leer'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_empty_message',
                'language_code' => 'en',
                'value' => 'Add some delicious items to your cart'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_empty_message',
                'language_code' => 'de',
                'value' => 'Fügen Sie Ihrem Warenkorb leckere Artikel hinzu'
            ],
            [
                'group' => 'sections',
                'key' => 'loading_cart',
                'language_code' => 'en',
                'value' => 'Loading your cart...'
            ],
            [
                'group' => 'sections',
                'key' => 'loading_cart',
                'language_code' => 'de',
                'value' => 'Ihr Warenkorb wird geladen...'
            ],
            [
                'group' => 'sections',
                'key' => 'continue_shopping',
                'language_code' => 'en',
                'value' => 'Continue Shopping'
            ],
            [
                'group' => 'sections',
                'key' => 'continue_shopping',
                'language_code' => 'de',
                'value' => 'Weiter einkaufen'
            ],
            [
                'group' => 'sections',
                'key' => 'clear_cart',
                'language_code' => 'en',
                'value' => 'Clear Cart'
            ],
            [
                'group' => 'sections',
                'key' => 'clear_cart',
                'language_code' => 'de',
                'value' => 'Warenkorb leeren'
            ],
            [
                'group' => 'sections',
                'key' => 'confirm_clear_cart',
                'language_code' => 'en',
                'value' => 'Are you sure you want to clear your cart?'
            ],
            [
                'group' => 'sections',
                'key' => 'confirm_clear_cart',
                'language_code' => 'de',
                'value' => 'Sind Sie sicher, dass Sie Ihren Warenkorb leeren möchten?'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_cleared',
                'language_code' => 'en',
                'value' => 'Your cart has been cleared'
            ],
            [
                'group' => 'sections',
                'key' => 'cart_cleared',
                'language_code' => 'de',
                'value' => 'Ihr Warenkorb wurde geleert'
            ],
            [
                'group' => 'sections',
                'key' => 'proceed_to_checkout',
                'language_code' => 'en',
                'value' => 'Proceed to Checkout'
            ],
            [
                'group' => 'sections',
                'key' => 'proceed_to_checkout',
                'language_code' => 'de',
                'value' => 'Zur Kasse gehen'
            ],
            [
                'group' => 'sections',
                'key' => 'order_summary',
                'language_code' => 'en',
                'value' => 'Order Summary'
            ],
            [
                'group' => 'sections',
                'key' => 'order_summary',
                'language_code' => 'de',
                'value' => 'Bestellübersicht'
            ],
            [
                'group' => 'sections',
                'key' => 'subtotal',
                'language_code' => 'en',
                'value' => 'Subtotal'
            ],
            [
                'group' => 'sections',
                'key' => 'subtotal',
                'language_code' => 'de',
                'value' => 'Zwischensumme'
            ],
            [
                'group' => 'sections',
                'key' => 'delivery_fee',
                'language_code' => 'en',
                'value' => 'Delivery Fee'
            ],
            [
                'group' => 'sections',
                'key' => 'delivery_fee',
                'language_code' => 'de',
                'value' => 'Liefergebühr'
            ],
            [
                'group' => 'sections',
                'key' => 'discount',
                'language_code' => 'en',
                'value' => 'Discount'
            ],
            [
                'group' => 'sections',
                'key' => 'discount',
                'language_code' => 'de',
                'value' => 'Rabatt'
            ],
            [
                'group' => 'sections',
                'key' => 'promo_code_placeholder',
                'language_code' => 'en',
                'value' => 'Enter promo code'
            ],
            [
                'group' => 'sections',
                'key' => 'promo_code_placeholder',
                'language_code' => 'de',
                'value' => 'Gutscheincode eingeben'
            ],
            [
                'group' => 'sections',
                'key' => 'apply',
                'language_code' => 'en',
                'value' => 'Apply'
            ],
            [
                'group' => 'sections',
                'key' => 'apply',
                'language_code' => 'de',
                'value' => 'Einlösen'
            ],
            [
                'group' => 'sections',
                'key' => 'need_help',
                'language_code' => 'en',
                'value' => 'Need Help?'
            ],
            [
                'group' => 'sections',
                'key' => 'need_help',
                'language_code' => 'de',
                'value' => 'Hilfe benötigt?'
            ],
            [
                'group' => 'sections',
                'key' => 'call_us',
                'language_code' => 'en',
                'value' => 'Call us'
            ],
            [
                'group' => 'sections',
                'key' => 'call_us',
                'language_code' => 'de',
                'value' => 'Rufen Sie uns an'
            ],
            [
                'group' => 'sections',
                'key' => 'remove',
                'language_code' => 'en',
                'value' => 'Remove'
            ],
            [
                'group' => 'sections',
                'key' => 'remove',
                'language_code' => 'de',
                'value' => 'Entfernen'
            ],
            [
                'group' => 'sections',
                'key' => 'added_to_cart',
                'language_code' => 'en',
                'value' => 'added to cart'
            ],
            [
                'group' => 'sections',
                'key' => 'added_to_cart',
                'language_code' => 'de',
                'value' => 'zum Warenkorb hinzugefügt'
            ],
            [
                'group' => 'sections',
                'key' => 'removed_from_cart',
                'language_code' => 'en',
                'value' => 'removed from cart'
            ],
            [
                'group' => 'sections',
                'key' => 'removed_from_cart',
                'language_code' => 'de',
                'value' => 'aus dem Warenkorb entfernt'
            ],
            [
                'group' => 'sections',
                'key' => 'enter_promo_code',
                'language_code' => 'en',
                'value' => 'Please enter promo code'
            ],
            [
                'group' => 'sections',
                'key' => 'enter_promo_code',
                'language_code' => 'de',
                'value' => 'Bitte geben Sie einen Promo-Code ein'
            ],
            [
                'group' => 'sections',
                'key' => 'promo_applied',
                'language_code' => 'en',
                'value' => 'Promo code applied successfully'
            ],
            [
                'group' => 'sections',
                'key' => 'promo_applied',
                'language_code' => 'de',
                'value' => 'Promo-Code erfolgreich angewendet'
            ],
            [
                'group' => 'sections',
                'key' => 'invalid_promo',
                'language_code' => 'en',
                'value' => 'Invalid promo code'
            ],
            [
                'group' => 'sections',
                'key' => 'invalid_promo',
                'language_code' => 'de',
                'value' => 'Ungültiger Promo-Code'
            ],
            [
                'group' => 'sections',
                'key' => 'special_note_placeholder',
                'language_code' => 'en',
                'value' => 'Example: Less spicy, no onions...'
            ],
            [
                'group' => 'sections',
                'key' => 'special_note_placeholder',
                'language_code' => 'de',
                'value' => 'Beispiel: Weniger scharf, keine Zwiebeln...'
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
