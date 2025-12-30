<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ url('abu_logo.png') }}" alt="ABU Logo" style="max-width: 150px; height: auto; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
            <h1 style="color: #2563eb; margin: 10px 0;">ABU Endowment</h1>
            <h2 style="color: #2563eb; margin: 5px 0;">& Crowd Funding</h2>
        </div>
        
        <div style="background: #f8f9fa; padding: 30px; border-radius: 10px;">
            <h2 style="color: #2563eb;">Password Reset Request</h2>
            
            <p>Hello {{ $name }},</p>
            
            <p>You have requested to reset your password. Use the code below to verify your identity:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <div style="display: inline-block; background: #2563eb; color: white; padding: 15px 30px; border-radius: 8px; font-size: 32px; font-weight: bold; letter-spacing: 5px;">
                    {{ $code }}
                </div>
            </div>
            
            <p><strong>This code will expire in 20 minutes.</strong></p>
            
            <p>If you did not request this password reset, please ignore this email.</p>
            
            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>ABU Endowment Team</strong>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

