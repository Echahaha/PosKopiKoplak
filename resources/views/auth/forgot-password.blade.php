<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi | Kopi Koplak POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --espresso: #3d2817;
            --espresso-deep: #2a1a0e;
            --cream: #f5ead8;
            --cream-soft: #faf3e7;
            --caramel: #c9762f;
            --caramel-dark: #a85f22;
            --honey: #e8b04b;
            --ink: #2b1c10;
            --ink-soft: #6b5642;
            --danger: #d1453b;
            --success: #4a8b5c;
            --white-cream: #fdf8f0;
        }

        html, body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: radial-gradient(ellipse 120% 80% at 50% 0%, var(--espresso) 0%, var(--espresso-deep) 45%, #0f0906 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }

        /* ── Glow bulat di tengah bawah, kayak cahaya lampu kafe ── */
        body::after {
            content: '';
            position: fixed;
            bottom: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(201, 118, 47, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Noise texture overlay ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 15% 25%, rgba(232, 176, 75, 0.07) 0%, transparent 40%),
                radial-gradient(circle at 85% 70%, rgba(201, 118, 47, 0.06) 0%, transparent 38%);
            pointer-events: none;
        }

        /* ── Lingkaran besar dekorasi, kayak ring cangkir kopi ── */
        .deco-ring {
            position: fixed;
            border-radius: 50%;
            border: 1px solid rgba(232, 176, 75, 0.08);
            pointer-events: none;
        }
        .deco-ring-1 { width: 500px; height: 500px; top: -180px; right: -180px; }
        .deco-ring-2 { width: 360px; height: 360px; bottom: -130px; left: -130px; border-color: rgba(201, 118, 47, 0.07); }
        .deco-ring-3 { width: 220px; height: 220px; top: 60%; right: 5%; border-color: rgba(232, 176, 75, 0.05); }

        /* ── KONTEN UTAMA ── */
        .page-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            animation: fadeUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .logo-wrap {
            width: 160px;
            height: 160px;
            margin-bottom: 0px;
            animation: logoWobble 3.4s ease-in-out infinite;
        }

        @keyframes logoWobble {
            0%, 100% { transform: rotate(-2deg) translateY(0); }
            50%       { transform: rotate(2deg) translateY(-6px); }
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 8px 20px rgba(0,0,0,0.5));
        }

        /* ── Brand ── */
        .brand-name {
            font-family: 'Fredoka', sans-serif;
            font-size: 38px;
            font-weight: 700;
            color: var(--white-cream);
            letter-spacing: -0.5px;
            line-height: 1;
            margin-bottom: 4px;
            text-shadow: 0 4px 0 rgba(0,0,0,0.25);
        }

        .brand-name .accent { color: var(--honey); }

        /* ── Headline utama halaman ── */
        .page-headline {
            font-family: 'Fredoka', sans-serif;
            font-size: 22px;
            font-weight: 600;
            color: var(--honey);
            margin-top: 28px;
            margin-bottom: 6px;
            text-align: center;
        }

        .page-sub {
            font-size: 13px;
            font-weight: 500;
            color: rgba(253, 248, 240, 0.5);
            text-align: center;
            margin-bottom: 28px;
            line-height: 1.6;
            max-width: 320px;
        }

        /* ── Alert ── */
        .alert-success, .alert-error {
            width: 100%;
            border-radius: 14px;
            padding: 13px 16px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 9px;
            animation: alertIn 0.25s ease-out;
        }

        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: rgba(74, 139, 92, 0.15);
            border: 1.5px solid rgba(74, 139, 92, 0.3);
            color: #7ecf95;
        }

        .alert-error {
            background: rgba(209, 69, 59, 0.15);
            border: 1.5px solid rgba(209, 69, 59, 0.3);
            color: #f0807a;
        }

        /* ── Form area ── */
        .form-wrap {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1.5px solid rgba(232, 176, 75, 0.15);
            border-radius: 20px;
            padding: 28px 28px 24px;
            backdrop-filter: blur(8px);
        }

        .field-group { margin-bottom: 18px; }

        .field-label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: rgba(253, 248, 240, 0.55);
            margin-bottom: 8px;
        }

        .field-wrap { position: relative; }

        .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.35;
            pointer-events: none;
            transition: opacity 0.2s, color 0.2s;
            color: var(--white-cream);
        }

        .field-icon svg {
            width: 18px; height: 18px;
            stroke: currentColor; fill: none;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }

        .field-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.07);
            border: 2px solid rgba(232, 176, 75, 0.2);
            border-radius: 14px;
            padding: 13px 16px 13px 46px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--white-cream);
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .field-input::placeholder { color: rgba(253, 248, 240, 0.25); }

        .field-input:focus {
            border-color: var(--honey);
            background: rgba(255, 255, 255, 0.10);
            box-shadow: 0 0 0 4px rgba(232, 176, 75, 0.12);
        }

        .field-wrap:focus-within .field-icon {
            opacity: 0.75;
            color: var(--honey);
        }

        .field-error-text {
            font-size: 11.5px;
            font-weight: 600;
            color: #f0807a;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            margin-top: 4px;
            padding: 15px 24px;
            background: var(--caramel);
            border: none;
            border-bottom: 4px solid var(--caramel-dark);
            border-radius: 14px;
            font-family: 'Fredoka', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: var(--white-cream);
            cursor: pointer;
            transition: transform 0.12s, background 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover { background: var(--caramel-dark); transform: translateY(-2px); }
        .btn-submit:active { transform: translateY(1px); border-bottom-width: 2px; }
        .btn-submit.loading { pointer-events: none; opacity: 0.75; }

        .btn-spinner {
            display: none;
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: var(--white-cream);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .btn-submit.loading .btn-spinner { display: inline-block; }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 22px;
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(253, 248, 240, 0.4);
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--honey); }

        .back-link svg {
            width: 14px; height: 14px;
            stroke: currentColor; fill: none;
            stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
        }

        /* ── Footer ── */
        .page-footer {
            margin-top: 36px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(253, 248, 240, 0.2);
            letter-spacing: 0.3px;
        }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .logo-wrap { width: 88px; height: 88px; }
            .brand-name { font-size: 30px; }
            .form-wrap { padding: 24px 20px 20px; }
        }
    </style>
