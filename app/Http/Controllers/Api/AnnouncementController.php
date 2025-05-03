<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Get active announcements
     */
    public function getActiveAnnouncements()
    {
        $announcements = Announcement::getActive();

        return response()->json([
            'success' => true,
            'announcements' => $announcements
        ]);
    }
}