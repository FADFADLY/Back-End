<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F8F8F8;
            text-align: right;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #E8EBF0;
        }

        .logo {
            max-width: 130px;
            height: auto;
            margin-bottom: 10px;
        }

        .content {
            padding: 25px 15px;
            text-align: center;
        }

        .otp-box {
            background-color: #E8EBF0;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 34px;
            font-weight: bold;
            color: #295163;
            letter-spacing: 6px;
            direction: ltr;
        }

        .info {
            color: #4D96B9;
            margin: 10px 0;
            font-size: 16px;
        }

        .warning {
            margin-top: 20px;
            font-size: 14px;
            color: #a94442;
            background-color: #fcebea;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 6px;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #E8EBF0;
            color: #777;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('fadfadly-logo.png') }}" alt="{{ config('app.name') }}" class="logo">
        <h2 style="color: #295163;">طلب إعادة تعيين كلمة المرور</h2>
    </div>

    <div class="content">
        <p class="info">مرحباً!</p>
        <p>لقد طلبت إعادة تعيين كلمة المرور الخاصة بحسابك في <strong>{{ config('app.name') }}</strong>.</p>
        <p>يرجى استخدام رمز التحقق التالي لإتمام العملية:</p>

        <div class="otp-box">
            <p style="margin-bottom: 10px;">رمز التحقق:</p>
            <div class="otp-code">{{ $resetCode }}</div>
        </div>

        <p class="info">صلاحية الرمز <strong>10 دقائق</strong> فقط من وقت الإرسال.</p>

        <div class="warning">
            ⚠️ هذا الرمز سري ولا يجب مشاركته مع أي شخص.
            <br>
            فريق الدعم لن يطلب منك هذا الرمز في أي وقت.
        </div>
    </div>

    <div class="footer">
        <p>تم إرسال هذه الرسالة تلقائيًا، لا ترد عليها.</p>
        <p>&copy; {{ date('Y') }} جميع الحقوق محفوظة لـ {{ config('app.name') }}</p>
    </div>
</div>
</body>
</html>
