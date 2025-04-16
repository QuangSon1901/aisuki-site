<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f7f7f7; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="color: #e61c23; margin-top: 0;">Test Email</h2>
        <p>This is a test email from {{ $siteName }}. If you received this email, your email configuration is working correctly.</p>
    </div>
    
    <div style="background-color: #fff; padding: 20px; border-radius: 5px; border: 1px solid #eee;">
        <p>Email configuration details:</p>
        <ul>
            <li>SMTP Server is configured correctly</li>
            <li>Authentication is working</li>
            <li>Email can be sent from your application</li>
        </ul>
        <p>Time sent: {{ now() }}</p>
    </div>
    
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #777;">
        <p>This is an automated message from {{ $siteName }}. Please do not reply to this email.</p>
    </div>
</body>
</html>