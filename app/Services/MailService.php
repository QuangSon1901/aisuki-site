<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Exception;
use Illuminate\Support\Facades\Log;

class MailService
{
    protected $defaultReplyTo = '';
    
    /**
     * Configure mail settings from database before sending
     */
    protected function configureMailSettings()
    {
        // Get mail settings from database
        $mailDriver = setting('mail_driver') ?? 'smtp';
        $mailHost = setting('mail_host') ?? '';
        $mailPort = (int) (setting('mail_port') ?? 587);
        $mailUsername = setting('mail_username') ?? '';
        $mailPassword = setting('mail_password') ?? '';
        $mailEncryption = setting('mail_encryption') ?? 'tls';
        $mailFromAddress = setting('mail_from_address') ?? '';
        $mailFromName = setting('mail_from_name') ?? '';
        $mailReplyTo = setting('mail_reply_to') ?? '';

        // Set mail configuration
        Config::set('mail.default', $mailDriver);
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $mailHost,
            'port' => $mailPort,
            'username' => $mailUsername,
            'password' => $mailPassword,
            'encryption' => $mailEncryption,
            'timeout' => null,
            'auth_mode' => null,
        ]);
        Config::set('mail.from', [
            'address' => $mailFromAddress,
            'name' => $mailFromName,
        ]);

        $this->defaultReplyTo = $mailReplyTo;
    }

    /**
     * Send test email to verify mail configuration
     *
     * @param array $config Mail configuration
     * @param string $toAddress Test recipient email
     * @return array
     */
    public function sendTestEmail(array $config, string $toAddress)
    {
        try {
            // Configure mail with provided settings
            Config::set('mail.default', $config['driver']);
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $config['host'],
                'port' => (int) $config['port'],
                'username' => $config['username'],
                'password' => $config['password'],
                'encryption' => $config['encryption'],
                'timeout' => null,
                'auth_mode' => null,
            ]);
            Config::set('mail.from', [
                'address' => $config['from_address'],
                'name' => $config['from_name'],
            ]);

            // Send test email
            $siteName = setting('site_name') ?? 'AISUKI Restaurant';
            
            Mail::send('emails.test', ['siteName' => $siteName], function ($message) use ($toAddress, $siteName) {
                $message->to($toAddress);
                $message->subject('Test Email from ' . $siteName);
            });

            return [
                'success' => true,
                'message' => 'Test email sent successfully. Please check your inbox.'
            ];
        } catch (Exception $e) {
            Log::error('Test email error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Send contact form notification
     *
     * @param array $data Form data
     * @return bool|Exception
     */
    public function sendContactFormNotification(array $data)
    {
        // Check if contact form email notification is enabled
        $enableContactForm = setting('mail_enable_notification') == '1';
        if (!$enableContactForm) {
            return false;
        }

        $contactEmail = setting('mail_contact_to');
        if (!$contactEmail) {
            return false;
        }

        try {
            // Configure mail settings from database
            $this->configureMailSettings();

            // Prepare email data
            $emailData = [
                'name' => $data['name'] ?? 'No name',
                'email' => $data['email'] ?? 'No email',
                'phone' => $data['phone'] ?? 'No phone',
                'subject' => $data['subject'] ?? 'Contact from website',
                'messageContent' => $data['message'] ?? 'No content',
                'date' => now()->format('d/m/Y H:i:s'),
                'ip_address' => request()->ip(),
                'site_name' => setting('site_name') ?? config('app.name'),
            ];

            // Send email
            Mail::send('emails.contact_form', $emailData, function ($message) use ($contactEmail, $data) {
                $message->to($contactEmail);
                $message->subject('New contact from website: ' . ($data['subject'] ?? 'No subject'));
                
                // Set reply-to
                if (!empty($data['email'])) {
                    $message->replyTo($data['email'], $data['name'] ?? '');
                }
            });

            return true;
        } catch (Exception $e) {
            Log::error('Cannot send contact form notification: ' . $e->getMessage());
            return $e;
        }
    }

    /**
     * Send auto-reply email
     *
     * @param array $data Form data
     * @return bool
     */
    public function sendContactAutoReply(array $data)
    {
        // Check if contact auto-reply is enabled
        $enableContactForm = setting('mail_enable_contact_form') == '1';
        if (!$enableContactForm) {
            return false;
        }

        // Don't send if user email is not provided
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        try {
            // Configure mail settings from database
            $this->configureMailSettings();

            // Prepare email data
            $emailData = [
                'name' => $data['name'] ?? 'Customer',
                'site_name' => setting('site_name') ?? config('app.name'),
                'contact_email' => setting('email'),
                'contact_phone' => setting('phone'),
            ];

            // Send email
            Mail::send('emails.contact_autoreply', $emailData, function ($message) use ($data) {
                $message->to($data['email'], $data['name'] ?? '');
                $message->subject('Thank you for contacting us');

                if (!empty($this->defaultReplyTo)) {
                    $message->replyTo($this->defaultReplyTo);
                }
            });

            return true;
        } catch (Exception $e) {
            Log::error('Cannot send auto-reply email: ' . $e->getMessage());
            return false;
        }
    }

    public function sendOrderConfirmation($to, array $data)
    {
        // Check if notifications are enabled
        $enableNotification = setting('mail_enable_notification') == '1';
        if (!$enableNotification) {
            return false;
        }

        try {
            // Configure mail settings
            $this->configureMailSettings();

            // Send email
            Mail::send('emails.order_confirmation', $data, function ($message) use ($to, $data) {
                $message->to($to);
                $message->subject('Order Confirmation - ' . $data['order']->order_number);
                
                if (!empty($this->defaultReplyTo)) {
                    $message->replyTo($this->defaultReplyTo);
                }
            });

            return true;
        } catch (Exception $e) {
            Log::error('Cannot send order confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    public function sendOrderNotificationToAdmin($to, array $data)
    {
        // Check if notifications are enabled
        $enableNotification = setting('mail_enable_notification') == '1';
        if (!$enableNotification) {
            return false;
        }

        try {
            // Configure mail settings
            $this->configureMailSettings();

            // Send email
            Mail::send('emails.admin_order_notification', $data, function ($message) use ($to, $data) {
                $message->to($to);
                $message->subject('New Order Received - ' . $data['order']->order_number);
                $message->replyTo($data['order']->email, $data['order']->full_name);
            });

            return true;
        } catch (Exception $e) {
            Log::error('Cannot send admin order notification: ' . $e->getMessage());
            return false;
        }
    }

    public function sendReservationConfirmation($to, array $data)
    {
        // Check if notifications are enabled
        $enableNotification = setting('mail_enable_notification') == '1';
        if (!$enableNotification) {
            return false;
        }

        try {
            // Configure mail settings
            $this->configureMailSettings();

            // Send email
            Mail::send('emails.reservation_confirmation', $data, function ($message) use ($to, $data) {
                $message->to($to);
                $message->subject('Reservation Confirmation');
                
                if (!empty($this->defaultReplyTo)) {
                    $message->replyTo($this->defaultReplyTo);
                }
            });

            return true;
        } catch (Exception $e) {
            Log::error('Cannot send reservation confirmation: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send reservation notification to admin
     */
    public function sendReservationNotificationToAdmin($to, array $data)
    {
        // Check if notifications are enabled
        $enableNotification = setting('mail_enable_notification') == '1';
        if (!$enableNotification) {
            return false;
        }

        try {
            // Configure mail settings
            $this->configureMailSettings();

            // Send email
            Mail::send('emails.admin_reservation_notification', $data, function ($message) use ($to, $data) {
                $message->to($to);
                $message->subject('New Reservation Request');
                $message->replyTo($data['email'], $data['name']);
            });

            return true;
        } catch (Exception $e) {
            Log::error('Cannot send admin reservation notification: ' . $e->getMessage());
            return false;
        }
    }
}