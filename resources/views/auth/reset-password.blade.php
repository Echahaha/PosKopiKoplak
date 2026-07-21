<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Sandi | Kopi Koplak POS</title>
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

        html, body { height: 100%; font-family: 'DM Sans', sans-serif; }

        body {
            min-height: 100vh;
            /* Beda dari forgot: gradient dari bawah (caramel ke espresso), bukan atas */
            background: radial-gradient(ellipse 140% 90% at 50% 110%, #7a3a10 0%, var(--espresso) 40%, var(--espresso-deep) 75%, #0f0906 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }

        /* Glow dari bawah — kayak bara kopi yang masih panas */
        body::after {
            content: '';
            position: fixed;
            bottom: -80px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 350px;
            background: radial-gradient(ellipse, rgba(201, 118, 47, 0.22) 0%, transparent 70%);
            pointer-events: none;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 80% 15%, rgba(232, 176, 75, 0.06) 0%, transparent 38%),
                radial-gradient(circle at 20% 80%, rgba(201, 118, 47, 0.07) 0%, transparent 35%);
            pointer-events: none;
        }

        /* Garis-garis diagonal tipis -- beda dari forgot yang pakai lingkaran */
        .deco-lines {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .deco-lines::before,
        .deco-lines::after {
            content: '';
            position: absolute;
            background: linear-gradient(to right, transparent, rgba(232, 176, 75, 0.05), transparent);
            height: 1px;
            width: 200%;
            left: -50%;
        }

        .deco-lines::before { top: 28%; transform: rotate(-8deg); }
        .deco-lines::after  { top: 68%; transform: rotate(-8deg); }

        /* ── KONTEN ── */
        .page-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            animation: fadeUp 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo kecil di atas ── */
        .logo-small {
            width: 180px;
            height: 180px;
            margin-bottom: 0px;
            opacity: 0.9;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4));
        }

        .logo-small img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ── Judul besar di tengah, bukan brand name ── */
        .page-eyebrow {
            font-family: 'Fredoka', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--caramel);
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .page-headline {
            font-family: 'Fredoka', sans-serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--white-cream);
            text-align: center;
            line-height: 1.15;
            margin-bottom: 6px;
            text-shadow: 0 4px 0 rgba(0,0,0,0.2);
        }

        .page-headline .accent { color: var(--honey); }

        .page-sub {
            font-size: 13px;
            font-weight: 500;
            color: rgba(253, 248, 240, 0.45);
            text-align: center;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .page-sub strong {
            color: var(--honey);
            font-weight: 700;
        }

        /* ── Alert ── */
        .alert-error {
            width: 100%;
            border-radius: 14px;
            padding: 13px 16px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 9px;
            background: rgba(209, 69, 59, 0.15);
            border: 1.5px solid rgba(209, 69, 59, 0.3);
            color: #f0807a;
            animation: alertIn 0.25s ease-out;
        }

        @keyframes alertIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Form ── */
        .form-wrap {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(232, 176, 75, 0.13);
            border-radius: 20px;
            padding: 28px 28px 24px;
            backdrop-filter: blur(10px);
        }

        .field-group { margin-bottom: 20px; }

        .field-label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: rgba(253, 248, 240, 0.5);
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
            border: 2px solid rgba(232, 176, 75, 0.18);
            border-radius: 14px;
            padding: 13px 46px 13px 46px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--white-cream);
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            -webkit-appearance: none;
        }

        .field-input::placeholder { color: rgba(253, 248, 240, 0.22); }

        .field-input:focus {
            border-color: var(--honey);
            background: rgba(255, 255, 255, 0.10);
            box-shadow: 0 0 0 4px rgba(232, 176, 75, 0.10);
        }

        .field-wrap:focus-within .field-icon {
            opacity: 0.75;
            color: var(--honey);
        }

        /* pw toggle */
        .pw-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 4px;
            cursor: pointer;
            opacity: 0.3;
            transition: opacity 0.2s;
            line-height: 0;
            color: var(--white-cream);
        }

        .pw-toggle:hover { opacity: 0.7; }

        .pw-toggle svg {
            width: 18px; height: 18px;
            stroke: currentColor; fill: none;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }

        /* ── Strength bar ── */
        .strength-wrap { margin-top: 10px; }

        .strength-bar {
            height: 4px;
            border-radius: 4px;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            border-radius: 4px;
            transition: width 0.35s ease, background 0.35s ease;
        }

        .strength-label {
            font-size: 11px;
            font-weight: 700;
            margin-top: 5px;
            letter-spacing: 0.3px;
            transition: color 0.35s;
        }

        /* ── Match indicator ── */
        .match-hint {
            font-size: 11.5px;
            font-weight: 600;
            margin-top: 6px;
            display: none;
            align-items: center;
            gap: 5px;
        }

        .match-hint.show { display: flex; }
        .match-hint.ok   { color: #7ecf95; }
        .match-hint.fail { color: #f0807a; }

        /* ── Submit ── */
        .btn-submit {
            width: 100%;
            margin-top: 6px;
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

        /* ── Footer link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 22px;
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(253, 248, 240, 0.35);
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--honey); }

        .back-link svg {
            width: 14px; height: 14px;
            stroke: currentColor; fill: none;
            stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
        }

        .page-footer {
            margin-top: 28px;
            font-size: 11px;
            font-weight: 600;
            color: rgba(253, 248, 240, 0.18);
        }

        @media (max-width: 480px) {
            .page-headline { font-size: 28px; }
            .form-wrap { padding: 22px 18px 20px; }
        }
    </style>
</head>

<body>

    <div class="deco-lines"></div>

    <div class="page-content">

        <div class="logo-small">
            <img src="{{ asset('images/logo-kopikoplak.png') }}" alt="Logo Kopi Koplak">
        </div>

        <p class="page-eyebrow">Kopi Koplak POS</p>

        <h1 class="page-headline">Bikin Sandi <span class="accent">Baru</span> 🔑</h1>
        <p class="page-sub">
            Buat akun <strong>{{ $email }}</strong><br>
            Jangan samain sama nama kucing kamu ya.
        </p>

        @if($errors->any())
            <div class="alert-error" role="alert">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="form-wrap">
            <form action="{{ route('password.update') }}" method="POST" id="resetForm" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                {{-- Password baru --}}
                <div class="field-group">
                    <label class="field-label" for="password">Sandi Baru</label>
                    <div class="field-wrap">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="11" width="14" height="10" rx="2"/>
                                <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                            </svg>
                        </span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="field-input"
                            placeholder="min. 8 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="pw-toggle" id="pwToggle1" aria-label="Tampilkan password">
                            <svg id="eye1" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="strength-wrap">
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-label" id="strengthLabel"></div>
                    </div>
                </div>

                {{-- Konfirmasi --}}
                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Ulangi Sandi Baru</label>
                    <div class="field-wrap">
                        <span class="field-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="11" width="14" height="10" rx="2"/>
                                <path d="M8 11V7a4 4 0 0 1 8 0v4"/>
                            </svg>
                        </span>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="field-input"
                            placeholder="ketik ulang sandinya"
                            required
                            autocomplete="new-password"
                        >
                        <button type="button" class="pw-toggle" id="pwToggle2" aria-label="Tampilkan password">
                            <svg id="eye2" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="match-hint" id="matchHint">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" id="matchIcon">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span id="matchText"></span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-spinner" aria-hidden="true"></span>
                    <span id="submitText">Simpan Sandi Baru 🔐</span>
                </button>
            </form>
        </div>

        <a href="{{ route('login') }}" class="back-link">
            <svg viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Balik ke login
        </a>

        <div class="page-footer">© {{ date('Y') }} Kopi Koplak · v2.0</div>

    </div>

    <script>
        // ── Toggle visibility ──
        const eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
        const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

        function makeToggle(btnId, inputId, eyeId) {
            document.getElementById(btnId).addEventListener('click', () => {
                const input  = document.getElementById(inputId);
                const eye    = document.getElementById(eyeId);
                const hidden = input.type === 'password';
                input.type   = hidden ? 'text' : 'password';
                eye.innerHTML = hidden ? eyeClosed : eyeOpen;
            });
        }
        makeToggle('pwToggle1', 'password', 'eye1');
        makeToggle('pwToggle2', 'password_confirmation', 'eye2');

        // ── Strength ──
        const pwInput    = document.getElementById('password');
        const fillEl     = document.getElementById('strengthFill');
        const labelEl    = document.getElementById('strengthLabel');

        const levels = [
            { pct: '0%',   color: 'transparent',  label: '' },
            { pct: '25%',  color: '#d1453b',       label: 'Lemah banget 😬' },
            { pct: '50%',  color: '#c9762f',       label: 'Lumayan, tapi kurang' },
            { pct: '75%',  color: '#e8b04b',       label: 'Udah oke nih 👍' },
            { pct: '100%', color: '#4a8b5c',       label: 'Mantap jiwa! 💪' },
        ];

        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            let score = 0;
            if (val.length >= 8)           score++;
            if (/[A-Z]/.test(val))         score++;
            if (/[0-9]/.test(val))         score++;
            if (/[^A-Za-z0-9]/.test(val))  score++;

            const lvl = val.length === 0 ? levels[0] : levels[score] || levels[1];
            fillEl.style.width      = lvl.pct;
            fillEl.style.background = lvl.color;
            labelEl.textContent     = lvl.label;
            labelEl.style.color     = lvl.color;

            checkMatch();
        });

        // ── Match ──
        const confirmInput = document.getElementById('password_confirmation');
        const matchHint    = document.getElementById('matchHint');
        const matchIcon    = document.getElementById('matchIcon');
        const matchText    = document.getElementById('matchText');

        const iconOk   = `<polyline points="20 6 9 17 4 12"/>`;
        const iconFail = `<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>`;

        function checkMatch() {
            const pw  = pwInput.value;
            const cfm = confirmInput.value;
            if (cfm.length === 0) {
                matchHint.classList.remove('show', 'ok', 'fail');
                return;
            }
            const ok = pw === cfm;
            matchHint.classList.add('show');
            matchHint.classList.toggle('ok',   ok);
            matchHint.classList.toggle('fail', !ok);
            matchIcon.innerHTML = ok ? iconOk : iconFail;
            matchText.textContent = ok ? 'Sandi cocok, gas!' : 'Belum sama nih...';
        }

        confirmInput.addEventListener('input', checkMatch);

        // ── Submit ──
        document.getElementById('resetForm').addEventListener('submit', function (e) {
            const pw  = pwInput.value;
            const cfm = confirmInput.value;
            if (!pw || !cfm) { e.preventDefault(); return; }

            const btn = document.getElementById('submitBtn');
            const txt = document.getElementById('submitText');
            btn.classList.add('loading');
            btn.disabled = true;
            txt.textContent = 'Lagi disimpen...';
        });
    </script>

</body>
</html>