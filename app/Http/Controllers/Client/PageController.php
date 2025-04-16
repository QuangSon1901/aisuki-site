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
    
    /**
     * Show the about us page (convenience method)
     */
    public function about($locale)
    {
        return $this->show($locale, 'about-us');
    }
}