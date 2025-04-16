<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run()
    {
        // Get language IDs
        $englishLanguage = Language::where('code', 'en')->first();
        $germanLanguage = Language::where('code', 'de')->first();
        
        if (!$englishLanguage || !$germanLanguage) {
            echo "You must run LanguageSeeder first.\n";
            return;
        }
        
        // Create the About Us page
        $aboutUsContent = $this->getAboutUsContentEn();
        $aboutUsPage = Page::create([
            'language_id' => $englishLanguage->id,
            'mass_id' => 1,
            'slug' => 'about-us',
            'title' => 'About Us',
            'content' => $aboutUsContent,
            'featured_image' => 'https://images.unsplash.com/photo-1617196701537-7329482cc9fe?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
            'meta_title' => 'About AISUKI - Our Story and Philosophy',
            'meta_description' => 'Discover the story of AISUKI and our passion for authentic Japanese cuisine',
            'meta_keywords' => 'aisuki, japanese restaurant, about us, our story, japanese cuisine',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        
        // Create German translation
        $aboutUsContentDe = $this->getAboutUsContentDe();
        $aboutUsPageDe = Page::create([
            'language_id' => $germanLanguage->id,
            'mass_id' => 1,
            'slug' => 'uber-uns',
            'title' => 'Über Uns',
            'content' => $aboutUsContentDe,
            'featured_image' => 'https://images.unsplash.com/photo-1617196701537-7329482cc9fe?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80',
            'meta_title' => 'Über AISUKI - Unsere Geschichte und Philosophie',
            'meta_description' => 'Entdecken Sie die Geschichte von AISUKI und unsere Leidenschaft für authentische japanische Küche',
            'meta_keywords' => 'aisuki, japanisches restaurant, über uns, unsere geschichte, japanische küche',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
    
    private function getAboutUsContentEn()
    {
        return '
        <!-- Introduction Section -->
        <section class="py-8">
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="w-full md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1609950547346-a4f431435b2b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="AISUKI Restaurant" class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-bold mb-6 relative inline-block">
                        Welcome to AISUKI
                    </h2>
                    <p class="mb-4">
                        AISUKI is an authentic Japanese restaurant, bringing true Japanese culinary experiences to the heart of Europe. Established in 2015, AISUKI has quickly become a favorite destination for lovers of Japanese cuisine in Munich.
                    </p>
                    <p class="mb-4">
                        We take pride in offering a rich menu with dishes prepared from the freshest ingredients, combining traditional Japanese techniques with modern creativity to create unique flavors only found at AISUKI.
                    </p>
                    <p class="mb-6">
                        With elegant, cozy ambiance and professional, attentive staff, AISUKI is committed to providing our guests with an excellent and memorable dining experience.
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1512485694743-9c9538b4e6e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Chef Tanaka" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-serif italic">"We bring Japan closer to you through every dish."</p>
                            <p class="text-sm text-aisuki-red font-semibold">- Chef Hiroshi Tanaka, Master Chef</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Story Timeline -->
        <section class="py-8 bg-theme-secondary">
            <h2 class="text-3xl font-bold mb-4 text-center">Our Story</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">The journey of AISUKI from its early days to the present is an inspiring story of passion, perseverance, and love for Japanese cuisine.</p>

            <div class="timeline-container py-8">
                <!-- Timeline Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-left">
                        <h3 class="text-xl font-bold mb-2">2015</h3>
                        <p>AISUKI was founded by Chef Hiroshi Tanaka at a small store in Munich with only 5 staff members.</p>
                    </div>
                </div>

                <!-- Timeline Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-right">
                        <h3 class="text-xl font-bold mb-2">2017</h3>
                        <p>After initial success, AISUKI expanded its space and menu, serving a greater variety of Japanese dishes.</p>
                    </div>
                </div>

                <!-- Timeline Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-left">
                        <h3 class="text-xl font-bold mb-2">2019</h3>
                        <p>AISUKI received the "Outstanding Japanese Restaurant" award in Munich and was featured in local media.</p>
                    </div>
                </div>

                <!-- Timeline Item 4 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-right">
                        <h3 class="text-xl font-bold mb-2">2020-2021</h3>
                        <p>Overcoming the challenges of the pandemic, AISUKI developed delivery and takeout services, continuing to serve customers despite difficult circumstances.</p>
                    </div>
                </div>

                <!-- Timeline Item 5 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-left">
                        <h3 class="text-xl font-bold mb-2">2023</h3>
                        <p>AISUKI moved to a new location at Korbinianstraße 60, with a larger space and upgraded menu.</p>
                    </div>
                </div>

                <!-- Timeline Item 6 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-right">
                        <h3 class="text-xl font-bold mb-2">Present</h3>
                        <p>AISUKI continues to grow, with over 20 employees and plans to expand to other cities in Germany, always staying true to its philosophy of high-quality Japanese cuisine.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Culinary Philosophy -->
        <section class="py-8">
            <h2 class="text-3xl font-bold mb-4 text-center">Our Culinary Philosophy</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">At AISUKI, we believe that food is not just sustenance but art, culture, and a way to connect people.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Philosophy Card 1 -->
                <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all p-6">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-leaf text-2xl text-aisuki-red"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center">Fresh Ingredients</h3>
                    <p class="text-center">We use only the freshest ingredients, imported directly from Japan or sourced from trusted local suppliers.</p>
                </div>

                <!-- Philosophy Card 2 -->
                <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all p-6">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-hands text-2xl text-aisuki-red"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center">Traditional Techniques</h3>
                    <p class="text-center">Our chef team is thoroughly trained and uses traditional Japanese cooking techniques, respecting the core values of this cuisine.</p>
                </div>

                <!-- Philosophy Card 3 -->
                <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all p-6">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-lightbulb text-2xl text-aisuki-red"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center">Continuous Innovation</h3>
                    <p class="text-center">While respecting tradition, we continuously innovate and improve, harmoniously blending Japanese cuisine with local tastes.</p>
                </div>
            </div>
        </section>

        <!-- Meet Our Team -->
        <section class="py-8 bg-theme-secondary">
            <h2 class="text-3xl font-bold mb-4 text-center">Our Team</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">Behind every great dish is a talented and passionate team. Meet the people who contribute to AISUKI\'s success.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1512485694743-9c9538b4e6e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Chef Hiroshi Tanaka" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Hiroshi Tanaka</h3>
                                <p class="text-white/80 text-sm">Master Chef & Founder</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">With over 25 years of experience in Japanese cuisine, Chef Tanaka brings to AISUKI the refinement and spirit of omotenashi - Japanese hospitality.</p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1581299894007-aaa50297cf16?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Akira Sato" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Akira Sato</h3>
                                <p class="text-white/80 text-sm">Sushi Chef</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">Formally trained in Tokyo, Chef Sato is a sushi master with perfect fish cutting and rice forming techniques, creating beautiful and delicious sushi works.</p>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1596075780750-81249df16d19?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Yuki Nakamura" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Yuki Nakamura</h3>
                                <p class="text-white/80 text-sm">Executive Chef</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">Chef Nakamura specializes in hot dishes and tempura, with the secret to crispy tempura batter while preserving the natural flavor of ingredients.</p>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1583394293214-28ded15ee548?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=880&q=80" alt="Sophie Mueller" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Sophie Mueller</h3>
                                <p class="text-white/80 text-sm">Restaurant Manager</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">With over 10 years of restaurant management experience, Sophie ensures every customer has an excellent experience at AISUKI.</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="italic">"A great team making great dishes, creating great experiences."</p>
            </div>
        </section>

        <!-- Core Values -->
        <section class="py-8">
            <h2 class="text-3xl font-bold mb-4 text-center">Core Values</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">These values shape all our decisions and actions, from selecting ingredients to serving customers.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Value Card 1 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-heart text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Passion</h3>
                        <p>We work with love and passion for Japanese cuisine, which is reflected in every dish and service.</p>
                    </div>
                </div>

                <!-- Value Card 2 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-medal text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Quality</h3>
                        <p>We never compromise on quality, from ingredients and preparation to presentation and service.</p>
                    </div>
                </div>

                <!-- Value Card 3 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-users text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Hospitality</h3>
                        <p>We treat each customer like family, providing the warmest and friendliest experience possible.</p>
                    </div>
                </div>

                <!-- Value Card 4 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-globe-asia text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Sustainability</h3>
                        <p>We are committed to responsible business practices, from sustainable sourcing to waste reduction and energy conservation.</p>
                    </div>
                </div>
            </div>
        </section>';
    }
    
    private function getAboutUsContentDe()
    {
        return '
        <!-- Introduction Section -->
        <section class="py-8">
            <div class="flex flex-col md:flex-row items-center gap-10">
                <div class="w-full md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1609950547346-a4f431435b2b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" alt="AISUKI Restaurant" class="rounded-lg shadow-xl w-full h-auto">
                </div>
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-bold mb-6 relative inline-block">
                        Willkommen bei AISUKI
                    </h2>
                    <p class="mb-4">
                        AISUKI ist ein authentisches japanisches Restaurant, das echte japanische kulinarische Erlebnisse ins Herz Europas bringt. Seit 2015 ist AISUKI schnell zu einem beliebten Ziel für Liebhaber der japanischen Küche in München geworden.
                    </p>
                    <p class="mb-4">
                        Wir sind stolz darauf, ein reichhaltiges Menü mit Gerichten aus frischesten Zutaten anzubieten, die traditionelle japanische Techniken mit moderner Kreativität verbinden, um einzigartige Aromen zu schaffen, die es nur bei AISUKI gibt.
                    </p>
                    <p class="mb-6">
                        Mit elegantem, gemütlichem Ambiente und professionellem, aufmerksamem Personal ist AISUKI bestrebt, unseren Gästen ein ausgezeichnetes und unvergessliches Esserlebnis zu bieten.
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1512485694743-9c9538b4e6e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Chef Tanaka" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="font-serif italic">"Wir bringen Japan durch jedes Gericht näher zu Ihnen."</p>
                            <p class="text-sm text-aisuki-red font-semibold">- Chefkoch Hiroshi Tanaka, Meisterkoch</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Story Timeline -->
        <section class="py-8 bg-theme-secondary">
            <h2 class="text-3xl font-bold mb-4 text-center">Unsere Geschichte</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">Die Reise von AISUKI von seinen Anfängen bis heute ist eine inspirierende Geschichte von Leidenschaft, Ausdauer und Liebe zur japanischen Küche.</p>

            <div class="timeline-container py-8">
                <!-- Timeline Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-left">
                        <h3 class="text-xl font-bold mb-2">2015</h3>
                        <p>AISUKI wurde von Chefkoch Hiroshi Tanaka in einem kleinen Laden in München mit nur 5 Mitarbeitern gegründet.</p>
                    </div>
                </div>

                <!-- Timeline Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-right">
                        <h3 class="text-xl font-bold mb-2">2017</h3>
                        <p>Nach dem anfänglichen Erfolg erweiterte AISUKI seinen Raum und das Menü und servierte eine größere Vielfalt an japanischen Gerichten.</p>
                    </div>
                </div>

                <!-- Timeline Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-left">
                        <h3 class="text-xl font-bold mb-2">2019</h3>
                        <p>AISUKI erhielt die Auszeichnung "Herausragendes Japanisches Restaurant" in München und wurde in lokalen Medien vorgestellt.</p>
                    </div>
                </div>

                <!-- Timeline Item 4 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-right">
                        <h3 class="text-xl font-bold mb-2">2020-2021</h3>
                        <p>AISUKI überwand die Herausforderungen der Pandemie, entwickelte Liefer- und Abholservices und bediente weiterhin Kunden trotz schwieriger Umstände.</p>
                    </div>
                </div>

                <!-- Timeline Item 5 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-left">
                        <h3 class="text-xl font-bold mb-2">2023</h3>
                        <p>AISUKI zog an einen neuen Standort in der Korbinianstraße 60, mit größerem Raum und verbessertem Menü.</p>
                    </div>
                </div>

                <!-- Timeline Item 6 -->
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content timeline-right">
                        <h3 class="text-xl font-bold mb-2">Gegenwart</h3>
                        <p>AISUKI wächst weiter, mit über 20 Mitarbeitern und Plänen zur Expansion in andere Städte in Deutschland, immer treu seiner Philosophie der hochwertigen japanischen Küche.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Culinary Philosophy -->
        <section class="py-8">
            <h2 class="text-3xl font-bold mb-4 text-center">Unsere kulinarische Philosophie</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">Bei AISUKI glauben wir, dass Essen nicht nur Nahrung ist, sondern Kunst, Kultur und eine Möglichkeit, Menschen zu verbinden.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Philosophy Card 1 -->
                <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all p-6">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-leaf text-2xl text-aisuki-red"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center">Frische Zutaten</h3>
                    <p class="text-center">Wir verwenden nur die frischesten Zutaten, die direkt aus Japan importiert oder von vertrauenswürdigen lokalen Lieferanten bezogen werden.</p>
                </div>

                <!-- Philosophy Card 2 -->
                <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all p-6">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-hands text-2xl text-aisuki-red"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center">Traditionelle Techniken</h3>
                    <p class="text-center">Unser Kochteam ist gründlich ausgebildet und verwendet traditionelle japanische Kochtechniken, wobei die Grundwerte dieser Küche respektiert werden.</p>
                </div>

                <!-- Philosophy Card 3 -->
                <div class="card rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all p-6">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mb-4 mx-auto">
                        <i class="fas fa-lightbulb text-2xl text-aisuki-red"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-center">Kontinuierliche Innovation</h3>
                    <p class="text-center">Während wir die Tradition respektieren, innovieren und verbessern wir kontinuierlich und verbinden harmonisch japanische Küche mit lokalen Geschmäckern.</p>
                </div>
            </div>
        </section>

        <!-- Meet Our Team -->
        <section class="py-8 bg-theme-secondary">
            <h2 class="text-3xl font-bold mb-4 text-center">Unser Team</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">Hinter jedem großartigen Gericht steht ein talentiertes und leidenschaftliches Team. Lernen Sie die Menschen kennen, die zum Erfolg von AISUKI beitragen.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Team Member 1 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1512485694743-9c9538b4e6e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Chef Hiroshi Tanaka" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Hiroshi Tanaka</h3>
                                <p class="text-white/80 text-sm">Meisterkoch & Gründer</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">Mit über 25 Jahren Erfahrung in der japanischen Küche bringt Chefkoch Tanaka die Raffinesse und den Geist des Omotenashi - japanische Gastfreundschaft - zu AISUKI.</p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1581299894007-aaa50297cf16?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Akira Sato" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Akira Sato</h3>
                                <p class="text-white/80 text-sm">Sushi-Koch</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">Formell in Tokio ausgebildet, ist Chefkoch Sato ein Sushi-Meister mit perfekten Fisch-Schnitt- und Reis-Formtechniken, der schöne und köstliche Sushi-Werke kreiert.</p>
                    </div>
                </div>

                <!-- Team Member 3 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1596075780750-81249df16d19?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" alt="Yuki Nakamura" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Yuki Nakamura</h3>
                                <p class="text-white/80 text-sm">Küchenchef</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">Chefkoch Nakamura ist spezialisiert auf heiße Gerichte und Tempura, mit dem Geheimnis für knusprigen Tempura-Teig bei gleichzeitiger Bewahrung des natürlichen Geschmacks der Zutaten.</p>
                    </div>
                </div>

                <!-- Team Member 4 -->
                <div class="card rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1583394293214-28ded15ee548?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=880&q=80" alt="Sophie Mueller" class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-4 w-full">
                                <h3 class="text-white font-semibold text-xl">Sophie Mueller</h3>
                                <p class="text-white/80 text-sm">Restaurant Managerin</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-sm">Mit über 10 Jahren Erfahrung im Restaurantmanagement sorgt Sophie dafür, dass jeder Kunde ein exzellentes Erlebnis bei AISUKI hat.</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="italic">"Ein großartiges Team, das großartige Gerichte zubereitet und großartige Erlebnisse schafft."</p>
            </div>
        </section>

        <!-- Core Values -->
        <section class="py-8">
            <h2 class="text-3xl font-bold mb-4 text-center">Kernwerte</h2>
            <p class="text-center max-w-3xl mx-auto mb-12">Diese Werte prägen all unsere Entscheidungen und Handlungen, von der Auswahl der Zutaten bis zur Bedienung der Kunden.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Value Card 1 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-heart text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Leidenschaft</h3>
                        <p>Wir arbeiten mit Liebe und Leidenschaft für die japanische Küche, was sich in jedem Gericht und Service widerspiegelt.</p>
                    </div>
                </div>

                <!-- Value Card 2 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-medal text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Qualität</h3>
                        <p>Wir machen keine Kompromisse bei der Qualität, von den Zutaten und der Zubereitung bis hin zur Präsentation und dem Service.</p>
                    </div>
                </div>

                <!-- Value Card 3 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-users text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Gastfreundschaft</h3>
                        <p>Wir behandeln jeden Kunden wie Familie und bieten das wärmste und freundlichste Erlebnis, das möglich ist.</p>
                    </div>
                </div>

                <!-- Value Card 4 -->
                <div class="card rounded-lg shadow-md overflow-hidden p-6 flex items-start">
                    <div class="w-16 h-16 rounded-full bg-aisuki-red/10 flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-globe-asia text-2xl text-aisuki-red"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Nachhaltigkeit</h3>
                        <p>Wir sind verantwortungsvolle Geschäftspraktiken verpflichtet, von nachhaltiger Beschaffung bis hin zur Abfallreduzierung und Energieeinsparung.</p>
                    </div>
                </div>
            </div>
        </section>';
    }
}