</head>

<body>

    <div class="deco-ring deco-ring-1"></div>
    <div class="deco-ring deco-ring-2"></div>
    <div class="deco-ring deco-ring-3"></div>

    <div class="page-content">

        <div class="logo-wrap">
            <img src="{{ asset('images/logo-kopikoplak.png') }}" alt="Logo Kopi Koplak">
        </div>

        <div class="brand-name">KOPI <span class="accent">KOPLAK</span></div>

        <p class="page-headline">Aduh, lupa sandi lagi? 🤦</p>
        <p class="page-sub">Gapapa, namanya juga manusia. Ketik email kamu di bawah, nanti kita kirimin link buat bikin sandi baru.</p>

        @if(session('status'))
            <div class="alert-success" role="status" aria-live="polite">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->has('email'))
            <div class="alert-error" role="alert" aria-live="assertive">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first('email') }}</span>
            </div>
        @endif

        <div class="form-wrap">
            <form action="{{ route('password.email') }}" method="POST" id="forgotForm" novalidate>
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <div class="field-wrap">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="2" y="4" width="20" height="16" rx="2"/>
                                <path d="m2 7 10 7 10-7"/>
                            </svg>
                        </span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="field-input"
                            placeholder="email@kopikoplak.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                        >
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-spinner" aria-hidden="true"></span>
                    <span id="submitText">Kirim Link Reset</span>
                </button>
            </form>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            <svg viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Eh, inget sandi? Balik login aja
        </a>

        <div class="page-footer">© {{ date('Y') }} Kopi Koplak · v2.0</div>

    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function (e) {
            const email = document.getElementById('email').value.trim();
            const btn   = document.getElementById('submitBtn');
            const txt   = document.getElementById('submitText');

            if (!email) {
                e.preventDefault();
                document.getElementById('email').focus();
                return;
            }

            btn.classList.add('loading');
            btn.disabled = true;
            txt.textContent = 'Lagi dikirim, sabar ya...';
        });
    </script>

</body>
</html>