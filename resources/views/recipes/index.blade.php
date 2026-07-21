@extends('dashboard')

@section('title', 'Resep')

@section('content')

<style>
    .resep-wrapper * {
        box-sizing: border-box;
    }

    .resep-wrapper {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 4px 0 32px;
    }

    /* ── PAGE HEADER ── */
    .resep-header {
        margin-bottom: 24px;
    }
    .resep-header h4 {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .resep-header h4 .menu-chip {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 13px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
    }
    .resep-header p {
        font-size: 13px;
        color: #7a8a7a;
        margin: 0;
    }

    /* ── CARD ── */
    .card-kk {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
    }
    .card-kk-body { padding: 22px; }
    .card-kk-title {
        font-size: 13px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .card-kk-title-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        background: #e6f4ee;
        color: #3d7a5e;
    }

    /* ── FORM ── */
    .form-label-kk {
        font-size: 11px;
        font-weight: 700;
        color: #6a7a6a;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 6px;
        display: block;
    }
    .form-control-kk,
    .form-select-kk {
        border: 1px solid #e4e0d8;
        border-radius: 11px;
        padding: 10px 14px;
        font-size: 13px;
        color: #1a1f1a;
        background: #faf9f6;
        width: 100%;
        transition: border-color 0.15s, box-shadow 0.15s;
        appearance: none;
    }
    .form-control-kk:focus,
    .form-select-kk:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61,122,94,0.12);
        background: white;
        outline: none;
    }
    .select-wrapper { position: relative; }
    .select-wrapper::after {
        content: '\f282';
        font-family: 'Bootstrap Icons';
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aaa9a;
        font-size: 12px;
        pointer-events: none;
    }

    .btn-simpan-kk {
        width: 100%;
        background: #3d7a5e;
        border: none;
        border-radius: 12px;
        padding: 11px;
        font-size: 13px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        transition: background 0.15s, transform 0.15s;
        margin-top: 4px;
    }
    .btn-simpan-kk:hover { background: #2d6a4e; transform: translateY(-1px); }

    /* ── INFO BOX ── */
    .info-box {
        background: #faf9f6;
        border: 1px solid #eceae4;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
    }
    .info-box i { color: #3d7a5e; margin-top: 1px; font-size: 14px; }
    .info-box p { font-size: 12px; color: #6a7a6a; margin: 0; line-height: 1.5; }

    /* ── HPP CARD ── */
    .hpp-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
        margin-top: 16px;
    }
    .hpp-card-header {
        padding: 16px 20px 12px;
        border-bottom: 1px solid #f0ede6;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .hpp-card-header-icon {
        width: 30px;
        height: 30px;
        border-radius: 9px;
        background: #fff3de;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
    .hpp-card-header span {
        font-size: 13px;
        font-weight: 800;
        color: #1a1f1a;
    }
    .hpp-card-body { padding: 18px 20px; }

    /* HPP nilai */
    .hpp-value-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 14px;
        border-bottom: 1px dashed #eceae4;
    }
    .hpp-value-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 700;
        color: #9aaa9a;
    }
    .hpp-value-num {
        font-size: 18px;
        font-weight: 800;
        color: #d97706;
    }

    /* Slider margin */
    .margin-label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }
    .margin-label-row .form-label-kk { margin: 0; }
    .margin-pct-badge {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 12px;
        font-weight: 800;
        padding: 2px 10px;
        border-radius: 20px;
        min-width: 48px;
        text-align: center;
    }

    input[type="range"].slider-kk {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 5px;
        border-radius: 10px;
        background: #e4e0d8;
        outline: none;
        margin-bottom: 18px;
    }
    input[type="range"].slider-kk::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #3d7a5e;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 1px 6px rgba(61,122,94,0.35);
    }
    input[type="range"].slider-kk::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #3d7a5e;
        cursor: pointer;
        border: 2px solid white;
    }

    /* Hasil estimasi */
    .estimasi-box {
        background: #f0f7f3;
        border: 1.5px solid #b2dcc8;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 14px;
    }
    .estimasi-box-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        font-weight: 700;
        color: #6a9a7a;
        margin-bottom: 4px;
    }
    .estimasi-box-harga {
        font-size: 22px;
        font-weight: 800;
        color: #3d7a5e;
        line-height: 1;
    }
    .estimasi-box-sub {
        font-size: 11px;
        color: #8aaa9a;
        margin-top: 4px;
    }

    /* Breakdown margin */
    .breakdown-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6a7a6a;
        padding: 4px 0;
    }
    .breakdown-row span:last-child { font-weight: 700; color: #1a1f1a; }

    /* Preset margin buttons */
    .preset-row {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }
    .preset-btn {
        background: #faf9f6;
        border: 1px solid #e4e0d8;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
        color: #5a6a5a;
        cursor: pointer;
        transition: all 0.15s;
    }
    .preset-btn:hover,
    .preset-btn.active {
        background: #e6f4ee;
        border-color: #b2dcc8;
        color: #3d7a5e;
    }

    .hpp-warning {
        background: #fff8e6;
        border: 1px solid #f6d860;
        border-radius: 10px;
        padding: 10px 13px;
        font-size: 12px;
        color: #92610a;
        display: flex;
        gap: 8px;
        align-items: flex-start;
    }
    .hpp-warning i { margin-top: 1px; }

    /* ── TABLE ── */
    .table-kk thead th {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9aaa9a;
        font-weight: 700;
        padding: 13px 16px;
        background: #faf9f6;
        border-bottom: 1px solid #f0ede6;
        white-space: nowrap;
    }
    .table-kk tbody td {
        padding: 13px 16px;
        font-size: 13px;
        color: #3a4a3a;
        border-bottom: 1px solid #f5f4f0;
        vertical-align: middle;
    }
    .table-kk tbody tr:last-child td { border-bottom: none; }
    .table-kk tbody tr:hover td { background: #faf9f7; }
    .td-bahan { font-weight: 700; color: #1a1f1a; }
    .takaran-val { font-weight: 800; color: #3d7a5e; }
    .takaran-unit { color: #9aaa9a; font-size: 12px; margin-left: 3px; }
    .hpp-per-bahan { font-size: 11px; color: #9aaa9a; font-weight: 600; }

    .row-num {
        width: 24px; height: 24px;
        background: #f0ede6; border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #7a6a50;
    }

    .btn-hapus-kk {
        width: 32px; height: 32px; border-radius: 9px;
        border: 1px solid #eceae4; background: white;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; color: #c53030;
        cursor: pointer; transition: all 0.15s; padding: 0;
    }
    .btn-hapus-kk:hover { background: #fde8e8; border-color: #f5b8b8; }

    .empty-resep {
        text-align: center; padding: 48px 16px; color: #9aaa9a;
    }
    .empty-resep .empty-icon { font-size: 36px; margin-bottom: 10px; display: block; opacity: 0.35; }
    .empty-resep p { font-size: 13px; margin: 0; }

    .resep-footer {
        padding: 14px 20px;
        background: #faf9f6;
        border-top: 1px solid #f0ede6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .resep-footer span { font-size: 12px; color: #9aaa9a; font-weight: 600; }
    .resep-footer strong { font-size: 13px; color: #3d7a5e; font-weight: 800; }

    /* ── ALERT ── */
    .alert-kk-resep {
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 16px;
        font-size: 13px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .alert-kk-resep.success { background: #e6f4ee; border: 1px solid #b2dcc8; color: #3d7a5e; }
    .alert-kk-resep.error { background: #fde8e8; border: 1px solid #f5b8b8; color: #c53030; }
    .alert-kk-resep .alert-close {
        background: none; border: none; color: inherit; cursor: pointer; font-size: 16px;
    }

    /* ── BACK BUTTON ── */
    .btn-kembali-resep {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: white;
        border: 1px solid #e4e0d8;
        border-radius: 20px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        color: #3a4a3a;
        text-decoration: none;
        transition: all 0.15s;
    }
    .btn-kembali-resep:hover { background: #f0ede6; color: #1a1f1a; text-decoration: none; }
</style>

<div class="resep-wrapper">

    {{-- ── ALERT ── --}}
    @if(session('success'))
    <div class="alert-kk-resep success">
        <div><i class="bi bi-check-circle-fill" style="margin-right: 8px;"></i>{{ session('success') }}</div>
        <button onclick="this.parentElement.remove()" class="alert-close"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-kk-resep error">
        <div><i class="bi bi-exclamation-triangle-fill" style="margin-right: 8px;"></i>{{ session('error') }}</div>
        <button onclick="this.parentElement.remove()" class="alert-close"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif

    {{-- ── PAGE HEADER ── --}}
    <div class="resep-header">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h4>
                    <i class="bi bi-journal-text" style="color:#3d7a5e"></i>
                    Resep
                    <span class="menu-chip">{{ $product->name }}</span>
                </h4>
                <p>Atur komposisi bahan baku dan estimasi harga jual untuk menu ini</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn-kembali-resep">
                <i class="bi bi-arrow-left"></i> Kembali ke Inventori
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── KOLOM KIRI: Form + HPP ── --}}
        <div class="col-md-4">

            {{-- Form Tambah Bahan --}}
            <div class="card-kk">
                <div class="card-kk-body">
                    <div class="card-kk-title">
                        <div class="card-kk-title-icon">
                            <i class="bi bi-plus-lg"></i>
                        </div>
                        Tambah Bahan Baku
                    </div>

                    <div class="info-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <p>Jumlah pemakaian dihitung per <strong>1 sajian</strong> menu ini saat transaksi.</p>
                    </div>

                    <form action="{{ route('recipes.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-kk">Pilih Bahan</label>
                            <div class="select-wrapper">
                                <select name="ingredient_id" class="form-select-kk" required>
                                    <option value="">— Pilih Bahan Baku —</option>
                                    @foreach($ingredients as $ing)
                                        <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-kk">Jumlah Pemakaian</label>
                            <input type="number"
                                name="usage_amount"
                                class="form-control-kk"
                                placeholder="Contoh: 18"
                                min="0" step="any" required>
                        </div>
                        <button type="submit" class="btn-simpan-kk">
                            <i class="bi bi-floppy me-2"></i> Simpan Bahan
                        </button>
                    </form>
                </div>
            </div>

            {{-- ── HPP & ESTIMASI HARGA JUAL ── --}}
            <div class="hpp-card">
                <div class="hpp-card-header">
                    <div class="hpp-card-header-icon">
                        <i class="bi bi-calculator"></i>
                    </div>
                    <span>HPP & Estimasi Harga Jual</span>
                </div>
                <div class="hpp-card-body">

                    @if($product->recipes->count() === 0)
                        {{-- Belum ada resep --}}
                        <div class="hpp-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Tambahkan bahan baku ke resep terlebih dahulu untuk menghitung HPP.</span>
                        </div>

                    @elseif($hpp <= 0)
                        {{-- Resep ada tapi harga modal belum diisi --}}
                        <div class="hpp-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Pastikan semua bahan baku sudah memiliki <strong>harga modal</strong> dan <strong>stok &gt; 0</strong> di halaman Inventori.</span>
                        </div>

                    @else
                        {{-- HPP tersedia, tampilkan kalkulator --}}

                        {{-- Nilai HPP --}}
                        <div class="hpp-value-row">
                            <div>
                                <div class="hpp-value-label">HPP per Sajian</div>
                                <div style="font-size:11px; color:#9aaa9a; margin-top:2px">
                                    Biaya bahan baku langsung
                                </div>
                            </div>
                            <div class="hpp-value-num">
                                Rp {{ number_format($hpp, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Preset Margin --}}
                        <div class="form-label-kk" style="margin-bottom:8px">Preset Margin</div>
                        <div class="preset-row">
                            <button class="preset-btn" data-margin="30">30%</button>
                            <button class="preset-btn" data-margin="50">50%</button>
                            <button class="preset-btn active" data-margin="100">100%</button>
                            <button class="preset-btn" data-margin="150">150%</button>
                            <button class="preset-btn" data-margin="200">200%</button>
                        </div>

                        {{-- Slider --}}
                        <div class="margin-label-row">
                            <label class="form-label-kk">Margin Keuntungan</label>
                            <span class="margin-pct-badge" id="marginDisplay">100%</span>
                        </div>
                        <input type="range"
                            class="slider-kk"
                            id="marginSlider"
                            min="10" max="300" step="5" value="100">

                        {{-- Hasil estimasi --}}
                        <div class="estimasi-box">
                            <div class="estimasi-box-label">Estimasi Harga Jual</div>
                            <div class="estimasi-box-harga" id="estimasiHarga">
                                Rp {{ number_format(ceil(($hpp * 2) / 500) * 500, 0, ',', '.') }}
                            </div>
                            <div class="estimasi-box-sub" id="estimasiSub">
                                Dibulatkan ke kelipatan Rp 500 terdekat
                            </div>
                        </div>

                        {{-- Breakdown --}}
                        <div class="breakdown-row">
                            <span>HPP</span>
                            <span>Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                        </div>
                        <div class="breakdown-row">
                            <span>Keuntungan</span>
                            <span id="breakdownKeuntungan">Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                        </div>
                        <div class="breakdown-row" style="border-top:1px solid #f0ede6; margin-top:6px; padding-top:8px">
                            <span style="font-weight:700; color:#1a1f1a">Harga Jual (bulat)</span>
                            <span id="breakdownHargaJual" style="color:#3d7a5e">
                                Rp {{ number_format(ceil(($hpp * 2) / 500) * 500, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Harga terdaftar saat ini --}}
                        @if($product->price > 0)
                        <div style="margin-top: 14px; padding-top: 12px; border-top: 1px dashed #eceae4;">
                            <div class="breakdown-row">
                                <span>Harga Jual Terdaftar</span>
                                <span style="color:#3d7a5e">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="breakdown-row">
                                <span>Margin Aktual</span>
                                <span id="marginAktual" style="color:#d97706">
                                    @php
                                        $marginAktual = $hpp > 0
                                            ? round((($product->price - $hpp) / $hpp) * 100)
                                            : 0;
                                    @endphp
                                    {{ $marginAktual }}%
                                </span>
                            </div>
                        </div>
                        @endif

                    @endif
                </div>
            </div>

        </div>{{-- end col kiri --}}

        {{-- ── KOLOM KANAN: Tabel Resep ── --}}
        <div class="col-md-8">
            <div class="card-kk">
                <div style="padding: 18px 20px 14px; border-bottom: 1px solid #f0ede6;">
                    <div class="card-kk-title" style="margin-bottom:0">
                        <div class="card-kk-title-icon" style="background:#f0ede6; color:#7a6a50">
                            <i class="bi bi-list-check"></i>
                        </div>
                        Komposisi Resep
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-kk mb-0">
                        <thead>
                            <tr>
                                <th style="padding-left:20px; width:44px">#</th>
                                <th>Bahan Baku</th>
                                <th>Takaran per Sajian</th>
                                <th>Biaya Bahan</th>
                                <th class="text-end" style="padding-right:20px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->recipes as $i => $recipe)
                            @php
                                $ing         = $recipe->ingredient;
                                $hargaPerUnit = ($ing && $ing->stock > 0)
                                    ? $ing->purchase_price / $ing->stock
                                    : 0;
                                $biayaBahan  = $recipe->usage_amount * $hargaPerUnit;
                            @endphp
                            <tr>
                                <td style="padding-left:20px">
                                    <span class="row-num">{{ $i + 1 }}</span>
                                </td>
                                <td class="td-bahan">{{ $recipe->ingredient->name }}</td>
                                <td>
                                    <span class="takaran-val">{{ $recipe->usage_amount }}</span>
                                    <span class="takaran-unit">{{ $recipe->ingredient->unit }}</span>
                                </td>
                                <td>
                                    @if($biayaBahan > 0)
                                        <span class="hpp-per-bahan">
                                            Rp {{ number_format($biayaBahan, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="hpp-per-bahan" style="color:#e4b84a">
                                            — harga belum diisi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end" style="padding-right:20px">
                                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-hapus-kk"
                                            onclick="return confirm('Hapus bahan ini dari resep?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-resep">
                                        <span class="empty-icon">🧪</span>
                                        <p>Belum ada bahan baku dalam resep ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($product->recipes->count() > 0)
                <div class="resep-footer">
                    <span>Total bahan terdaftar</span>
                    <div style="display:flex; gap:16px; align-items:center">
                        <span>{{ $product->recipes->count() }} bahan</span>
                        @if($hpp > 0)
                        <strong>HPP: Rp {{ number_format($hpp, 0, ',', '.') }}</strong>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- ── JAVASCRIPT HPP KALKULATOR ── --}}
@if($hpp > 0)
<script>
    const hpp        = {{ $hpp }};
    const slider     = document.getElementById('marginSlider');
    const display    = document.getElementById('marginDisplay');
    const hargaEl    = document.getElementById('estimasiHarga');
    const subEl      = document.getElementById('estimasiSub');
    const keuntEl    = document.getElementById('breakdownKeuntungan');
    const hargaJualEl = document.getElementById('breakdownHargaJual');

    function bulatkan(angka) {
        // Bulatkan ke kelipatan 500 terdekat ke atas
        return Math.ceil(angka / 500) * 500;
    }

    function formatRupiah(angka) {
        return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
    }

    function hitungDanTampilkan(margin) {
        const hargaMentah  = hpp * (1 + margin / 100);
        const hargaBulat   = bulatkan(hargaMentah);
        const keuntungan   = hargaBulat - hpp;
        const marginAktual = ((hargaBulat - hpp) / hpp * 100).toFixed(1);

        display.textContent      = margin + '%';
        hargaEl.textContent      = formatRupiah(hargaBulat);
        keuntEl.textContent      = formatRupiah(keuntungan);
        hargaJualEl.textContent  = formatRupiah(hargaBulat);
        subEl.textContent        = 'Margin aktual setelah pembulatan: ' + marginAktual + '%';
    }

    // Slider event
    slider.addEventListener('input', function () {
        hitungDanTampilkan(parseInt(this.value));

        // Update preset active state
        document.querySelectorAll('.preset-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.margin == this.value);
        });
    });

    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const margin = parseInt(this.dataset.margin);
            slider.value = margin;
            hitungDanTampilkan(margin);

            document.querySelectorAll('.preset-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Init
    hitungDanTampilkan(100);
</script>
@endif

@endsection