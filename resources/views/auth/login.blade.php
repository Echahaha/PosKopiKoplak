<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kopi Koplak POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* ── Palet "Ngoplak" -- coklat kopi warm & earthy ── */
            --espresso: #3d2817;
            /* coklat espresso gelap, bg panel kiri */
            --espresso-deep: #2a1a0e;
            /* lebih gelap lagi, untuk gradient */
            --cream: #f5ead8;
            /* krem susu, bg form */
            --cream-soft: #faf3e7;
            /* krem lebih terang, bg input */
            --caramel: #c9762f;
            /* oranye karamel, aksen utama / CTA */
            --caramel-dark: #a85f22;
            /* karamel gelap, hover state */
            --honey: #e8b04b;
            /* kuning madu, highlight/accent kedua */
            --ink: #2b1c10;
            /* coklat nyaris hitam, teks utama */
            --ink-soft: #6b5642;
            /* coklat lembut, teks sekunder */
            --danger: #d1453b;
            --success: #4a8b5c;
            --white-cream: #fdf8f0;
            /* putih gading, teks di atas gelap */
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            background: var(--cream);
            display: flex;
            align-items: stretch;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ─── LEFT PANEL: Maskot & Personality ─────────────────────── */
        .panel-left {
            flex: 0 0 46%;
            position: relative;
            background: radial-gradient(ellipse 140% 100% at 30% 0%, var(--espresso-deep) 0%, var(--espresso) 55%, #1f140a 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 44px;
            overflow: hidden;
        }

        /* Pola titik-titik "kopi tumpah" -- bukan bean rapi simetris, tapi
           tersebar acak seperti tetesan kopi yang jatuh santai */
        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 12% 22%, rgba(232, 176, 75, 0.10) 0%, transparent 40%),
                radial-gradient(circle at 85% 15%, rgba(201, 118, 47, 0.14) 0%, transparent 38%),
                radial-gradient(circle at 78% 80%, rgba(232, 176, 75, 0.08) 0%, transparent 45%),
                radial-gradient(circle at 8% 85%, rgba(201, 118, 47, 0.10) 0%, transparent 42%);
        }

        .panel-left-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* ── MASKOT: cangkir kopi dengan muka jenaka, miring santai ── */
        .mascot-wrap {
            width: 320px;
            height: 320px;
            margin: 0 auto 0px;
            position: relative;
            animation: mascotWobble 3.4s ease-in-out infinite;
        }

        @keyframes mascotWobble {

            0%,
            100% {
                transform: rotate(-3deg) translateY(0);
            }

            50% {
                transform: rotate(3deg) translateY(-6px);
            }
        }

        .mascot-svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 10px 24px rgba(0, 0, 0, 0.35));
        }

        /* Uap kopi yang naik dengan goyangan, posisinya pas di atas mulut cangkir */
        .steam-wiggle {
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 11px;
            z-index: 3;
        }

        .steam-w {
            width: 4px;
            height: 22px;
            border-radius: 4px;
            background: linear-gradient(to top, rgba(232, 176, 75, 0.55), transparent);
            animation: steamRise 2.4s ease-in-out infinite;
            transform-origin: bottom center;
            transform: scaleY(0.6) translateY(-4px);
        }

        .steam-w:nth-child(2) {
            animation-delay: 0.5s;
            height: 30px;
        }

        .steam-w:nth-child(3) {
            animation-delay: 1s;
        }

        @keyframes steamRise {
            0% {
                transform: scaleY(0) translateX(0) rotate(0deg);
                opacity: 0;
            }

            25% {
                opacity: 1;
            }

            60% {
                transform: scaleY(1) translateX(4px) rotate(8deg) translateY(-16px);
            }

            100% {
                transform: scaleY(1) translateX(-3px) rotate(-6deg) translateY(-32px);
                opacity: 0;
            }
        }

        .panel-brand-name {
            font-family: 'Fredoka', sans-serif;
            font-size: 46px;
            font-weight: 700;
            color: var(--white-cream);
            letter-spacing: -0.5px;
            line-height: 0.95;
            margin-bottom: 6px;
            margin-top: -60px;
            text-shadow: 0 4px 0 rgba(0, 0, 0, 0.2);
        }

        .panel-brand-name .accent-koplak {
            color: var(--honey);
            display: inline-block;
            transform: rotate(-2deg);
        }

        .panel-brand-sub {
            font-family: 'Fredoka', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--caramel);
            background: rgba(232, 176, 75, 0.15);
            border: 1.5px dashed rgba(232, 176, 75, 0.4);
            display: inline-block;
            padding: 5px 16px;
            border-radius: 100px;
            letter-spacing: 0.5px;
            margin-bottom: 36px;
            transform: rotate(-1.5deg);
        }

        /* Speech bubble -- microcopy ngoplak, bukan quote formal */
        .panel-quote {
            font-family: 'Fredoka', sans-serif;
            font-size: 15px;
            font-weight: 500;
            color: var(--white-cream);
            line-height: 1.5;
            max-width: 250px;
            position: relative;
            background: rgba(255, 255, 255, 0.06);
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            border-radius: 18px;
            padding: 16px 20px;
            margin: 0 auto;
        }

        .panel-quote::after {
            content: '';
            position: absolute;
            top: -7px;
            left: 38px;
            width: 14px;
            height: 14px;
            background: rgba(255, 255, 255, 0.06);
            border-left: 1.5px solid rgba(255, 255, 255, 0.12);
            border-top: 1.5px solid rgba(255, 255, 255, 0.12);
            transform: rotate(45deg);
            border-radius: 2px 0 0 0;
        }

        .panel-quote span {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            font-size: 11px;
            letter-spacing: 0.4px;
            color: var(--honey);
            margin-top: 8px;
            opacity: 0.85;
        }

        /* Mini stat pills -- elemen ringan pengisi komposisi, tetap playful
           dan relevan (klaim kecil khas tone "ngoplak"), bukan dekorasi kosong */
        .mini-stats-row {
            display: flex;
            gap: 22px;
            justify-content: center;
            margin-top: 26px;
        }

        .mini-stat {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.55);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .mini-stat strong {
            color: var(--white-cream);
            font-weight: 700;
        }

        /* ─── RIGHT PANEL ─────────────────────────────────────────── */
        .panel-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 40px;
            position: relative;
            background: var(--cream);
        }

        .panel-right::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(201, 118, 47, 0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
            animation: slideUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-heading {
            font-family: 'Fredoka', sans-serif;
            font-size: 30px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .login-subheading {
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink-soft);
            margin-bottom: 32px;
        }

        /* ─── ALERT ─────────────────────────────────────────────── */
        .alert-error,
        .alert-success {
            border-radius: 13px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: alertIn 0.25s ease-out;
        }

        @keyframes alertIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: rgba(209, 69, 59, 0.1);
            border: 1.5px solid rgba(209, 69, 59, 0.25);
            color: var(--danger);
        }

        .alert-success {
            background: rgba(74, 139, 92, 0.1);
            border: 1.5px solid rgba(74, 139, 92, 0.25);
            color: var(--success);
        }

        .alert-error::before,
        .alert-success::before {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .alert-error::before {
            content: '!';
            border: 1.5px solid rgba(209, 69, 59, 0.4);
        }

        .alert-success::before {
            content: '✓';
            border: 1.5px solid rgba(74, 139, 92, 0.4);
        }

        /* ─── FORM FIELDS ────────────────────────────────────────── */
        .field-group {
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: var(--ink-soft);
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.4;
            pointer-events: none;
            transition: opacity 0.2s, color 0.2s;
            color: var(--ink-soft);
        }

        .field-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .field-input {
            width: 100%;
            background: var(--cream-soft);
            border: 2px solid #eadfca;
            border-radius: 14px;
            padding: 13px 16px 13px 46px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s, transform 0.1s;
            -webkit-appearance: none;
        }

        .field-input::placeholder {
            color: #c2ad8d;
        }

        .field-input:focus {
            border-color: var(--caramel);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(201, 118, 47, 0.12);
        }

        .field-wrap:focus-within .field-icon {
            opacity: 0.8;
            color: var(--caramel);
        }

        /* State error per-field */
        .field-input.has-error {
            border-color: var(--danger);
            background: rgba(209, 69, 59, 0.04);
        }

        .field-input.has-error:focus {
            box-shadow: 0 0 0 4px rgba(209, 69, 59, 0.1);
        }

        .field-wrap.has-error .field-icon {
            opacity: 0.8;
            color: var(--danger);
        }

        .field-error-text {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--danger);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pw-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 4px;
            cursor: pointer;
            opacity: 0.35;
            transition: opacity 0.2s;
            line-height: 0;
            color: var(--ink-soft);
        }

        .pw-toggle:hover {
            opacity: 0.7;
        }

        .pw-toggle:focus-visible {
            outline: 2px solid var(--caramel);
            outline-offset: 2px;
            opacity: 0.9;
        }

        .pw-toggle svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Indikator Caps Lock aktif */
        .capslock-warning {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--caramel-dark);
            background: rgba(232, 176, 75, 0.18);
            border: 1.5px solid rgba(232, 176, 75, 0.4);
            border-radius: 10px;
            padding: 6px 11px;
            margin-top: 8px;
        }

        .capslock-warning.show {
            display: flex;
        }

        /* ─── SUBMIT BUTTON ──────────────────────────────────────── */
        .btn-submit {
            width: 100%;
            margin-top: 6px;
            padding: 15px 24px;
            background: var(--caramel);
            border: none;
            border-bottom: 4px solid var(--caramel-dark);
            border-radius: 14px;
            font-family: 'Fredoka', sans-serif;
            font-size: 14.5px;
            font-weight: 600;
            letter-spacing: 0.3px;
            color: var(--white-cream);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.12s, box-shadow 0.2s, opacity 0.2s, background 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            background: var(--caramel-dark);
            transform: translateY(-2px);
        }

        .btn-submit:active {
            transform: translateY(1px);
            border-bottom-width: 2px;
        }

        .btn-submit:disabled,
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }

        .btn-spinner {
            display: none;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-top-color: var(--white-cream);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .btn-submit.loading .btn-spinner {
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ─── FOOTER ─────────────────────────────────────────────── */
        .login-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 22px;
            border-top: 2px dashed #e8dcc4;
        }

        .footer-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--ink-soft);
        }

        .live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(74, 139, 92, 0.4);
            }

            50% {
                box-shadow: 0 0 0 4px rgba(74, 139, 92, 0);
            }
        }

        .footer-version {
            font-size: 11px;
            font-weight: 600;
            color: #b3a489;
        }

        /* ─── RESPONSIVE ─────────────────────────────────────────── */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
                overflow: auto;
            }

            .panel-left {
                flex: 0 0 auto;
                padding: 36px 24px;
                min-height: 260px;
            }

            .panel-brand-name {
                font-size: 34px;
            }

            .mascot-wrap {
                width: 200px;
                height: 200px;
                margin-bottom: 16px;
            }

            .panel-quote {
                display: none;
            }

            .panel-right {
                padding: 36px 24px;
            }
        }
    </style>
