<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }
        .otp-container {
            background-color: #fff5f5;
            border: 2px dashed #ff6b6b;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 700;
            color: #ff6b6b;
            letter-spacing: 5px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ff8e53);
            color: white !important;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            margin-top: 10px;
            box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
            transition: transform 0.2s;
        }
        .footer {
            background-color: #fcfcfc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="content">
            <p>Hi {{ $name }},</p>
            <p>We received a request to reset your password. Please use the following reset code to proceed:</p>
            
            <div class="otp-container">
                <div class="otp-code">{{ $reset_code }}</div>
            </div>

            <p>You can also reset your password by clicking the button below:</p>
            
            <div style="text-align: center;">
                <a href="{{ $reset_url }}" class="button">Reset My Password</a>
            </div>

            <p style="margin-top: 30px;">If you didn't request a password reset, you can safely ignore this email. This code will expire in 10 minutes.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Asset Management System. All rights reserved.
        </div>
    </div>
</body>
</html>
