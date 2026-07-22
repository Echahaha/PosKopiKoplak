<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Kopi Koplak POS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Chart.js dipindah ke <head> (defer) supaya sudah siap SEBELUM
         script di halaman child (misal home.blade.php) dieksekusi.
         Sebelumnya Chart.js di-load di akhir <body>, yang berisiko race
         condition kalau halaman child memanggil `new Chart(...)` lebih
         cepat daripada library-nya selesai di-load. --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

    <style>
        /* ── RESET & BASE ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --gold: #c8a96e;
            --gold-light: #faf0dc;
            --gold-dark: #a3834a;
            --sidebar-bg: #1a1f2e;
            --sidebar-border: rgba(255, 255, 255, 0.07);
            --nav-hover: rgba(200, 169, 110, 0.13);
            --nav-active: rgba(200, 169, 110, 0.18);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            color: #1a1f2e;
            min-height: 100vh;
        }

        /* ── LAYOUT ── */
        .pos-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ─────────────────────────────
           SIDEBAR
        ───────────────────────────── */
        .sidebar {
            width: 240px;
            flex-shrink: 0;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1040;
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            overflow-y: auto;
        }

        /* brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            text-decoration: none;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--gold);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon i {
            font-size: 19px;
            color: var(--sidebar-bg);
        }

        .brand-text {
            line-height: 1.2;
        }

        .brand-name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .3px;
        }

        .brand-sub {
            font-size: 10px;
            font-weight: 500;
            color: rgba(255, 255, 255, .35);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* nav */
        .sidebar-nav {
            padding: 12px 10px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-section-label {
            font-size: 9.5px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .25);
            padding: 12px 10px 5px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255, 255, 255, .5);
            font-size: 13.5px;
            font-weight: 400;
            text-decoration: none;
            transition: background .2s, color .2s;
            margin-bottom: 1px;
            position: relative;
        }

        .nav-item i {
            font-size: 17px;
            width: 20px;
            flex-shrink: 0;
        }

        .nav-item:hover {
            background: var(--nav-hover);
            color: var(--gold);
            text-decoration: none;
        }

        .nav-item.active {
            background: var(--nav-active);
            color: var(--gold);
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3px;
            background: var(--gold);
            border-radius: 0 3px 3px 0;
        }

        /* Focus state untuk keyboard navigation (aksesibilitas) */
        .nav-item:focus-visible,
        .nav-logout:focus-visible,
        .topbar-icon-btn:focus-visible,
        .sidebar-brand:focus-visible {
            outline: 2px solid var(--gold);
            outline-offset: 2px;
        }

        .nav-divider {
            height: 1px;
            background: var(--sidebar-border);
            margin: 10px 8px;
        }

        .nav-spacer {
            flex: 1;
        }

        .nav-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            color: rgba(220, 70, 70, .65);
            font-size: 13px;
            cursor: pointer;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            transition: background .2s, color .2s;
            margin: 4px 0 8px;
        }

        .nav-logout i {
            font-size: 17px;
            width: 20px;
        }

        .nav-logout:hover {
            background: rgba(220, 70, 70, .1);
            color: #ff6b6b;
        }

        /* sidebar scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .1);
            border-radius: 4px;
        }

        /* ─────────────────────────────
           MAIN AREA
        ───────────────────────────── */
        .main-area {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* topbar */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e8ebf0;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-greeting {
            font-size: 15px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 2px;
        }

        .topbar-meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .role-badge {
            background: var(--sidebar-bg);
            color: var(--gold);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 4px;
        }

        .topbar-sub {
            font-size: 12.5px;
            color: #6b7088;
            /* dipergelap dari #8a8fa8 untuk kontras */
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            background: #f5f7fa;
            border: 1px solid #e8ebf0;
            border-radius: 20px;
            padding: 7px 14px;
            font-size: 12.5px;
            color: #4a4f68;
            /* dipergelap dari #5a607a */
            font-weight: 500;
        }

        .date-pill i {
            color: var(--gold);
            font-size: 15px;
        }

        .topbar-icon-btn {
            width: 36px;
            height: 36px;
            background: #f5f7fa;
            border: 1px solid #e8ebf0;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
            position: relative;
        }

        .topbar-icon-btn i {
            font-size: 18px;
            color: #4a4f68;
        }

        .topbar-icon-btn:hover {
            background: #eaecf3;
        }

        /* ── NOTIFICATION DROPDOWN ── */
        .notif-wrap {
            position: relative;
        }

        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            border-radius: 999px;
            background: #d64545;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            line-height: 16px;
            text-align: center;
            border: 2px solid #fff;
        }

        .notif-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            max-width: calc(100vw - 32px);
            background: #fff;
            border: 1px solid #e8ebf0;
            border-radius: var(--radius-md);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .12);
            z-index: 1500;
            overflow: hidden;
        }

        .notif-dropdown.show {
            display: block;
            animation: kk-modal-in .15s ease-out;
        }

        .notif-dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f1f5;
            font-size: 13px;
            font-weight: 700;
            color: #1a1f2e;
        }

        .notif-dropdown-list {
            max-height: 320px;
            overflow-y: auto;
        }

        .notif-item {
            display: flex;
            gap: 10px;
            padding: 12px 16px;
            text-decoration: none;
            color: inherit;
            border-bottom: 1px solid #f5f6fa;
            transition: background .15s;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: #fafbfd;
        }

        .notif-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
        }

        .notif-item-icon.notif-danger {
            background: #fee8e8;
            color: #b92525;
        }

        .notif-item-icon.notif-warning {
            background: #fef4e0;
            color: #a16800;
        }

        .notif-item-icon.notif-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .notif-item-title {
            font-size: 13px;
            font-weight: 600;
            color: #1a1f2e;
            margin-bottom: 2px;
        }

        .notif-item-subtitle {
            font-size: 12px;
            color: #6b7088;
        }

        .notif-empty {
            padding: 28px 16px;
            text-align: center;
            color: #8a8fa8;
            font-size: 13px;
        }

        .notif-empty i {
            font-size: 24px;
            display: block;
            margin: 0 auto 8px;
            color: #c8cdd9;
        }

        /* Utility: sembunyikan elemen tanpa menghapusnya dari DOM.
           Dipakai oleh badge notifikasi supaya JS polling tinggal
           toggle class ini, bukan insert/hapus elemen. */
        .kk-hidden {
            display: none !important;
        }

        /* hamburger (mobile) */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }

        .hamburger-btn i {
            font-size: 22px;
            color: #1a1f2e;
        }

        /* content */
        .page-content {
            flex: 1;
            padding: 24px 28px;
        }

        /* ── PAGE LOADING BAR (progress bar tipis di atas saat navigasi) ── */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: var(--gold);
            z-index: 2000;
            transition: width .25s ease-out, opacity .3s ease-out;
            opacity: 0;
        }

        .page-loader.active {
            opacity: 1;
        }

        /* ── ALERT OVERRIDE ── */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 500;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            animation: kk-alert-in .25s ease-out;
        }

        @keyframes kk-alert-in {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-close-btn {
            background: none;
            border: none;
            cursor: pointer;
            opacity: .5;
            font-size: 16px;
            line-height: 1;
            padding: 0 0 0 8px;
            color: inherit;
            flex-shrink: 0;
        }

        .alert-close-btn:hover {
            opacity: 1;
        }

        .alert-danger {
            background: #fee8e8;
            color: #b92525;
        }

        .alert-success {
            background: #e4f5ec;
            color: #1e6e40;
        }

        .alert-warning {
            background: #fef4e0;
            color: #a16800;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: #fff;
            border: 1px solid #e8ebf0;
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-icon i {
            font-size: 18px;
        }

        .icon-gold {
            background: var(--gold-light);
        }

        .icon-gold i {
            color: var(--gold-dark);
        }

        .icon-green {
            background: #eaf3de;
        }

        .icon-green i {
            color: #2f7d4f;
        }

        .icon-red {
            background: #fee8e8;
        }

        .icon-red i {
            color: #b92525;
        }

        .icon-blue {
            background: #e4edf8;
        }

        .icon-blue i {
            color: #185fa5;
        }

        .stat-label {
            font-size: 11.5px;
            font-weight: 600;
            color: #6b7088;
            /* dipergelap dari #8a8fa8 */
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #1a1f2e;
            margin-bottom: 4px;
        }

        .stat-delta {
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .delta-up {
            color: #2f7d4f;
        }

        .delta-down {
            color: #b92525;
        }

        .delta-warn {
            color: var(--gold-dark);
        }

        /* ── PANEL (chart/table container) ── */
        .panel {
            background: #fff;
            border: 1px solid #e8ebf0;
            border-radius: var(--radius-lg);
            padding: 20px 22px;
        }

        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .panel-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f2e;
        }

        .panel-tag {
            font-size: 11px;
            color: #6b7088;
            background: #f5f7fa;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: 500;
        }

        /* ── SIDEBAR OVERLAY (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 1039;
        }

        /* ── LOGOUT CONFIRM MODAL ── */
        .kk-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 2100;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .kk-modal-backdrop.show {
            display: flex;
        }

        .kk-modal-box {
            background: #fff;
            border-radius: var(--radius-lg);
            padding: 24px;
            max-width: 340px;
            width: 100%;
            text-align: center;
            animation: kk-modal-in .2s ease-out;
        }

        @keyframes kk-modal-in {
            from {
                opacity: 0;
                transform: scale(.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .kk-modal-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #fee8e8;
            color: #b92525;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 14px;
        }

        .kk-modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a1f2e;
            margin-bottom: 6px;
        }

        .kk-modal-text {
            font-size: 13px;
            color: #6b7088;
            margin-bottom: 18px;
        }

        .kk-modal-actions {
            display: flex;
            gap: 10px;
        }

        .kk-modal-btn {
            flex: 1;
            padding: 10px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background .2s;
        }

        .kk-modal-btn-cancel {
            background: #f5f7fa;
            color: #4a4f68;
        }

        .kk-modal-btn-cancel:hover {
            background: #eaecf3;
        }

        .kk-modal-btn-confirm {
            background: #d64545;
            color: #fff;
        }

        .kk-modal-btn-confirm:hover {
            background: #b92525;
        }

        /* ─────────────────────────────
           RESPONSIVE
        ───────────────────────────── */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-area {
                margin-left: 0;
            }

            .hamburger-btn {
                display: flex;
                align-items: center;
            }

            .topbar {
                padding: 12px 16px;
            }

            .page-content {
                padding: 16px;
            }

            .date-pill span {
                display: none;
            }
        }

        @media (max-width: 575px) {
            .topbar-greeting {
                font-size: 14px;
            }

            .topbar-sub {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- Progress bar tipis di paling atas, aktif saat link diklik / form disubmit --}}
    <div class="page-loader" id="pageLoader"></div>

    <div class="pos-wrapper">

        {{-- ─── SIDEBAR ─── --}}
        <aside class="sidebar" id="sidebar">

            <a class="sidebar-brand" href="{{ url('/dashboard') }}">
                <div class="brand-icon">
                    <i class="ti ti-cup" aria-hidden="true"></i>
                </div>
                <div class="brand-text">
                    <div class="brand-name">Kopi Koplak</div>
                    <div class="brand-sub">Point of Sale</div>
                </div>
            </a>

            <nav class="sidebar-nav" aria-label="Navigasi utama">

                <div class="nav-section-label">Utama</div>

                <a class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                    <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Dashboard
                </a>

                <a class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <i class="ti ti-receipt" aria-hidden="true"></i> Kasir / POS
                </a>

                <a class="nav-item {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="ti ti-box-seam" aria-hidden="true"></i> Inventori Stok
                </a>

                <a class="nav-item {{ request()->routeIs('products.logs') ? 'active' : '' }}" href="{{ route('products.logs') }}">
                    <i class="ti ti-history" aria-hidden="true"></i> Riwayat Stok
                </a>

                <a class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="ti ti-wallet" aria-hidden="true"></i> Pengeluaran
                </a>

                @if(auth()->user()->role == 'owner')
                <div class="nav-divider"></div>
                <div class="nav-section-label">Owner</div>

                <a class="nav-item {{ request()->routeIs('lstm.*') ? 'active' : '' }}" href="{{ route('lstm.dashboard-stok') }}">
                    <i class="ti ti-cpu" aria-hidden="true"></i> Prediksi Stok
                </a>

                <a class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                    <i class="ti ti-chart-bar" aria-hidden="true"></i> Laporan Keuangan
                </a>
                <a class="nav-item {{ request()->routeIs('stock-opname.*') ? 'active' : '' }}" href="{{ route('stock-opname.index') }}">
                    <i class="ti ti-clipboard-check" aria-hidden="true"></i> Stok Opname
                </a>
                <a class="nav-item {{ request()->routeIs('pengaturan.*') || request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('pengaturan.index') }}">
                    <i class="ti ti-settings" aria-hidden="true"></i> Pengaturan
                </a>
                <a class="nav-item {{ request()->routeIs('masterdata.*') ? 'active' : '' }}" href="{{ route('masterdata.index') }}">
                    <i class="ti ti-database" aria-hidden="true"></i> Master Data
                </a>
                @endif

                <div class="nav-divider"></div>

                <div class="nav-spacer"></div>

                <button type="button" class="nav-logout" id="logoutTriggerBtn">
                    <i class="ti ti-logout" aria-hidden="true"></i>
                    Logout ({{ auth()->user()->username }})
                </button>

            </nav>
        </aside>

        {{-- Mobile overlay --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- ─── MAIN ─── --}}
        <div class="main-area">

            {{-- Topbar --}}
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="hamburger-btn d-lg-none" id="sidebarToggle" aria-label="Buka menu">
                        <i class="ti ti-menu-2" aria-hidden="true"></i>
                    </button>
                    <div class="topbar-left">
                        <div class="topbar-greeting">Halo, {{ auth()->user()->name }}! 👋</div>
                        <div class="topbar-meta">
                            <span class="role-badge">{{ strtoupper(auth()->user()->role) }}</span>
                            <span class="topbar-sub">Selamat datang kembali</span>
                        </div>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="date-pill">
                        <i class="ti ti-calendar" aria-hidden="true"></i>
                        {{-- Locale Carbon, konsisten dengan halaman home.blade.php --}}
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
                    </div>

                    {{-- Notifikasi: dropdown berisi stok menipis/habis + status AI offline.
                         Data $notifications dikirim dari DashboardController saat load awal.
                         Setelah itu, JS polling (lihat script di bawah) akan REFRESH isi
                         dropdown ini tiap 10 detik tanpa reload halaman, dengan fetch ke
                         endpoint /api/dashboard/status.
                         Kalau variabel belum ada di halaman tertentu (misal belum
                         semua controller diupdate), fallback ke array kosong supaya
                         tidak error "Undefined variable". --}}
                    @php($notifications = $notifications ?? [])
                    <div class="notif-wrap">
                        <button type="button"
                            class="topbar-icon-btn"
                            id="notifTriggerBtn"
                            aria-label="Notifikasi ({{ count($notifications) }} baru)">
                            <i class="ti ti-bell" aria-hidden="true"></i>
                            {{-- Badge selalu ada di DOM (id="notifBadgeCount"), disembunyikan
                                 lewat class kk-hidden kalau jumlahnya 0. Ini sengaja dibuat
                                 begini (bukan @if/@endif) supaya JS polling tinggal
                                 toggle class + ganti angka, tanpa perlu insert/hapus elemen. --}}
                            <span class="notif-badge {{ count($notifications) > 0 ? '' : 'kk-hidden' }}" id="notifBadgeCount">{{ count($notifications) > 9 ? '9+' : count($notifications) }}</span>
                        </button>

                        <div class="notif-dropdown" id="notifDropdown">
                            <div class="notif-dropdown-header">Notifikasi</div>
                            {{-- id="notifDropdownList" -> innerHTML elemen ini yang akan
                                 di-replace oleh JS polling setiap kali ada data baru. --}}
                            <div class="notif-dropdown-list" id="notifDropdownList">
                                @forelse($notifications as $notif)
                                @if($notif['link'])
                                <a href="{{ $notif['link'] }}" class="notif-item">
                                    @else
                                    <div class="notif-item" style="cursor:default;">
                                        @endif
                                        <div class="notif-item-icon notif-{{ $notif['type'] }}">
                                            <i class="ti ti-{{ $notif['icon'] }}" aria-hidden="true"></i>
                                        </div>
                                        <div>
                                            <div class="notif-item-title">{{ $notif['title'] }}</div>
                                            <div class="notif-item-subtitle">{{ $notif['subtitle'] }}</div>
                                        </div>
                                        @if($notif['link'])
                                </a>
                                @else
                            </div>
                            @endif
                            @empty
                            <div class="notif-empty">
                                <i class="ti ti-bell-off" aria-hidden="true"></i>
                                Tidak ada notifikasi saat ini
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Profil: hanya owner yang punya halaman tujuan (route pengaturan.index).
                         Role lain belum punya halaman profil, jadi ikon disembunyikan
                         daripada jadi link mati yang membingungkan. --}}
                @if(auth()->user()->role == 'owner')
                <a href="{{ route('pengaturan.index') }}" class="topbar-icon-btn" aria-label="Profil & Pengaturan">
                    <i class="ti ti-user-circle" aria-hidden="true"></i>
                </a>
                @endif
        </div>
        </header>

        {{-- Content --}}
        <main class="page-content">

            {{-- Flash messages: bisa ditutup manual + auto-dismiss setelah 5 detik --}}
            @if(session('error'))
            <div class="alert alert-danger mb-4" data-auto-dismiss="true">
                <span><i class="ti ti-alert-triangle me-2" aria-hidden="true"></i>{{ session('error') }}</span>
                <button type="button" class="alert-close-btn" aria-label="Tutup pesan" onclick="this.closest('.alert').remove()">&times;</button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success mb-4" data-auto-dismiss="true">
                <span><i class="ti ti-circle-check me-2" aria-hidden="true"></i>{{ session('success') }}</span>
                <button type="button" class="alert-close-btn" aria-label="Tutup pesan" onclick="this.closest('.alert').remove()">&times;</button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning mb-4" data-auto-dismiss="true">
                <span><i class="ti ti-alert-circle me-2" aria-hidden="true"></i>{{ session('warning') }}</span>
                <button type="button" class="alert-close-btn" aria-label="Tutup pesan" onclick="this.closest('.alert').remove()">&times;</button>
            </div>
            @endif

            @yield('content')

        </main>
    </div>

    </div>

    {{-- ─── MODAL KONFIRMASI LOGOUT ─── --}}
    <div class="kk-modal-backdrop" id="logoutModal">
        <div class="kk-modal-box">
            <div class="kk-modal-icon">
                <i class="ti ti-logout" aria-hidden="true"></i>
            </div>
            <div class="kk-modal-title">Yakin ingin logout?</div>
            <div class="kk-modal-text">Kamu akan keluar dari sesi ini dan perlu login kembali untuk lanjut bekerja.</div>
            <div class="kk-modal-actions">
                <button type="button" class="kk-modal-btn kk-modal-btn-cancel" id="logoutCancelBtn">Batal</button>
                <button type="button" class="kk-modal-btn kk-modal-btn-confirm" id="logoutConfirmBtn">Ya, Logout</button>
            </div>
        </div>
    </div>

    {{-- Form logout asli, disubmit lewat JS setelah konfirmasi --}}
    <form action="{{ route('logout') }}" method="POST" id="logoutForm" style="display:none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Mobile sidebar toggle ──
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Auto-close sidebar mobile saat salah satu nav-item diklik,
        // supaya transisi ke halaman baru tidak terasa "nyangkut" di sidebar terbuka.
        document.querySelectorAll('.sidebar-nav .nav-item').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 991) {
                    closeSidebar();
                }
            });
        });

        // ── Page loading bar ──
        // Tampil saat user klik link internal atau submit form,
        // memberi feedback visual bahwa halaman sedang berpindah
        // (berguna terutama saat koneksi lambat).
        const pageLoader = document.getElementById('pageLoader');

        function startPageLoader() {
            pageLoader.classList.add('active');
            pageLoader.style.width = '0%';
            requestAnimationFrame(function() {
                pageLoader.style.width = '75%';
            });
        }

        document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])').forEach(function(link) {
            link.addEventListener('click', function(e) {
                // Hanya untuk navigasi biasa (bukan klik kanan / ctrl+klik / dsb)
                if (e.button === 0 && !e.ctrlKey && !e.metaKey) {
                    startPageLoader();
                }
            });
        });

        document.querySelectorAll('form').forEach(function(form) {
            // Form logout dikecualikan karena sudah dihandle modal konfirmasi
            if (form.id !== 'logoutForm') {
                form.addEventListener('submit', startPageLoader);
            }
        });

        window.addEventListener('pageshow', function() {
            // Kalau user balik via tombol back, reset loader supaya tidak nyangkut
            pageLoader.classList.remove('active');
            pageLoader.style.width = '0%';
        });

        // ── Auto-dismiss flash messages setelah 5 detik ──
        document.querySelectorAll('[data-auto-dismiss="true"]').forEach(function(alertEl) {
            setTimeout(function() {
                alertEl.style.transition = 'opacity .3s ease-out';
                alertEl.style.opacity = '0';
                setTimeout(function() {
                    alertEl.remove();
                }, 300);
            }, 5000);
        });

        // ── Modal konfirmasi logout ──
        const logoutTriggerBtn = document.getElementById('logoutTriggerBtn');
        const logoutModal = document.getElementById('logoutModal');
        const logoutCancelBtn = document.getElementById('logoutCancelBtn');
        const logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
        const logoutForm = document.getElementById('logoutForm');

        logoutTriggerBtn.addEventListener('click', function() {
            logoutModal.classList.add('show');
        });

        logoutCancelBtn.addEventListener('click', function() {
            logoutModal.classList.remove('show');
        });

        // Klik di luar kotak modal -> tutup juga
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('show');
            }
        });

        logoutConfirmBtn.addEventListener('click', function() {
            logoutForm.submit();
        });

        // ── Dropdown notifikasi ──
        const notifTriggerBtn = document.getElementById('notifTriggerBtn');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifTriggerBtn && notifDropdown) {
            notifTriggerBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('show');
            });

            // Klik di luar dropdown -> tutup
            document.addEventListener('click', function(e) {
                if (!notifDropdown.contains(e.target) && e.target !== notifTriggerBtn) {
                    notifDropdown.classList.remove('show');
                }
            });

            // Tutup dropdown dengan tombol Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    notifDropdown.classList.remove('show');
                }
            });
        }

        // ── POLLING STATUS AI & NOTIFIKASI (tiap 5 detik) ──
        // Fetch ke /api/dashboard/status secara berkala supaya badge notifikasi
        // dan card "AI Status" di halaman home ikut update TANPA reload halaman.
        // Interval 5 detik: cukup cepat untuk demo di depan dosen penguji.

        // Escape sederhana untuk teks yang akan disisipkan via innerHTML,
        // mencegah karakter aneh pada nama produk merusak markup / jadi celah XSS.
        function kkEscapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function kkRenderNotifItem(notif) {
            const tag = notif.link ? 'a' : 'div';
            const hrefAttr = notif.link ? ` href="${kkEscapeHtml(notif.link)}"` : ' style="cursor:default;"';
            return `
                <${tag} class="notif-item"${hrefAttr}>
                    <div class="notif-item-icon notif-${kkEscapeHtml(notif.type)}">
                        <i class="ti ti-${kkEscapeHtml(notif.icon)}" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="notif-item-title">${kkEscapeHtml(notif.title)}</div>
                        <div class="notif-item-subtitle">${kkEscapeHtml(notif.subtitle)}</div>
                    </div>
                </${tag}>
            `;
        }

        function kkUpdateNotifUI(notifications) {
            const badge = document.getElementById('notifBadgeCount');
            const list = document.getElementById('notifDropdownList');
            if (!badge || !list) return;

            const total = notifications.length;

            // Update badge angka
            if (total > 0) {
                badge.textContent = total > 9 ? '9+' : String(total);
                badge.classList.remove('kk-hidden');
            } else {
                badge.classList.add('kk-hidden');
            }

            // Update isi dropdown
            if (total === 0) {
                list.innerHTML = `
                    <div class="notif-empty">
                        <i class="ti ti-bell-off" aria-hidden="true"></i>
                        Tidak ada notifikasi saat ini
                    </div>
                `;
            } else {
                list.innerHTML = notifications.map(kkRenderNotifItem).join('');
            }
        }

        function kkUpdateAiStatusCard(aiStatus) {
            // Elemen ini ada di home.blade.php (widget "AI Status").
            // Kalau halaman saat ini bukan home (elemen tidak ada di DOM),
            // fungsi ini diam saja -- tidak error, cukup di-skip.
            const dot = document.getElementById('aiStatusDot');
            const label = document.getElementById('aiStatusLabel');
            const sub = document.getElementById('aiStatusSub');
            const accent = document.getElementById('aiStatusAccent');
            if (!dot || !label || !sub) return; // bukan di halaman home, skip

            if (aiStatus.online) {
                dot.classList.remove('kk-ai-dot-offline');
                dot.classList.add('kk-ai-dot-online');
                label.textContent = 'Ready';
                label.style.color = '#1a1f1a';
                sub.textContent = `${aiStatus.total_model} model aktif`;
                if (accent) accent.style.background = '#3d7a5e';
            } else {
                dot.classList.remove('kk-ai-dot-online');
                dot.classList.add('kk-ai-dot-offline');
                label.textContent = 'Offline';
                label.style.color = '#c0392b';
                sub.textContent = aiStatus.message;
                if (accent) accent.style.background = '#c0392b';
            }
        }

        function kkPollDashboardStatus() {
            fetch('{{ route("dashboard.status") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) {
                    if (!res.ok) throw new Error('Gagal mengambil status');
                    return res.json();
                })
                .then(function(data) {
                    kkUpdateNotifUI(data.notifications || []);
                    kkUpdateAiStatusCard(data.aiStatus || {
                        online: false,
                        message: 'Tidak ada respons'
                    });
                })
                .catch(function(err) {
                    // Diamkan secara UI (tidak mengganggu user dengan alert),
                    // tapi tetap dicatat di console untuk debugging.
                    console.warn('Polling status dashboard gagal:', err);
                });
        }

        // Jalankan sekali saat halaman dimuat (sinkron cepat), lalu ulangi tiap 5 detik.
        kkPollDashboardStatus();
        setInterval(kkPollDashboardStatus, 5000);
    </script>

    @stack('scripts')
</body>

</html>