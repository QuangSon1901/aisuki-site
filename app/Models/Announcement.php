<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id', 'mass_id', 'title', 'content', 'is_active', 
        'start_date', 'end_date', 'is_dismissible', 'priority', 'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_dismissible' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Liên kết với model Language
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    /**
     * Lấy các bản dịch của thông báo này (cùng mass_id nhưng khác language_id)
     */
    public function translations()
    {
        return Announcement::where('mass_id', $this->mass_id)
            ->where('id', '!=', $this->id)
            ->with('language')
            ->get();
    }

    /**
     * Scope các thông báo đang active
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Lấy tất cả thông báo active theo ngôn ngữ hiện tại
     */
    public static function getActive()
    {
        $currentLanguage = Language::where('code', app()->getLocale())->first();
        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', true)->first();
        }
        
        $announcements = self::active()
            ->where('language_id', $currentLanguage->id)
            ->orderBy('priority', 'desc')
            ->orderBy('sort_order')
            ->get();
            
        if ($announcements->isEmpty()) {
            // Fallback: Lấy thông báo với ngôn ngữ mặc định
            $defaultLanguage = Language::where('is_default', true)->first();
            if ($defaultLanguage) {
                $announcements = self::active()
                    ->where('language_id', $defaultLanguage->id)
                    ->orderBy('priority', 'desc')
                    ->orderBy('sort_order')
                    ->get();
            }
        }
        
        return $announcements;
    }
}