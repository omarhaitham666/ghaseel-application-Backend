<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>كود استعادة كلمة المرور</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; color: #333; text-align: center; padding: 20px; }
        .code-box { display: inline-block; background-color: #007bff; color: #fff; padding: 15px 25px; font-size: 24px; border-radius: 8px; margin: 20px 0; }
        .footer { margin-top: 30px; font-size: 14px; color: #555; }
    </style>
</head>
<body>
    <h1>مرحبًا،</h1>
    <p>كود استعادة كلمة المرور الخاص بك هو:</p>
    <div class="code-box">{{ $code }}</div>
    <p>يرجى استخدام هذا الكود لتغيير كلمة المرور الخاصة بك.</p>
    <div class="footer">
        إذا لم تطلب تغيير كلمة المرور، يمكنك تجاهل هذه الرسالة.
    </div>
</body>
</html>
