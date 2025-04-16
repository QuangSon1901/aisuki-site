<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'language_id', 'mass_id', 'slug', 'title', 'content', 
        'featured_image', 'meta_title', 'meta_description', 'meta_keywords',
        'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    
    /**
     * Get records with the same content (same mass_id) but different languages
     */
    public function translations()
    {
        return Page::where('mass_id', $this->mass_id)
            ->where('id', '!=', $this->id)
            ->get();
    }
    
    /**
     * Get a page by slug and the current language
     */
    public static function getBySlug($slug)
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $page = self::where('slug', $slug)
            ->where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->first();
            
        if (!$page) {
            // Fallback: Get the page in the default language
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $page = self::where('slug', $slug)
                    ->where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->first();
            }
        }
        
        return $page;
    }
    
    /**
     * Get all active pages for current language
     */
    public static function getActive()
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $pages = self::where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        if ($pages->isEmpty()) {
            // Fallback: Get pages in default language
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $pages = self::where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }
        }
        
        return $pages;
    }
}