<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

class LanguageController extends Controller
{
    public function change(Request $request, $locale)
    {
        // Set cookie with the new locale
        Cookie::queue('locale', $locale, 60*24*30);
        
        // Get referrer URL
        $referer = $request->headers->get('referer');
        
        if (!$referer) {
            // If no referrer, redirect to home page with new locale
            return redirect('/' . $locale);
        }
        
        // Parse the referrer URL
        $parsedUrl = parse_url($referer);
        $path = $parsedUrl['path'] ?? '';
        $segments = array_filter(explode('/', $path));
        
        // If no segments, redirect to home page with new locale
        if (empty($segments)) {
            return redirect('/' . $locale);
        }
        
        // Get current locale and remaining path
        $currentLocale = array_shift($segments);
        
        // Check if first segment was a valid locale
        $availableLocales = Language::getActive()->pluck('code')->toArray();
        if (!in_array($currentLocale, $availableLocales)) {
            // If not a valid locale, redirect to home page with new locale
            return redirect('/' . $locale);
        }
        
        // Build the new URL based on the route
        if (empty($segments)) {
            // If it's just the home page
            return redirect('/' . $locale);
        } elseif (count($segments) === 1) {
            // For routes with one segment (like about-us, menu, contact, cart, checkout)
            $slug = $segments[0];
            
            // Handle special routes that are not dynamic pages
            $specialRoutes = ['menu', 'contact', 'cart', 'checkout'];
            if (in_array($slug, $specialRoutes)) {
                return redirect('/' . $locale . '/' . $slug);
            }
            
            // For page routes, find the corresponding page in the target language
            $page = Page::getBySlugAndLocale($slug, $currentLocale);
            
            if ($page) {
                // Find equivalent page in the target language
                $targetPage = $page->getTranslationByLocale($locale);
                
                if ($targetPage) {
                    // If we found a translation, use its slug
                    return redirect('/' . $locale . '/' . $targetPage->slug);
                }
            }
            
            // Special case for about-us page which has a dedicated route
            if ($slug === 'about-us') {
                // Try to find about page in the target language
                $aboutPage = Page::findByMassIdAndLocale(1, $locale); // Assuming mass_id 1 is about page
                
                if ($aboutPage) {
                    return redirect('/' . $locale . '/' . $aboutPage->slug);
                }
                
                // Fallback to about-us route
                return redirect('/' . $locale . '/about-us');
            }
            
            // Fallback to the same slug if no translation found
            return redirect('/' . $locale . '/' . $slug);
        } elseif (count($segments) >= 2 && $segments[0] === 'page') {
            // For page/{slug} routes
            $slug = $segments[1];
            
            // Find the page in the current locale
            $page = Page::getBySlugAndLocale($slug, $currentLocale);
            
            if ($page) {
                // Find equivalent page in the target language
                $targetPage = $page->getTranslationByLocale($locale);
                
                if ($targetPage) {
                    // If we found a translation, use its slug
                    return redirect('/' . $locale . '/page/' . $targetPage->slug);
                }
            }
            
            // Fallback to the same slug if no translation found
            return redirect('/' . $locale . '/page/' . $slug);
        } else {
            // For other routes, keep the same path
            return redirect('/' . $locale . '/' . implode('/', $segments));
        }
    }
}