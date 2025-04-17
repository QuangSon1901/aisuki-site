<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #e61c23;
            color: white;
        }
        .content {
            padding: 20px;
        }
        .reservation-details {
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
        }
        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #777;
            padding: 20px 0;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $site_name }}</h1>
        <p>Reservation Confirmation</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $name }},</p>
        
        <p>Thank you for your reservation request. We have received your details and will be processing your reservation shortly.</p>
        
        <div class="reservation-details">
            <h3>Reservation Information</h3>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Time:</strong> {{ $time }}</p>
            <p><strong>Number of Guests:</strong> {{ $guests }}</p>
            @if(!empty($notes))
                <p><strong>Your Notes:</strong> {{ $notes }}</p>
            @endif
        </div>
        
        <p>We will contact you to confirm your reservation. If you need to modify or cancel, please contact us at {{ setting('phone') }} or {{ setting('email') }}.</p>
        
        <p>We look forward to welcoming you to {{ $site_name }}!</p>
        
        <p>Best regards,</p>
        <p>The {{ $site_name }} Team</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ $site_name }}. All rights reserved.</p>
        <p>{{ setting('address') }}</p>
    </div>
</body>
</html>