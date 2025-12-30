<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0066cc;
        }
        .header h1 {
            color: #0066cc;
            margin: 0;
            font-size: 28px;
        }
        .content {
            margin: 30px 0;
        }
        .content h2 {
            color: #0066cc;
            font-size: 22px;
            margin-top: 0;
        }
        .donation-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #0066cc;
        }
        .donation-details p {
            margin: 10px 0;
            font-size: 16px;
        }
        .donation-details strong {
            color: #0066cc;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #0066cc;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #0066cc;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ABU Endowment Fund</h1>
            <p>Ahmadu Bello University, Zaria</p>
        </div>
        
        <div class="content">
            <h2>Dear {{ $donorName }},</h2>
            
            <p>On behalf of the entire Ahmadu Bello University community, we extend our heartfelt gratitude for your generous donation to the ABU Endowment Fund.</p>
            
            <p>Your contribution of <strong>₦{{ $amount }}</strong> to the <strong>{{ $donationType }}</strong> will make a significant impact on the lives of students, researchers, and the broader ABU community.</p>
            
            <div class="donation-details">
                <h3 style="margin-top: 0; color: #0066cc;">Donation Details</h3>
                <p><strong>Amount:</strong> <span class="amount">₦{{ $amount }}</span></p>
                <p><strong>Donation Type:</strong> {{ $donationType }}</p>
                @if($projectName !== 'ABU Endowment Fund')
                <p><strong>Project:</strong> {{ $projectName }}</p>
                @endif
                <p><strong>Payment Reference:</strong> {{ $reference }}</p>
                <p><strong>Date:</strong> {{ $donationDate->format('F d, Y \a\t h:i A') }}</p>
            </div>
            
            <p>Your support helps us:</p>
            <ul>
                <li>Provide scholarships to deserving students</li>
                <li>Fund groundbreaking research projects</li>
                <li>Improve infrastructure and learning facilities</li>
                <li>Support community development initiatives</li>
            </ul>
            
            <p>Every contribution, no matter the size, plays a crucial role in building a brighter future for ABU and the generations to come. Your generosity is truly appreciated and will leave a lasting legacy.</p>
            
            <p>We will keep you updated on how your donation is being used to make a positive impact. Thank you for being part of the ABU family and for your commitment to excellence in education.</p>
            
            <div class="signature">
                <p>With sincere appreciation,</p>
                <p><strong>The ABU Endowment Fund Team</strong><br>
                Ahmadu Bello University, Zaria<br>
                Kaduna State, Nigeria</p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>For inquiries, please contact us at: endowment@abu.edu.ng</p>
            <p>&copy; {{ date('Y') }} ABU Endowment Fund. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

