<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function change(Request $request, $locale)
    {
        Cookie::queue('locale', $locale, 60*24*30);
        
        // Redirect to home page with the new locale or to the referer URL if available
        $referer = $request->headers->get('referer');
        
        if ($referer) {
            // Parse the referrer URL
            $parsedUrl = parse_url($referer);
            $path = $parsedUrl['path'] ?? '';
            
            // Extract path segments
            $segments = array_filter(explode('/', $path));
            
            // If we have segments and the first one might be a locale
            if (count($segments) > 0) {
                $currentLocale = array_shift($segments);
                
                // Check if first segment was a locale (2-letter code)
                if (strlen($currentLocale) === 2) {
                    // Replace old locale with new one
                    $newPath = '/' . $locale;
                    
                    // Add remaining segments
                    if (!empty($segments)) {
                        $newPath .= '/' . implode('/', $segments);
                    }
                    
                    // Build new URL
                    $scheme = $parsedUrl['scheme'] ?? 'http';
                    $host = $parsedUrl['host'] ?? 'localhost';
                    $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
                    $query = isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '';
                    
                    return redirect($scheme . '://' . $host . $port . $newPath . $query);
                }
            }
        }
        
        // Fallback to home page with new locale
        return redirect('/' . $locale);
    }
}