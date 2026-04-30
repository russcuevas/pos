<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .header {
            background: linear-gradient(135deg, #d93025 0%, #b71c1c 100%);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }

        .content {
            padding: 40px;
            color: #334155;
            line-height: 1.6;
        }

        .button-wrap {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            background-color: #d93025;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            display: inline-block;
        }

        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">Sammer Store</h1>
        </div>
        <div class="content">
            <h2 style="margin-top: 0;">Hi {{ $customer->fullname }},</h2>
            <p>Welcome to our Sammer Store! We're excited to have you on board. Please click the button below to verify
                your email address and activate your account.</p>

            <div class="button-wrap">
                <a href="{{ $url }}" class="button">Verify Email Address</a>
            </div>

            <p>If you did not create an account, no further action is required.</p>
            <p>Thanks,<br>Sammer Store</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Sammer Store
        </div>
    </div>
</body>

</html>
