<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting value
     * 
     * @param string $key The setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function setting($key, $default = null)
    {
        $cacheKey = 'setting_' . $key . '_' . app()->getLocale();
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }
            
            if ($setting->is_translatable) {
                $translation = trans_db('settings', $key);
                
                if ($translation) {
                    return $translation;
                }
            }
            
            return $setting->value ?? $default;
        });
    }
}

if (!function_exists('settings_group')) {
    /**
     * Get all settings by group
     * 
     * @param string $group The settings group
     * @return array
     */
    function settings_group($group)
    {
        $cacheKey = 'settings_' . $group . '_' . app()->getLocale();
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($group) {
            $settings = Setting::where('group', $group)->get();
            $result = [];
            
            foreach ($settings as $setting) {
                $value = $setting->value;
                
                if ($setting->is_translatable) {
                    $translation = trans_db('settings', $setting->key);
                    
                    if ($translation) {
                        $value = $translation;
                    }
                }
                
                $result[$setting->key] = $value;
            }
            
            return $result;
        });
    }
}

if (!function_exists('clear_settings_cache')) {
    /**
     * Clear settings cache
     */
    function clear_settings_cache()
    {
        $settingKeys = Setting::pluck('key')->toArray();
        $languages = get_languages();
        
        foreach ($settingKeys as $key) {
            foreach ($languages as $language) {
                Cache::forget('setting_' . $key . '_' . $language->code);
            }
        }
        
        $settingGroups = Setting::distinct('group')->pluck('group')->toArray();
        
        foreach ($settingGroups as $group) {
            foreach ($languages as $language) {
                Cache::forget('settings_' . $group . '_' . $language->code);
            }
        }
    }
}