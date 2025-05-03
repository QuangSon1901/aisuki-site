<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Language;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Get active announcements
     */
    public function getActiveAnnouncements(Request $request)
    {
        // Lấy locale từ request nếu có, nếu không thì lấy từ app
        $locale = $request->input('locale', app()->getLocale());
        
        // Tìm language_id cho locale hiện tại
        $language = Language::where('code', $locale)->first();
        
        if (!$language) {
            // Fallback to default language
            $language = Language::where('is_default', true)->first();
        }
        
        // Lấy announcements theo language_id
        $announcements = Announcement::active()
            ->where('language_id', $language->id)
            ->orderBy('priority', 'desc')
            ->orderBy('sort_order')
            ->get();
            
        if ($announcements->isEmpty() && !$language->is_default) {
            // Nếu không có thông báo cho ngôn ngữ hiện tại, fallback về default
            $defaultLanguage = Language::where('is_default', true)->first();
            
            $announcements = Announcement::active()
                ->where('language_id', $defaultLanguage->id)
                ->orderBy('priority', 'desc')
                ->orderBy('sort_order')
                ->get();
        }

        return response()->json([
            'success' => true,
            'locale' => $locale,
            'language_id' => $language->id,
            'announcements' => $announcements
        ]);
    }
}