<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $fillable = [
        'language_id', 'mass_id', 'slug', 'name', 'image', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
    
    /**
     * Lấy các record cùng nội dung (cùng mass_id) nhưng khác ngôn ngữ
     */
    public function translations()
    {
        return MenuCategory::where('mass_id', $this->mass_id)
            ->where('id', '!=', $this->id)
            ->get();
    }
    
    /**
     * Lấy danh mục theo slug và ngôn ngữ hiện tại
     */
    public static function getBySlug($slug)
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $category = self::where('slug', $slug)
            ->where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->first();
            
        if (!$category) {
            // Fallback: Lấy danh mục theo slug với ngôn ngữ mặc định
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $category = self::where('slug', $slug)
                    ->where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->first();
            }
        }
        
        return $category;
    }
    
    /**
     * Lấy tất cả danh mục theo ngôn ngữ hiện tại
     */
    public static function getActive()
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $categories = self::where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        if ($categories->isEmpty()) {
            // Fallback: Lấy danh mục với ngôn ngữ mặc định
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $categories = self::where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }
        }
        
        return $categories;
    }

    public static function getActiveForCurrentLanguage()
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        return self::where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
}