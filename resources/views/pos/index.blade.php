@extends('dashboard')

@section('content')
@php use Illuminate\Support\Facades\Storage; @endphp

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .dashboard-wrapper * {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    body {
        background: #f5f4f0;
    }

    /* =====================
       RECENT ORDERS STRIP
    ===================== */
    .recent-section {
        margin-bottom: 16px;
    }

    .recent-label {
        font-size: 11px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: .07em;
        margin-bottom: 8px;
    }

    .recent-strip {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        scrollbar-width: none;
        padding-bottom: 2px;
    }

    .recent-strip::-webkit-scrollbar {
        display: none;
    }

    .recent-chip {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #e8e7e2;
        border-radius: 100px;
        padding: 6px 14px 6px 6px;
        cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
        text-decoration: none;
        color: inherit;
        min-height: 44px;
        box-sizing: border-box;
    }

    .recent-chip:hover {
        border-color: #1D9E75;
        box-shadow: 0 2px 8px rgba(29, 158, 117, .12);
        text-decoration: none;
        color: inherit;
    }

    .recent-chip:active {
        transform: scale(.97);
    }

    .recent-chip-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #E1F5EE;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #0F6E56;
        flex-shrink: 0;
    }

    .recent-chip-name {
        font-size: 12px;
        font-weight: 600;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
    }

    .recent-chip-price {
        font-size: 11px;
        color: #888;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
    }

    /* =====================
       MAIN LAYOUT
    ===================== */
    .pos-grid {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 16px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .pos-grid {
            grid-template-columns: 1fr;
        }
    }

    .pos-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e8e7e2;
        overflow: hidden;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
    }

    /* =====================
       MENU PANEL
    ===================== */
    .menu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #f0efeb;
        flex-wrap: wrap;
    }

    .menu-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .search-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f5f4f0;
        border-radius: 100px;
        padding: 10px 16px;
        width: 220px;
        flex: 1;
        min-width: 160px;
        max-width: 260px;
    }

    .search-wrap i {
        color: #aaa;
        font-size: 15px;
    }

    .search-wrap input {
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        color: #333;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
        width: 100%;
    }

    .search-wrap input::placeholder {
        color: #bbb;
    }

    .search-clear {
        border: none;
        background: transparent;
        color: #bbb;
        cursor: pointer;
        font-size: 14px;
        padding: 2px;
        display: none;
        line-height: 1;
    }

    .search-clear.show {
        display: block;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 10px;
        padding: 16px;
        max-height: 560px;
        overflow-y: auto;
    }

    .product-grid::-webkit-scrollbar {
        width: 4px;
    }

    .product-grid::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 4px;
    }

    .product-item {
        border: 1px solid #f0efeb;
        border-radius: 14px;
        padding: 14px 10px 12px;
        text-align: center;
        cursor: pointer;
        transition: transform .15s, border-color .15s, box-shadow .15s;
        background: #fff;
        min-height: 44px;
        -webkit-tap-highlight-color: transparent;
        user-select: none;
    }

    .product-item:hover {
        transform: translateY(-3px);
        border-color: #1D9E75;
        box-shadow: 0 6px 16px rgba(29, 158, 117, .12);
    }

    .product-item:active {
        transform: scale(.96);
    }

    .product-item.pop {
        animation: itemPop .25s ease;
    }

    @keyframes itemPop {
        0% {
            transform: scale(1);
        }

        45% {
            transform: scale(.93);
            background: #E1F5EE;
        }

        100% {
            transform: scale(1);
        }
    }

    .product-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #E1F5EE;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 22px;
        color: #0F6E56;
    }

    .product-name {
        font-size: 12px;
        font-weight: 600;
        line-height: 1.35;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .product-price {
        font-size: 12px;
        font-weight: 600;
        color: #1D9E75;
    }

    .product-unit {
        font-size: 10px;
        color: #aaa;
        margin-top: 2px;
    }

    .empty-search {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        color: #ccc;
        gap: 10px;
        font-size: 13px;
        grid-column: 1 / -1;
    }

    .empty-search.show {
        display: flex;
    }

    .empty-search i {
        font-size: 32px;
    }

    /* =====================
       ORDER PANEL
    ===================== */
    .order-panel {
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 16px;
    }

    @media (max-width: 900px) {
        .order-panel {
            position: static;
        }
    }

    .order-header {
        padding: 16px 18px;
        border-bottom: 1px solid #f0efeb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .order-title {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .cart-badge {
        background: #1D9E75;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 100px;
    }

    .cart-list {
        padding: 12px;
        min-height: 170px;
        max-height: 360px;
        overflow-y: auto;
        display: none;
        /* diatur via JS: flex saat ada isi */
        flex-direction: column;
        gap: 8px;
    }

    .cart-list::-webkit-scrollbar {
        width: 3px;
    }

    .cart-list::-webkit-scrollbar-thumb {
        background: #eee;
        border-radius: 3px;
    }

    .empty-cart {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 170px;
        color: #ccc;
        gap: 10px;
        font-size: 13px;
    }

    .empty-cart i {
        font-size: 36px;
    }

    .cart-item {
        background: #f9f9f7;
        border-radius: 12px;
        padding: 10px 12px;
    }

    .cart-item-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
        gap: 8px;
    }

    .ci-name {
        font-size: 13px;
        font-weight: 600;
        color: #1a1a1a;
    }

    .ci-sub {
        font-size: 11px;
        color: #999;
        margin-top: 1px;
    }

    .ci-remove {
        border: none;
        background: transparent;
        color: #ccc;
        cursor: pointer;
        font-size: 15px;
        padding: 2px 4px;
        line-height: 1;
        flex-shrink: 0;
        transition: color .15s;
    }

    .ci-remove:hover {
        color: #e24b4a;
    }

    .qty-ctrl {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #fff;
        border: 1px solid #e8e7e2;
        border-radius: 100px;
        padding: 3px 10px;
        flex-shrink: 0;
    }

    .qty-btn {
        border: none;
        background: transparent;
        cursor: pointer;
        font-size: 17px;
        line-height: 1;
        color: #555;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        transition: color .1s;
    }

    .qty-btn:hover {
        color: #1D9E75;
    }

    .qty-num {
        font-size: 13px;
        font-weight: 600;
        min-width: 18px;
        text-align: center;
    }

    .ta-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #ece9e2;
    }

    /* Toggle take away — pakai hidden checkbox + label agar reliable */
    .ta-switch-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .ta-checkbox {
        display: none;
    }

    .ta-switch-track {
        width: 34px;
        height: 20px;
        border-radius: 100px;
        background: #ddd;
        position: relative;
        transition: background .2s;
        flex-shrink: 0;
    }

    .ta-switch-track.on {
        background: #1D9E75;
    }

    .ta-switch-track::after {
        content: '';
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    }

    .ta-switch-track.on::after {
        transform: translateX(14px);
    }

    .ta-text {
        font-size: 11px;
        font-weight: 600;
        color: #999;
    }

    .ta-text.on {
        color: #0F6E56;
    }

    /* =====================
       ADDON CHIPS
    ===================== */
    .addons-row {
        margin-top: 8px;
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .addons-label {
        font-size: 10px;
        font-weight: 700;
        color: #aaa;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 2px;
    }

    .addon-chip {
        cursor: pointer;
    }

    .addon-chip input {
        display: none;
    }

    .addon-chip-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #e8e7e2;
        background: #fff;
        color: #888;
        transition: all .15s;
        min-height: 28px;
        box-sizing: border-box;
    }

    .addon-chip input:checked + .addon-chip-pill {
        border-color: #1D9E75;
        background: #E1F5EE;
        color: #0F6E56;
    }

    .addon-chip-extra {
        color: #1D9E75;
        font-weight: 700;
    }

    /* =====================
       ORDER FOOTER
    ===================== */
    .order-footer {
        border-top: 1px solid #f0efeb;
        padding: 14px 18px 18px;
    }

    .subtotal-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .sub-label {
        font-size: 13px;
        color: #999;
    }

    .sub-amount {
        font-size: 20px;
        font-weight: 700;
        color: #1D9E75;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
    }

    .pay-method-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }

    .pay-method-btn {
        border: 1.5px solid #e8e7e2;
        background: #f5f4f0;
        border-radius: 12px;
        padding: 10px 6px;
        font-size: 12px;
        font-weight: 600;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
        color: #888;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        transition: all .15s;
        min-height: 44px;
    }

    .pay-method-btn span.ico {
        font-size: 16px;
    }

    .pay-method-btn.active {
        border-color: #1D9E75;
        background: #E1F5EE;
        color: #0F6E56;
    }

    /* =====================
       INPUT BAYAR & KEMBALIAN
    ===================== */
    .pay-input-wrap {
        position: relative;
        margin-bottom: 8px;
    }

    .pay-input-prefix {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 13px;
        font-weight: 600;
        color: #999;
        pointer-events: none;
    }

    .pay-input {
        width: 100%;
        background: #f5f4f0;
        border: 1.5px solid #e8e7e2;
        border-radius: 12px;
        padding: 13px 14px 13px 38px;
        font-size: 15px;
        font-weight: 600;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
        color: #1a1a1a;
        outline: none;
        transition: border-color .15s;
        box-sizing: border-box;
    }

    .pay-input:focus {
        border-color: #1D9E75;
        background: #fff;
    }

    .pay-input.error {
        border-color: #e24b4a;
        background: #fff8f8;
    }

    .quick-cash-row {
        display: flex;
        gap: 6px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .quick-cash-btn {
        flex: 1;
        min-width: 64px;
        border: 1px solid #e8e7e2;
        background: #fff;
        border-radius: 10px;
        padding: 8px 4px;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        cursor: pointer;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
        transition: all .15s;
        min-height: 36px;
    }

    .quick-cash-btn:hover {
        border-color: #1D9E75;
        color: #0F6E56;
    }

    .quick-cash-btn.exact {
        border-color: #1D9E75;
        color: #0F6E56;
        background: #E1F5EE;
    }

    .change-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #E1F5EE;
        border-radius: 10px;
        padding: 10px 14px;
        margin-bottom: 12px;
        transition: background .2s;
    }

    .change-row.minus {
        background: #fdecea;
    }

    .change-label {
        font-size: 12px;
        font-weight: 600;
        color: #0F6E56;
    }

    .change-row.minus .change-label {
        color: #c0392b;
    }

    .change-amount {
        font-size: 15px;
        font-weight: 700;
        color: #0F6E56;
    }

    .change-row.minus .change-amount {
        color: #c0392b;
    }

    /* =====================
       CLEAR CART BUTTON
    ===================== */
    .clear-btn {
        background: transparent;
        border: 1px solid #f0efeb;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 600;
        color: #bbb;
        cursor: pointer;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
        transition: border-color .15s, color .15s, background .15s;
        display: flex;
        align-items: center;
        gap: 4px;
        min-height: 32px;
    }

    .clear-btn:hover {
        border-color: #e24b4a;
        color: #e24b4a;
        background: #fff5f5;
    }

    .checkout-btn {
        width: 100%;
        background: #1D9E75;
        color: #fff;
        border: none;
        border-radius: 13px;
        padding: 16px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'DM Sans', 'Plus Jakarta Sans';
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        letter-spacing: .03em;
        transition: background .15s, transform .1s;
        min-height: 48px;
    }

    .checkout-btn:hover:not(:disabled) {
        background: #0F6E56;
    }

    .checkout-btn:active:not(:disabled) {
        transform: scale(.98);
    }

    .checkout-btn:disabled {
        background: #e0e0e0;
        color: #bbb;
        cursor: not-allowed;
        transform: none;
    }

    .checkout-hint {
        font-size: 11px;
        color: #c0392b;
        text-align: center;
        margin-top: 8px;
        display: none;
    }

    .checkout-hint.show {
        display: block;
    }

    /* =====================
       CONFIRM / ALERT MODAL
    ===================== */
    .pos-overlay {
        display: none;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        background: rgba(0, 0, 0, 0.6) !important;
        z-index: 99999 !important;
        align-items: center;
        justify-content: center;
        padding: 16px;
        box-sizing: border-box;
    }

    .pos-overlay.show {
        display: flex !important;
    }

    .modal-box {
        background: #ffffff !important;
        border-radius: 20px;
        padding: 28px 24px 24px;
        width: 380px;
        max-width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalIn .2s cubic-bezier(.34, 1.4, .64, 1);
    }

    @keyframes modalIn {
        from {
            transform: scale(.92);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .modal-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-title i {
        color: #1D9E75;
        font-size: 20px;
    }

    .modal-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f5f4f0;
        font-size: 13px;
        gap: 10px;
    }

    .modal-row:last-of-type {
        border-bottom: none;
    }

    .modal-row-label {
        color: #999;
    }

    .modal-row-val {
        font-weight: 600;
        color: #1a1a1a;
        text-align: right;
    }

    .modal-row-val.green {
        color: #1D9E75;
        font-size: 15px;
    }

    .modal-row-val.red {
        color: #e24b4a;
    }

    .modal-divider {
        border: none;
        border-top: 1.5px solid #f0efeb;
        margin: 14px 0;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .modal-cancel {
        flex: 1;
        padding: 14px;
        background: #f5f4f0;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'DM Sans', Plus Jakarta Sans;
        color: #888;
        cursor: pointer;
        transition: background .15s;
        min-height: 48px;
    }

    .modal-cancel:hover {
        background: #eae9e4;
    }

    .modal-confirm {
        flex: 2;
        padding: 14px;
        background: #1D9E75;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        font-family: 'DM Sans', Plus Jakarta Sans;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background .15s, transform .1s;
        min-height: 48px;
    }

    .modal-confirm:hover {
        background: #0F6E56;
    }

    .modal-confirm:active {
        transform: scale(.98);
    }

    .modal-confirm.loading {
        pointer-events: none;
        opacity: .7;
    }

    /* Simple alert/confirm replacement modal (ganti window.confirm/alert) */
    .alert-modal .modal-box {
        width: 340px;
        text-align: center;
    }

    .alert-modal .modal-title {
        justify-content: center;
    }

    .alert-modal-msg {
        font-size: 13px;
        color: #666;
        margin-bottom: 4px;
        line-height: 1.5;
    }

    /* =====================
       TOAST
    ===================== */
    #pos-toast {
        position: fixed;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: #26215C;
        color: #fff;
        padding: 11px 22px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 500;
        font-family: 'DM Sans', Plus Jakarta Sans;
        pointer-events: none;
        z-index: 9999;
        transition: transform .28s cubic-bezier(.34, 1.36, .64, 1);
        white-space: nowrap;
        max-width: 90vw;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    #pos-toast.show {
        transform: translateX(-50%) translateY(0);
    }
</style>

<div class="recent-section">
    <div class="recent-label">Pesanan terbaru</div>
    <div class="recent-strip" id="recent-strip">
        <span style="font-size:12px;color:#bbb;align-self:center;padding:4px 8px;">Belum ada transaksi hari ini</span>
    </div>
</div>

<div class="pos-grid">

    {{-- ====== MENU PANEL ====== --}}
    <div class="pos-card">
        <div class="menu-header">
            <span class="menu-title">Menu Kopi Koplak</span>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="search-menu" placeholder="Cari menu..." autocomplete="off">
                <button type="button" class="search-clear" id="search-clear" onclick="clearSearch()">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </div>
        <div class="product-grid" id="product-list">
            @foreach($products as $product)
            <div class="product-item"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-product-price="{{ $product->price }}"
                data-product-unit="{{ $product->unit }}">
                <div class="product-icon" style="@if($product->image) padding:0;overflow:hidden; @endif">
                    @if($product->image)
                    <img src="{{ Storage::url($product->image) }}"
                        style="width:100%;height:100%;object-fit:cover;border-radius:12px">
                    @else
                    <i class="bi bi-cup-hot"></i>
                    @endif
                </div>
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <div class="product-unit">Gudang: {{ $product->unit }}</div>
            </div>
            @endforeach
            <div class="empty-search" id="empty-search">
                <i class="bi bi-search"></i>
                <span>Menu tidak ditemukan</span>
            </div>
        </div>
    </div>

    {{-- ====== ORDER PANEL ====== --}}
    <div class="pos-card order-panel">
        <div class="order-header">
            <span class="order-title">Detail Pesanan</span>
            <div style="display:flex;align-items:center;gap:8px;">
                <button class="clear-btn" id="clear-btn" onclick="askClearCart()" style="display:none;">
                    <i class="bi bi-trash3"></i> Hapus Semua
                </button>
                <span class="cart-badge" id="cart-count">0 item</span>
            </div>
        </div>

        {{-- Empty state: OUTSIDE cart-list agar tidak ikut di-overwrite oleh innerHTML --}}
        <div class="empty-cart" id="empty-cart">
            <i class="bi bi-cart3"></i>
            <span>Keranjang masih kosong</span>
        </div>

        <div class="cart-list" id="cart-items" style="display:none;"></div>

        <div class="order-footer">
            <div class="subtotal-row">
                <span class="sub-label">Subtotal</span>
                <span class="sub-amount" id="total-price">Rp 0</span>
            </div>

            <div class="pay-method-grid" id="pay-method-grid">
                <button type="button" class="pay-method-btn active" data-method="Cash" onclick="setPayMethod('Cash')">
                    <span class="ico">💵</span> Tunai
                </button>
                <button type="button" class="pay-method-btn" data-method="Debit" onclick="setPayMethod('Debit')">
                    <span class="ico">💳</span> Debit
                </button>
                <button type="button" class="pay-method-btn" data-method="QRIS" onclick="setPayMethod('QRIS')">
                    <span class="ico">📱</span> QRIS
                </button>
            </div>

            {{-- Input nominal bayar (hanya tampil untuk Cash) --}}
            <div id="cash-section">
                <div class="pay-input-wrap">
                    <span class="pay-input-prefix">Rp</span>
                    <input type="number" id="pay-amount" class="pay-input"
                        placeholder="Nominal bayar..." inputmode="numeric"
                        oninput="calcChange()" min="0" step="1000">
                </div>
                <div class="quick-cash-row" id="quick-cash-row"></div>
                <div class="change-row" id="change-row">
                    <span class="change-label" id="change-label">Kembalian</span>
                    <span class="change-amount" id="change-amount">—</span>
                </div>
            </div>

            <button class="checkout-btn" id="checkout-btn" onclick="openModal()" disabled>
                <i class="bi bi-check2-circle"></i>
                Proses Transaksi
            </button>
            <div class="checkout-hint" id="checkout-hint">Nominal bayar belum cukup</div>
        </div>
    </div>

</div>

<div id="pos-toast"></div>

{{-- ====== CONFIRM MODAL ====== --}}
<div class="pos-overlay" id="confirm-modal" onclick="closeModalOnBackdrop(event)">
    <div class="modal-box">
        <div class="modal-title">
            <i class="bi bi-receipt"></i>
            Konfirmasi Pesanan
        </div>

        <div id="modal-items"></div>

        <hr class="modal-divider">

        <div class="modal-row">
            <span class="modal-row-label">Subtotal</span>
            <span class="modal-row-val" id="modal-subtotal">—</span>
        </div>
        <div class="modal-row">
            <span class="modal-row-label">Metode Bayar</span>
            <span class="modal-row-val" id="modal-method">—</span>
        </div>
        <div class="modal-row" id="modal-pay-row">
            <span class="modal-row-label">Nominal Bayar</span>
            <span class="modal-row-val" id="modal-pay">—</span>
        </div>
        <div class="modal-row" id="modal-change-row">
            <span class="modal-row-label">Kembalian</span>
            <span class="modal-row-val green" id="modal-change">—</span>
        </div>

        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Batal</button>
            <button class="modal-confirm" id="modal-confirm-btn" onclick="processCheckout()">
                <i class="bi bi-check2-circle"></i>
                Proses Sekarang
            </button>
        </div>
    </div>
</div>

{{-- ====== GENERIC CONFIRM/ALERT MODAL (ganti window.confirm & alert) ====== --}}
<div class="pos-overlay alert-modal" id="alert-modal">
    <div class="modal-box">
        <div class="modal-title" id="alert-modal-title">
            <i class="bi bi-question-circle"></i>
            <span id="alert-modal-title-text">Konfirmasi</span>
        </div>
        <p class="alert-modal-msg" id="alert-modal-msg"></p>
        <div class="modal-actions" id="alert-modal-actions">
            <button class="modal-cancel" id="alert-modal-cancel">Batal</button>
            <button class="modal-confirm" id="alert-modal-ok">Ya</button>
        </div>
    </div>
</div>

<script>
    let cart = [];
    let currentPayMethod = 'Cash';

    const ADDON_DEFS = [
        { name: 'Cup Hot', price: 0 },
        { name: 'Cup Ice', price: 0 },
        { name: 'Gula Pasir', price: 0 },
        { name: 'Gula Aren Bubuk', price: 0 },
        { name: 'Oat Milk', price: 10000 },
    ];

    // ---- Recent menus (localStorage) ----
    const RECENT_KEY = 'kopikoplak_recent_menus';
    let recentMenus = [];
    try {
        const saved = localStorage.getItem(RECENT_KEY);
        if (saved) recentMenus = JSON.parse(saved);
    } catch (e) {
        recentMenus = [];
    }

    function saveRecent() {
        try {
            localStorage.setItem(RECENT_KEY, JSON.stringify(recentMenus));
        } catch (e) {}
    }

    // ---- Formatting ----
    function fmt(n) {
        const sign = n < 0 ? '-' : '';
        return sign + 'Rp ' + Math.abs(parseInt(n) || 0).toLocaleString('id-ID');
    }

    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ---- Generic alert/confirm modal (replaces window.alert/confirm) ----
    let alertResolver = null;

    function showAlert(message, opts = {}) {
        const {
            title = 'Konfirmasi',
            okText = 'Ya',
            cancelText = 'Batal',
            danger = false,
            okOnly = false,
        } = opts;

        return new Promise(resolve => {
            alertResolver = resolve;
            document.getElementById('alert-modal-title-text').innerText = title;
            document.getElementById('alert-modal-msg').innerText = message;
            const okBtn = document.getElementById('alert-modal-ok');
            const cancelBtn = document.getElementById('alert-modal-cancel');
            okBtn.innerText = okText;
            okBtn.style.background = danger ? '#e24b4a' : '#1D9E75';
            cancelBtn.style.display = okOnly ? 'none' : 'block';
            document.getElementById('alert-modal').classList.add('show');
        });
    }

    document.getElementById('alert-modal-ok').addEventListener('click', () => {
        document.getElementById('alert-modal').classList.remove('show');
        if (alertResolver) alertResolver(true);
        alertResolver = null;
    });

    document.getElementById('alert-modal-cancel').addEventListener('click', () => {
        document.getElementById('alert-modal').classList.remove('show');
        if (alertResolver) alertResolver(false);
        alertResolver = null;
    });

    // ---- Click product card ----
    document.querySelectorAll('.product-item').forEach(el => {
        el.addEventListener('click', function() {
            const id = parseInt(this.dataset.productId);
            const name = this.dataset.productName;
            const price = parseInt(this.dataset.productPrice);
            const unit = this.dataset.productUnit;
            this.classList.add('pop');
            setTimeout(() => this.classList.remove('pop'), 260);
            addToCart(id, name, price, unit);
        });
    });

    function addToCart(id, name, price, unit) {
        const ex = cart.find(x => x.id === id);
        if (ex) {
            ex.qty++;
        } else {
            cart.push({
                id,
                name,
                price,
                unit,
                qty: 1,
                addons: [],
                addonPrices: {}
            });
        }
        renderCart();
        showToast((name.length > 28 ? name.slice(0, 27) + '…' : name) + ' ditambahkan');
    }

    // ---- Clear cart ----
    async function askClearCart() {
        if (cart.length === 0) return;
        const ok = await showAlert('Hapus semua item dari keranjang? Tindakan ini tidak bisa dibatalkan.', {
            title: 'Hapus Semua Item',
            okText: 'Hapus',
            danger: true
        });
        if (ok) {
            cart = [];
            renderCart();
            showToast('Keranjang dikosongkan');
        }
    }

    // ---- Recent menus ----
    function updateRecent(id, name, price) {
        recentMenus = recentMenus.filter(x => x.id !== id);
        recentMenus.unshift({ id, name, price });
        if (recentMenus.length > 5) recentMenus = recentMenus.slice(0, 5);
        saveRecent();
        renderRecent();
    }

    function renderRecent() {
        const strip = document.getElementById('recent-strip');
        if (recentMenus.length === 0) {
            strip.innerHTML = '<span style="font-size:12px;color:#bbb;align-self:center;padding:4px 8px;">Belum ada transaksi hari ini</span>';
            return;
        }
        strip.innerHTML = recentMenus.map(m => `
            <div class="recent-chip" onclick="recentAdd(${m.id})">
                <div class="recent-chip-icon"><i class="bi bi-cup-hot"></i></div>
                <div>
                    <div class="recent-chip-name">${escapeHtml(m.name.length > 20 ? m.name.slice(0,19)+'…' : m.name)}</div>
                    <div class="recent-chip-price">${fmt(m.price)}</div>
                </div>
            </div>
        `).join('');
    }

    function recentAdd(id) {
        const el = document.querySelector(`.product-item[data-product-id="${id}"]`);
        if (el) {
            el.classList.add('pop');
            setTimeout(() => el.classList.remove('pop'), 260);
            addToCart(id, el.dataset.productName, parseInt(el.dataset.productPrice), el.dataset.productUnit);
        }
    }

    // ---- Render cart ----
    function renderCart() {
        const container = document.getElementById('cart-items');
        const emptyEl = document.getElementById('empty-cart');
        const badge = document.getElementById('cart-count');
        const clearBtn = document.getElementById('clear-btn');
        let total = 0, totalQty = 0;

        if (cart.length === 0) {
            emptyEl.style.display = 'flex';
            container.style.display = 'none';
            container.innerHTML = '';
            document.getElementById('total-price').innerText = 'Rp 0';
            badge.innerText = '0 item';
            clearBtn.style.display = 'none';
            resetChange();
            updateCheckoutState();
            return;
        }

        emptyEl.style.display = 'none';
        container.style.display = 'flex';
        clearBtn.style.display = 'flex';

        container.innerHTML = cart.map((item, i) => {
            const addonTotal = Object.values(item.addonPrices || {}).reduce((a, b) => a + b, 0);
            const lineTotal = (item.price + addonTotal) * item.qty;
            total += lineTotal;
            totalQty += item.qty;

            const addonsHtml = ADDON_DEFS.map(addon => {
                const checked = (item.addons || []).includes(addon.name);
                return `
                    <label class="addon-chip">
                        <input type="checkbox" ${checked ? 'checked' : ''}
                            onchange="toggleAddon(${i}, '${addon.name}', ${addon.price}, this.checked)">
                        <span class="addon-chip-pill">
                            ${addon.name}
                            ${addon.price > 0 ? `<span class="addon-chip-extra">+${Math.round(addon.price/1000)}K</span>` : ''}
                        </span>
                    </label>
                `;
            }).join('');

            return `
            <div class="cart-item">
                <div class="cart-item-row">
                    <div style="flex:1;min-width:0;">
                        <div class="ci-name">${escapeHtml(item.name)}</div>
                        <div class="ci-sub">${fmt(item.price)} × ${item.qty}${addonTotal > 0 ? ' + addon' : ''} = ${fmt(lineTotal)}</div>
                    </div>
                    <button class="ci-remove" type="button" title="Hapus item" onclick="removeItem(${i})">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                    <div class="qty-ctrl">
                        <button class="qty-btn" type="button" onclick="updateQty(${i}, -1)" aria-label="Kurangi">−</button>
                        <span class="qty-num">${item.qty}</span>
                        <button class="qty-btn" type="button" onclick="updateQty(${i}, 1)" aria-label="Tambah">+</button>
                    </div>
                </div>

                <div class="addons-row">
                    <span class="addons-label">Add-ons</span>
                    ${addonsHtml}
                </div>
            </div>`;
        }).join('');

        document.getElementById('total-price').innerText = fmt(total);
        badge.innerText = totalQty + ' item';
        renderQuickCash(total);
        calcChange();
        updateCheckoutState();
    }

    function updateQty(index, change) {
        if (cart[index] === undefined) return;
        cart[index].qty += change;
        if (cart[index].qty <= 0) cart.splice(index, 1);
        renderCart();
    }

    function removeItem(index) {
        if (cart[index] === undefined) return;
        cart.splice(index, 1);
        renderCart();
    }



    function toggleAddon(index, addonName, addonPrice, checked) {
        if (!cart[index]) return;
        if (!cart[index].addons) cart[index].addons = [];
        if (!cart[index].addonPrices) cart[index].addonPrices = {};

        if (checked) {
            if (!cart[index].addons.includes(addonName)) {
                cart[index].addons.push(addonName);
                cart[index].addonPrices[addonName] = addonPrice;
            }
        } else {
            cart[index].addons = cart[index].addons.filter(a => a !== addonName);
            delete cart[index].addonPrices[addonName];
        }
        renderCart();
    }

    // ---- Metode bayar ----
    function setPayMethod(method) {
        currentPayMethod = method;
        document.querySelectorAll('.pay-method-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.method === method);
        });
        const cashSection = document.getElementById('cash-section');
        cashSection.style.display = method === 'Cash' ? 'block' : 'none';
        calcChange();
        updateCheckoutState();
    }

    // ---- Hitung total ----
    function getTotal() {
        return cart.reduce((s, item) => {
            const addonTotal = Object.values(item.addonPrices || {}).reduce((a, b) => a + b, 0);
            return s + (item.price + addonTotal) * item.qty;
        }, 0);
    }

    // ---- Quick cash shortcut buttons ----
    function roundUpTo(amount, step) {
        return Math.ceil(amount / step) * step;
    }

    function renderQuickCash(total) {
        const row = document.getElementById('quick-cash-row');
        if (currentPayMethod !== 'Cash' || total <= 0) {
            row.innerHTML = '';
            return;
        }
        const suggestions = new Set();
        suggestions.add(total); // uang pas
        [5000, 10000, 20000, 50000, 100000].forEach(step => {
            if (step > total) suggestions.add(roundUpTo(total, step));
        });
        const list = Array.from(suggestions).sort((a, b) => a - b).slice(0, 4);

        row.innerHTML = list.map(amount => `
            <button type="button" class="quick-cash-btn ${amount === total ? 'exact' : ''}" onclick="setQuickCash(${amount})">
                ${amount === total ? 'Uang Pas' : fmt(amount).replace('Rp ', '')}
            </button>
        `).join('');
    }

    function setQuickCash(amount) {
        document.getElementById('pay-amount').value = amount;
        calcChange();
    }

    function calcChange() {
        if (currentPayMethod !== 'Cash') {
            updateCheckoutState();
            return;
        }

        const total = getTotal();
        const payVal = parseInt(document.getElementById('pay-amount').value) || 0;
        const changeRow = document.getElementById('change-row');
        const changeLbl = document.getElementById('change-label');
        const changeAmt = document.getElementById('change-amount');
        const payInput = document.getElementById('pay-amount');

        if (payVal === 0) {
            changeRow.classList.remove('minus');
            changeLbl.innerText = 'Kembalian';
            changeAmt.innerText = '—';
            payInput.classList.remove('error');
            updateCheckoutState();
            return;
        }

        const change = payVal - total;
        if (change < 0) {
            changeRow.classList.add('minus');
            changeLbl.innerText = 'Kurang';
            changeAmt.innerText = fmt(Math.abs(change));
            payInput.classList.add('error');
        } else {
            changeRow.classList.remove('minus');
            changeLbl.innerText = 'Kembalian';
            changeAmt.innerText = fmt(change);
            payInput.classList.remove('error');
        }
        updateCheckoutState();
    }

    // ---- Live checkout-button validation (fix: dulu cuma divalidasi saat klik) ----
    function updateCheckoutState() {
        const checkoutBtn = document.getElementById('checkout-btn');
        const hint = document.getElementById('checkout-hint');
        const total = getTotal();

        if (cart.length === 0) {
            checkoutBtn.disabled = true;
            hint.classList.remove('show');
            return;
        }

        if (currentPayMethod === 'Cash') {
            const payVal = parseInt(document.getElementById('pay-amount').value) || 0;
            const insufficient = payVal < total;
            checkoutBtn.disabled = insufficient;
            hint.classList.toggle('show', insufficient);
        } else {
            checkoutBtn.disabled = false;
            hint.classList.remove('show');
        }
    }

    function resetChange() {
        const payInput = document.getElementById('pay-amount');
        if (payInput) payInput.value = '';
        const changeAmt = document.getElementById('change-amount');
        if (changeAmt) changeAmt.innerText = '—';
        const changeRow = document.getElementById('change-row');
        if (changeRow) changeRow.classList.remove('minus');
        const changeLbl = document.getElementById('change-label');
        if (changeLbl) changeLbl.innerText = 'Kembalian';
        document.getElementById('quick-cash-row').innerHTML = '';
    }

    // ---- Modal konfirmasi ----
    function openModal() {
        if (cart.length === 0) return;

        const total = getTotal();
        const payVal = parseInt(document.getElementById('pay-amount').value) || 0;

        if (currentPayMethod === 'Cash' && payVal < total) {
            document.getElementById('pay-amount').classList.add('error');
            document.getElementById('pay-amount').focus();
            showToast('Nominal bayar kurang dari total!');
            return;
        }

        const itemsHtml = cart.map(item => {
            const addonTotal = Object.values(item.addonPrices || {}).reduce((a, b) => a + b, 0);
            const addonNames = (item.addons || []).filter(a => item.addonPrices[a] > 0);
            return `
            <div class="modal-row">
                <span class="modal-row-label" style="color: #1a1a1a; font-weight: 500;">
                    ${escapeHtml(item.name)}
                    ${addonNames.length ? `<br><small style="color:#aaa;">+ ${addonNames.map(escapeHtml).join(', ')}</small>` : ''}
                    <span style="color: #888;"> ×${item.qty}</span>
                </span>
                <span class="modal-row-val">${fmt((item.price + addonTotal) * item.qty)}</span>
            </div>
        `;
        }).join('');
        document.getElementById('modal-items').innerHTML = itemsHtml;
        document.getElementById('modal-subtotal').innerText = fmt(total);
        document.getElementById('modal-method').innerText = currentPayMethod === 'Cash' ? 'Tunai' : currentPayMethod;

        const payRow = document.getElementById('modal-pay-row');
        const changeRow = document.getElementById('modal-change-row');

        if (currentPayMethod === 'Cash') {
            const change = payVal - total;
            payRow.style.display = 'flex';
            changeRow.style.display = 'flex';
            document.getElementById('modal-pay').innerText = fmt(payVal);
            document.getElementById('modal-change').innerText = fmt(change);
        } else {
            payRow.style.display = 'none';
            changeRow.style.display = 'none';
        }

        document.getElementById('confirm-modal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('confirm-modal').classList.remove('show');
        const btn = document.getElementById('modal-confirm-btn');
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="bi bi-check2-circle"></i> Proses Sekarang';
    }

    function closeModalOnBackdrop(e) {
        if (e.target === document.getElementById('confirm-modal')) closeModal();
    }

    // ---- Proses checkout ----
    function processCheckout() {
        const btn = document.getElementById('modal-confirm-btn');
        if (btn.classList.contains('loading')) return; // anti double submit
        btn.classList.add('loading');
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

        const total = getTotal();
        const payVal = currentPayMethod === 'Cash' ? (parseInt(document.getElementById('pay-amount').value) || total) : total;
        const change = currentPayMethod === 'Cash' ? payVal - total : 0;

        fetch("{{ route('pos.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart: cart,
                    total_price: total,
                    pay_amount: payVal,
                    change_amount: change,
                    payment_method: currentPayMethod
                })
            })
            .then(r => r.json())
            .then(async data => {
                closeModal();
                if (data.transaction_id) {
                    cart.forEach(item => updateRecent(item.id, item.name, item.price));

                    cart = [];
                    setPayMethod('Cash');
                    renderCart();
                    showToast('✓ Transaksi berhasil!');

                    const print = await showAlert('Transaksi berhasil disimpan. Cetak struk sekarang?', {
                        title: 'Cetak Struk',
                        okText: 'Cetak',
                        cancelText: 'Nanti'
                    });
                    if (print) {
                        window.open('/pos/print/' + data.transaction_id, '_blank');
                    }
                } else {
                    await showAlert(data.message || 'Transaksi gagal diproses.', {
                        title: 'Gagal',
                        okOnly: true,
                        danger: true
                    });
                }
            })
            .catch(err => {
                closeModal();
                console.error(err);
                showAlert('Terjadi kesalahan sistem. Coba lagi.', {
                    title: 'Kesalahan',
                    okOnly: true,
                    danger: true
                });
            });
    }

    // ---- Keyboard shortcut ----
    document.addEventListener('keydown', function(e) {
        const confirmOpen = document.getElementById('confirm-modal').classList.contains('show');
        const alertOpen = document.getElementById('alert-modal').classList.contains('show');

        if (e.key === 'Escape') {
            if (confirmOpen) closeModal();
        }
        // Hanya proses Enter sebagai shortcut checkout kalau modal konfirmasi
        // sedang terbuka DAN fokus bukan di dalam alert modal.
        if (e.key === 'Enter' && confirmOpen && !alertOpen) {
            e.preventDefault();
            processCheckout();
        }
    });

    // ---- Search ----
    const searchInput = document.getElementById('search-menu');
    const searchClearBtn = document.getElementById('search-clear');

    searchInput.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        searchClearBtn.classList.toggle('show', q.length > 0);
        let visibleCount = 0;
        document.querySelectorAll('.product-item').forEach(el => {
            const match = el.dataset.productName.toLowerCase().includes(q);
            el.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
        document.getElementById('empty-search').classList.toggle('show', visibleCount === 0 && q.length > 0);
    });

    function clearSearch() {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    }

    // ---- Toast ----
    let toastTimer = null;
    function showToast(msg) {
        const t = document.getElementById('pos-toast');
        t.innerText = msg;
        t.classList.add('show');
        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(() => t.classList.remove('show'), 2400);
    }

    // ---- Init ----
    if (recentMenus.length > 0) renderRecent();
    setPayMethod('Cash');
    renderCart();
</script>

@endsection