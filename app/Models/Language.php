<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'code', 'name', 'native_name', 'flag', 'is_default', 'is_active', 'sort_order'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'language_code', 'code');
    }

    /**
     * Get default language
     */
    public static function getDefault()
    {
        return self::where('is_default', true)->first();
    }
    
    /**
     * Get all active languages
     */
    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }
    
    /**
     * Get translation count for this language
     */
    public function getTranslationCountAttribute()
    {
        return $this->translations()->count();
    }
}