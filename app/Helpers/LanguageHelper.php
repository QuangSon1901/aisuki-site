<?php

use App\Models\Language;
use App\Models\Translation;

if (!function_exists('get_languages')) {
    /**
     * Get all active languages
     */
    function get_languages()
    {
        return Language::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}

if (!function_exists('default_language')) {
    /**
     * Get default language
     */
    function default_language()
    {
        return Language::where('is_default', true)->first();
    }
}

if (!function_exists('trans_db')) {
    /**
     * Get translated value from database
     * 
     * @param string $group The translation group
     * @param string $key The translation key
     * @param bool $fallback Whether to use default language fallback
     * @return string|null
     */
    function trans_db($group, $key, $fallback = true)
    {
        $locale = app()->getLocale();
        
        $translation = Translation::where([
            'group' => $group,
            'key' => $key,
            'language_code' => $locale
        ])->first();
        
        if ($translation) {
            return $translation->value;
        }
        
        if ($fallback) {
            $defaultLanguage = default_language();
            
            if ($defaultLanguage) {
                $fallbackTranslation = Translation::where([
                    'group' => $group,
                    'key' => $key,
                    'language_code' => $defaultLanguage->code
                ])->first();
                
                if ($fallbackTranslation) {
                    return $fallbackTranslation->value;
                }
            }
        }
        
        return null;
    }
}

if (!function_exists('localized_url')) {
    /**
     * Get localized URL for current route
     * 
     * @param string $locale The target locale
     * @return string
     */
    function localized_url($locale)
    {
        $segments = request()->segments();
        
        // Remove current locale from segments if it exists
        if (count($segments) > 0) {
            $languages = Language::where('is_active', true)
                ->pluck('code', 'code')
                ->toArray();
                
            if (array_key_exists($segments[0], $languages)) {
                array_shift($segments);
            }
        }
        
        // Add new locale as first segment
        array_unshift($segments, $locale);
        
        return url(implode('/', $segments));
    }
}