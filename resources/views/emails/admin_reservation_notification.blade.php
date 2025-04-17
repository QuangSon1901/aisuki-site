<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Reservation Request</title>
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
        <p>New Reservation Request</p>
    </div>
    
    <div class="content">
        <p>A new table reservation has been received!</p>
        
        <div class="reservation-details">
            <h3>Reservation Information</h3>
            <p><strong>Name:</strong> {{ $name }}</p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Phone:</strong> {{ $phone }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Time:</strong> {{ $time }}</p>
            <p><strong>Number of Guests:</strong> {{ $guests }}</p>
            @if(!empty($notes))
                <p><strong>Notes:</strong> {{ $notes }}</p>
            @endif
            <p><strong>Submitted:</strong> {{ $submitted_at }}</p>
        </div>
        
        <p>Please confirm this reservation with the customer as soon as possible.</p>
    </div>
    
    <div class="footer">
        <p>© {{ date('Y') }} {{ $site_name }}. All rights reserved.</p>
    </div>
</body>
</html>