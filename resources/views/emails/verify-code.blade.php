<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            font-size: 16px;
            line-height: 1.5;
            margin: 10px 0;
        }

        .code-container {
            background-color: #f1f8f4;
            border: 2px dashed #28a745;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .code {
            font-size: 36px;
            font-weight: bold;
            color: #28a745;
            letter-spacing: 8px;
            margin: 0;
        }

        .expiry {
            font-size: 13px;
            color: #999;
            font-style: italic;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #aaa;
        }

        .brand {
            color: #28a745;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Account Verification</h2>
        <p>Great,</p>
        <p>Thank you for registering at <span class="brand">My Mini Store</span>. Please use the verification code below
            to complete the registration process:</p>

        <div class="code-container">
            <p class="code">{{ $code }}</p>
        </div>

        <p class="expiry">This code is valid for 5 minutes.</p>
        <p>If you do not fulfill this request, you can ignore this email.</p>

        <div class="footer">
            This is an automated email, please do not reply.<br>
            &copy; 2026 My Mini Store. All rights reserved.
        </div>
    </div>
</body>

</html>
