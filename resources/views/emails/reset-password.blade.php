<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reset Password Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
            text-align: center;
            color: #333333;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 5px;
            margin: 20px 0;
            padding: 15px;
            background-color: #f3f4f6;
            display: inline-block;
            border-radius: 4px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Reset Your Password</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <p>Your verification code is:</p>

            <div class="otp-code">{{ $code }}</div>

            <p>This code will expire in 5 minutes.</p>
            <p>If you did not request a password reset, no further action is required.</p>
        </div>
        <div class="footer">
            This is an automated email, please do not reply.<br>
            &copy; 2026 My Mini Store. All rights reserved.
        </div>
    </div>
</body>

</html>
