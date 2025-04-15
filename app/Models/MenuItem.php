<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'language_id', 'mass_id', 'category_id', 'code', 'name', 
        'description', 'price', 'image', 'is_active', 'is_featured', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'float',
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    
    /**
     * Lấy các record cùng nội dung (cùng mass_id) nhưng khác ngôn ngữ
     */
    public function translations()
    {
        return MenuItem::where('mass_id', $this->mass_id)
            ->where('id', '!=', $this->id)
            ->get();
    }
    
    /**
     * Lấy món ăn theo danh mục và ngôn ngữ hiện tại
     */
    public static function getByCategory($categoryId)
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $items = self::where('category_id', $categoryId)
            ->where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        if ($items->isEmpty()) {
            // Fallback: Lấy món ăn với ngôn ngữ mặc định
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $items = self::where('category_id', $categoryId)
                    ->where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }
        }
        
        return $items;
    }
    
    /**
     * Lấy món ăn nổi bật theo ngôn ngữ hiện tại
     */
    public static function getFeatured()
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $items = self::where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();
            
        if ($items->isEmpty()) {
            // Fallback: Lấy món ăn nổi bật với ngôn ngữ mặc định
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $items = self::where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->where('is_featured', true)
                    ->orderBy('sort_order')
                    ->get();
            }
        }
        
        return $items;
    }
}