</head>

<body>

    {{-- ─── LEFT PANEL: Maskot Ngoplak ─────────────────────────── --}}
    <div class="panel-left">
        <div class="panel-left-content">

            <div class="mascot-wrap">
                <img
                    src="{{ asset('images/logo-kopikoplak.png') }}"
                    alt="Logo Kopi Koplak"
                    class="mascot-svg"
                    style="width:100%; height:100%; object-fit:contain;">
            </div>

            <h1 class="panel-brand-name">KOPI <span class="accent-koplak">KOPLAK</span></h1>
            <div class="panel-brand-sub">☕ Point of Sale Ngopi-Ngopi</div>

            <div class="panel-quote">
                "Kerja keraslah sampai tetangga mengira kamu miara tuyul, padahal cuma barista yang jarang tidur."
                <span>— Kata Barista Senior</span>
            </div>

            <div class="mini-stats-row">
                <div class="mini-stat"><strong>1.2k+</strong> Kopi Tersaji</div>
                <div class="mini-stat"><strong>3.5k+</strong> Senyum Tersungging</div>
                <div class="mini-stat"><strong>99%</strong> Pelanggan Puas</div>
            </div>
        </div>
    </div>

    {{-- ─── RIGHT PANEL ─────────────────────────────────── --}}
    <div class="panel-right">
        <div class="login-box">
            <h2 class="login-heading">Woi, masuk dulu!</h2>
            <p class="login-subheading">Sebelum ngasir, login dulu yuk.</p>

            {{-- Alert sukses (misal setelah logout / reset password) --}}
            @if(session('success'))
            <div class="alert-success" role="status" aria-live="polite">{{ session('success') }}</div>
            @endif

            {{-- Alert error umum.
                 AuthController kamu pakai withErrors(), BUKAN session('error').
                 Error login ("Username atau password salah", akun nonaktif, dst)
                 masuk ke $errors->first('username'), bukan session('error').
                 session('error') tetap disertakan untuk jaga-jaga kalau dipakai
                 di tempat lain. --}}
            @if(session('error'))
            <div class="alert-error" role="alert" aria-live="assertive">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm" novalidate>
                @csrf

                <div class="field-group">
                    <label class="field-label" for="username">Username</label>
                    <div class="field-wrap {{ $errors->has('username') ? 'has-error' : '' }}">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                            </svg>
                        </span>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            class="field-input {{ $errors->has('username') ? 'has-error' : '' }}"
                            placeholder="username kamu"
                            value="{{ old('username') }}"
                            required
                            autocomplete="username"
                            aria-describedby="username-error"
                            {{ $errors->has('username') ? '' : 'autofocus' }}>
                    </div>
                    {{-- Error login dari AuthController selalu masuk ke key 'username'. --}}
                    @error('username')
                    <p class="field-error-text" id="username-error" role="alert">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap {{ $errors->has('password') ? 'has-error' : '' }}">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="11" width="14" height="10" rx="2" />
                                <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                            </svg>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="field-input {{ $errors->has('password') ? 'has-error' : '' }}"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            style="padding-right: 46px;"
                            aria-describedby="password-error capslock-hint"
                            {{ $errors->has('username') && !$errors->has('password') ? 'autofocus' : '' }}>
                        <button type="button" class="pw-toggle" id="pwToggle" aria-label="Tampilkan password">
                            <svg id="eyeIcon" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="field-error-text" id="password-error" role="alert">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="capslock-warning" id="capslockWarning" role="status" aria-live="polite">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l9 7-9 7-9-7 9-7z" />
                            <path d="M12 22v-8" />
                        </svg>
                        <span id="capslock-hint">Eh, Caps Lock nyala tuh!</span>
                    </div>
                </div>

                <div style="text-align:right; margin:-6px 0 18px;">
                    <a href="{{ route('password.request') }}"
                        style="font-size:12px; font-weight:600; color:#9a8567; text-decoration:none; letter-spacing:0.2px; transition:color 0.2s;"
                        onmouseover="this.style.color='#c9762f'"
                        onmouseout="this.style.color='#9a8567'">
                        Lupa sandi nih
                    </a>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-spinner" aria-hidden="true"></span>
                    <span id="submitBtnText">Gas, Masuk!</span>
                </button>
            </form>

            <div class="login-footer">
                <div class="footer-badge">
                    <div class="live-dot"></div>
                    Mesin lagi nyala
                </div>
                <span class="footer-version">© {{ date('Y') }} Kopi Koplak · v2.0</span>
            </div>
        </div>
    </div>

    <script>
        // ── Toggle password visibility ──
        const pwToggle = document.getElementById('pwToggle');
        const pwInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        const eyeOpen = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

        pwToggle.addEventListener('click', () => {
            const isHidden = pwInput.type === 'password';
            pwInput.type = isHidden ? 'text' : 'password';
            eyeIcon.innerHTML = isHidden ? eyeClosed : eyeOpen;
            pwToggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
        });

        // ── Deteksi Caps Lock aktif ──
        const capslockWarning = document.getElementById('capslockWarning');

        function checkCapsLock(e) {
            if (typeof e.getModifierState === 'function') {
                const isCapsOn = e.getModifierState('CapsLock');
                capslockWarning.classList.toggle('show', isCapsOn);
            }
        }

        pwInput.addEventListener('keydown', checkCapsLock);
        pwInput.addEventListener('keyup', checkCapsLock);
        pwInput.addEventListener('blur', () => capslockWarning.classList.remove('show'));

        // ── Validasi dasar sebelum submit + loading state ──
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitBtnText');
        const usernameInp = document.getElementById('username');

        function setFieldError(input, hasError) {
            input.classList.toggle('has-error', hasError);
            const wrap = input.closest('.field-wrap');
            if (wrap) wrap.classList.toggle('has-error', hasError);
        }

        loginForm.addEventListener('submit', function(e) {
            let valid = true;

            if (usernameInp.value.trim() === '') {
                setFieldError(usernameInp, true);
                valid = false;
            }
            if (pwInput.value.trim() === '') {
                setFieldError(pwInput, true);
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                (usernameInp.value.trim() === '' ? usernameInp : pwInput).focus();
                return;
            }

            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitText.textContent = 'Bentar, dicek dulu...';
        });

        [usernameInp, pwInput].forEach(function(input) {
            input.addEventListener('input', function() {
                if (input.value.trim() !== '') {
                    setFieldError(input, false);
                }
            });
        });
    </script>
</body>

</html>