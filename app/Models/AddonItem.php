<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddonItem extends Model
{
    protected $fillable = [
        'language_id', 'mass_id', 'name', 'price', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
    ];
    
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
    
    /**
     * Get menu items that use this addon
     */
    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_addon', 'addon_item_id', 'menu_item_id');
    }
    
    /**
     * Lấy các record cùng nội dung (cùng mass_id) nhưng khác ngôn ngữ
     */
    public function translations()
    {
        return AddonItem::where('mass_id', $this->mass_id)
            ->where('id', '!=', $this->id)
            ->get();
    }
    
    /**
     * Lấy tất cả add-on theo ngôn ngữ hiện tại
     */
    public static function getActive()
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $addons = self::where('language_id', $currentLanguage->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
            
        if ($addons->isEmpty()) {
            // Fallback: Lấy add-on với ngôn ngữ mặc định
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $addons = self::where('language_id', $defaultLanguage->id)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
            }
        }
        
        return $addons;
    }
}