<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Show a specific page by slug
     */
    public function show($locale, $slug)
    {
        // Get the page
        $page = Page::getBySlug($slug);
        
        // Return 404 if page doesn't exist
        if (!$page) {
            abort(404);
        }
        
        // Get SEO data
        $seoSettings = settings_group('seo');
        $contactSettings = settings_group('contact');
        $socialSettings = settings_group('social');
        
        return view('client.pages.show', compact(
            'page',
            'seoSettings',
            'contactSettings',
            'socialSettings'
        ));
    }

    public function contact()
    {
        $seoSettings = settings_group('seo');
        $contactSettings = settings_group('contact');
        $socialSettings = settings_group('social');
        
        return view('client.pages.contact', compact(
            'seoSettings',
            'contactSettings',
            'socialSettings'
        ));
    }

    public function submitReservation(Request $request)
    {
        // Validate form input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date',
            'time' => 'required',
            'guests' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        // Phần này sẽ được xử lý sau (gửi mail, lưu vào database...)
        
        // Redirect với thông báo thành công
        return redirect()->route('contact', ['locale' => app()->getLocale()])->with('success', 
            trans_db('sections', 'reservation_success', false) ?: 'Thank you for your reservation. We will contact you shortly to confirm!'
        );
    }
    
    /**
     * Show the about us page (convenience method)
     */
    public function about($locale)
    {
        return $this->show($locale, 'about-us');
    }
}