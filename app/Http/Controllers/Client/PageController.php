<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Language;
use App\Models\Notification;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        // Format content for notification
        $content = "Name: {$request->name}\n";
        $content .= "Email: {$request->email}\n";
        $content .= "Phone: {$request->phone}\n";
        $content .= "Date: {$request->date}\n";
        $content .= "Time: {$request->time}\n";
        $content .= "Guests: {$request->guests}\n";
        if ($request->notes) {
            $content .= "Notes: {$request->notes}\n";
        }
        
        // Create notification
        $notification = Notification::create([
            'type' => 'reservation',
            'title' => 'New Table Reservation',
            'content' => $content,
            'is_read' => false,
            'is_processed' => false,
        ]);
        
        // Send email notifications
        $this->sendReservationEmails($request, $notification);
        $successMessage = trans_db('sections', 'reservation_success', false) ?: 'Thank you for your reservation. We will contact you shortly to confirm!';
        
        return redirect()
            ->route('contact', ['locale' => app()->getLocale()])
            ->with('success', $successMessage);
    }

    protected function sendReservationEmails($request, $notification)
    {
        try {
            // Prepare email data
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date' => $request->date,
                'time' => $request->time,
                'guests' => $request->guests,
                'notes' => $request->notes,
                'site_name' => setting('site_name', 'AISUKI Restaurant'),
                'submitted_at' => now()->format('F j, Y, g:i a'),
            ];
            
            // Send notification to admin
            $adminEmail = setting('mail_contact_to', setting('email'));
            $mailService = app(MailService::class);
            $mailService->sendReservationNotificationToAdmin($adminEmail, $data);
            $mailService->sendReservationConfirmation($request->email, $data);
            
            return true;
        } catch (\Exception $e) {
            // Log error but don't break flow
            Log::error('Failed to send reservation emails: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Show the about us page (convenience method)
     */
    public function about($locale)
    {
        // Try to find the about page by language-specific slug
        $language = Language::where('code', $locale)->first();
        
        // Assume mass_id 1 is the about page
        // You might want to use a more reliable way to identify the about page
        $aboutPage = Page::where('mass_id', 1)
            ->where('language_id', $language->id)
            ->where('is_active', true)
            ->first();
            
        if ($aboutPage) {
            return $this->show($locale, $aboutPage->slug);
        }
        
        // Fallback to default about-us slug if no custom slug found
        return $this->show($locale, 'about-us');
    }
}