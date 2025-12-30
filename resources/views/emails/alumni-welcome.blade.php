<!DOCTYPE html>
<html>
<head>
    <title>Welcome to ABU Endowment</title>
</head>
<body>
    <h1>Welcome, {{ $donor->name }}!</h1>
    <p>We are pleased to inform you that your alumni record has been successfully created on the ABU Endowment & Crowd Funding platform.</p>
    
    <p>An account has been created for you. You can login using the following credentials:</p>
    
    <p>
        <strong>Username:</strong> {{ $username }}<br>
        <strong>Password:</strong> {{ $password }}
    </p>
    
    <p>Please login and change your password as soon as possible.</p>
    
    <p>
        <a href="{{ url('/') }}">Visit our website</a>
    </p>
    
    <p>Thank you for your continued support!</p>
    
    <p>Best regards,<br>ABU Endowment Team</p>
</body>
</html>
