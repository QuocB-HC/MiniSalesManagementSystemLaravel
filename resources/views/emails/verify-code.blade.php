<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ asset('css/emails/verify-code.css') }}">
</head>

<body>
    <div class="container">
        <h2>Hello!</h2>
        <p>Thank you for registering an account at <strong>My Mini Store</strong>.</p>
        <p>Your verification code is:</p>
        <p class="code">{{ $code }}</p>
        <p>This code will expire in 5 minutes. Please do not share this code with anyone.</p>
        <div class="footer">
            This is an automated email, please do not reply.
        </div>
    </div>
</body>

</html>
