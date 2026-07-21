<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px 20px;
        }

        .wrapper {
            max-width: 520px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 36px 40px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3498db, #2ecc71, #3498db);
        }

        /* Ganti CSS .logo-box dengan ini */
        .logo-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(145deg, #3498db, #2980b9);
            border-radius: 16px;
            margin: 0 auto 16px;
            display: table;
        }

        .logo-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -1px;
        }

        .header h1 {
            color: #fff;
            font-size: 22px;
            letter-spacing: 3px;
            margin-bottom: 4px;
        }

        .header p {
            color: rgba(93, 173, 226, 0.8);
            font-size: 10px;
            letter-spacing: 5px;
        }

        .body {
            padding: 40px;
        }

        .greeting {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .message {
            font-size: 14px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .btn-wrap {
            text-align: center;
            margin: 28px 0;
        }

        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: #fff !important;
            text-decoration: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .warning {
            background: #fef9e7;
            border-left: 3px solid #f1c40f;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            margin: 24px 0;
        }

        .warning p {
            font-size: 12px;
            color: #856404;
            line-height: 1.6;
        }

        .url-section {
            margin-top: 24px;
        }

        .url-label {
            font-size: 11px;
            color: #aaa;
            margin-bottom: 6px;
        }

        .url-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 11px;
            color: #888;
            word-break: break-all;
            line-height: 1.5;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #f0f0f0;
        }

        .footer p {
            font-size: 11px;
            color: #bbb;
            line-height: 1.6;
        }

        .footer strong {
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">

            <div class="header">
                <div class="logo-box">
                    <span class="logo-text">KK</span>
                </div>
                <h1>KOPI KOPLAK</h1>
                <p>POINT OF SALE SYSTEM</p>
            </div>

            <div class="body">
                <p class="greeting">Halo, {{ $user->name }}! 👋</p>
                <p class="message">
                    Kami menerima permintaan untuk mereset password akun kamu di
                    <strong>Kopi Koplak POS</strong>. Klik tombol di bawah ini untuk
                    membuat password baru:
                </p>

                <div class="btn-wrap">
                    <a href="{{ $resetUrl }}" class="btn">Reset Password Sekarang</a>
                </div>

                <div class="warning">
                    <p>
                        ⏱️ <strong>Link ini hanya berlaku 60 menit</strong> sejak email dikirimkan.<br>
                        Jika kamu tidak meminta reset password, abaikan email ini —
                        akun kamu tetap aman dan tidak ada perubahan yang dibuat.
                    </p>
                </div>

                <div class="url-section">
                    <p class="url-label">Jika tombol tidak bisa diklik, salin link berikut ke browser:</p>
                    <div class="url-box">{{ $resetUrl }}</div>
                </div>
            </div>

            <div class="footer">
                <p>
                    Email ini dikirim otomatis oleh sistem <strong>Kopi Koplak POS</strong>.<br>
                    Mohon tidak membalas email ini.
                </p>
                <p style="margin-top:8px;">© 2025 Kopi Koplak · All rights reserved</p>
            </div>

        </div>
    </div>
</body>

</html>