@extends('dashboard')

@section('content')
<style>
    :root {
        --kopi-coklat: #6F4E37;
        --kopi-coklat-tua: #4A3324;
        --kopi-krem: #C9A77C;
        --kopi-krem-muda: #F3E9DC;
        --kopi-merah: #C8553D;
        --kopi-oranye: #E0883A;
        --kopi-kuning: #D9A23B;
        --kopi-hijau: #4F8A5B;
    }

    .stok-page h1.stok-title {
        font-weight: 700;
        color: var(--kopi-coklat-tua);
        letter-spacing: -0.02em;
        font-size: 1.5rem;
    }

    .stok-page .stok-subtitle {
        color: #8a7a6d;
        font-size: 0.875rem;
    }

    .stok-summary-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
    }

    .stok-summary-pill {
        background: #fff;
        border-radius: 12px;
        padding: 0.9rem 1.3rem;
        box-shadow: 0 2px 10px rgba(74, 51, 36, 0.06);
        display: flex;
        align-items: center;
        gap: 0.7rem;
        min-width: 150px;
    }

    .stok-summary-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .stok-summary-dot.habis   { background: var(--kopi-merah); }
    .stok-summary-dot.kritis  { background: var(--kopi-oranye); }
    .stok-summary-dot.menipis { background: var(--kopi-kuning); }
    .stok-summary-dot.aman    { background: var(--kopi-hijau); }

    .stok-summary-count {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--kopi-coklat-tua);
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .stok-summary-label {
        font-size: 0.75rem;
        color: #8a7a6d;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .stok-card {
        background: #fff;
        border-radius: 14px;
        padding: 1.25rem;
        height: 100%;
        box-shadow: 0 3px 14px rgba(74, 51, 36, 0.07);
        border-top: 4px solid transparent;
        transition: transform 0.12s ease, box-shadow 0.12s ease;
    }

    .stok-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(74, 51, 36, 0.12);
    }

    .stok-card.habis   { border-top-color: var(--kopi-merah); }
    .stok-card.kritis  { border-top-color: var(--kopi-oranye); }
    .stok-card.menipis { border-top-color: var(--kopi-kuning); }
    .stok-card.aman    { border-top-color: var(--kopi-hijau); }

    .stok-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.9rem;
    }

    .stok-card-nama {
        font-weight: 700;
        color: var(--kopi-coklat-tua);
        font-size: 0.95rem;
        margin: 0;
        line-height: 1.3;
    }

    .stok-card-badge {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        color: #fff;
        white-space: nowrap;
        flex-shrink: 0;
        margin-left: 0.5rem;
    }

    .stok-card-badge.habis   { background: var(--kopi-merah); }
    .stok-card-badge.kritis  { background: var(--kopi-oranye); }
    .stok-card-badge.menipis { background: var(--kopi-kuning); color: #4A3324; }
    .stok-card-badge.aman    { background: var(--kopi-hijau); }

    .stok-gauge {
        background: #f0e9de;
        border-radius: 8px;
        height: 8px;
        overflow: hidden;
        margin-bottom: 0.4rem;
    }

    .stok-gauge-fill {
        height: 100%;
        border-radius: 8px;
        transition: width 0.4s ease;
    }

    .stok-gauge-fill.habis   { background: var(--kopi-merah); }
    .stok-gauge-fill.kritis  { background: var(--kopi-oranye); }
    .stok-gauge-fill.menipis { background: var(--kopi-kuning); }
    .stok-gauge-fill.aman    { background: var(--kopi-hijau); }

    .stok-gauge-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #8a7a6d;
        margin-bottom: 1rem;
    }

    .stok-card-stat {
        font-size: 0.8rem;
        color: #5b4d40;
        margin-bottom: 0.3rem;
    }

    .stok-card-stat strong {
        color: var(--kopi-coklat-tua);
    }

    .sumber-badge {
        font-size: 0.62rem;
        font-weight: 700;
        padding: 0.12rem 0.4rem;
        border-radius: 6px;
        margin-left: 0.35rem;
        vertical-align: middle;
    }

    .sumber-badge.lstm      { background: #dbe9ff; color: #1d4ed8; }
    .sumber-badge.rata_rata { background: #ececec; color: #6b6b6b; }

    .stok-card-cta {
        display: inline-block;
        margin-top: 0.8rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--kopi-coklat);
        text-decoration: none;
    }

    .stok-card-cta:hover {
        color: var(--kopi-coklat-tua);
        text-decoration: underline;
    }

    .stok-empty {
        background: #fff;
        border-radius: 14px;
        padding: 3rem 2rem;
        text-align: center;
        color: #8a7a6d;
        font-size: 0.875rem;
    }
</style>

<div class="stok-page container-fluid ps-0 pe-2">

    <h1 class="stok-title mb-1">Status Stok</h1>
    <p class="stok-subtitle mb-4">Ringkasan seluruh bahan baku, diurutkan dari yang paling butuh perhatian.</p>

    @php
        $jumlahPerStatus = ['habis' => 0, 'kritis' => 0, 'menipis' => 0, 'aman' => 0];
        foreach ($daftarStok as $item) {
            $jumlahPerStatus[$item['status']]++;
        }
        $labelRingkasan = [
            'habis'   => 'Stok habis',
            'kritis'  => 'Kritis',
            'menipis' => 'Menipis',
            'aman'    => 'Aman',
        ];
    @endphp

    {{-- Ringkasan jumlah per status --}}
    <div class="stok-summary-row">
        @foreach ($jumlahPerStatus as $status => $jumlah)
            <div class="stok-summary-pill">
                <span class="stok-summary-dot {{ $status }}"></span>
                <div>
                    <div class="stok-summary-count">{{ $jumlah }}</div>
                    <div class="stok-summary-label">{{ $labelRingkasan[$status] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    @if (count($daftarStok) === 0)
        <div class="stok-empty">
            <i class="fas fa-box-open fa-2x mb-3"></i>
            <p class="mb-0">Belum ada bahan baku dengan resep terdaftar.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach ($daftarStok as $item)
                @php
                    $label = $labelRingkasan[$item['status']];

                    $acuanPenuh = $item['min_stock'] > 0
                        ? $item['min_stock'] * 2
                        : max($item['rata_rata_harian'] * 7, 1);

                    $persen = $acuanPenuh > 0
                        ? min(100, round(($item['stok_sekarang'] / $acuanPenuh) * 100))
                        : 0;
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="stok-card {{ $item['status'] }}">
                        <div class="stok-card-header">
                            <p class="stok-card-nama">{{ $item['nama'] }}</p>
                            <span class="stok-card-badge {{ $item['status'] }}">{{ $label }}</span>
                        </div>

                        <div class="stok-gauge">
                            <div class="stok-gauge-fill {{ $item['status'] }}" style="width: {{ $persen }}%;"></div>
                        </div>
                        <div class="stok-gauge-label">
                            <span>{{ $item['stok_sekarang'] }} {{ $item['satuan'] }} tersisa</span>
                            <span>min. {{ $item['min_stock'] }} {{ $item['satuan'] }}</span>
                        </div>

                        <div class="stok-card-stat">
                            Rata-rata pakai: <strong>{{ $item['rata_rata_harian'] }} {{ $item['satuan'] }}/hari</strong>
                        </div>

                        <div class="stok-card-stat">
                            Prediksi besok:
                            <strong>{{ $item['prediksi_besok'] }} {{ $item['satuan'] }}</strong>
                            @if ($item['sumber_prediksi'] === 'lstm')
                                <span class="sumber-badge lstm" title="Dihitung oleh model LSTM">AI</span>
                            @else
                                <span class="sumber-badge rata_rata" title="Model AI belum tersedia untuk bahan ini, dipakai estimasi rata-rata 7 hari sebagai fallback">
                                    Estimasi
                                </span>
                            @endif
                        </div>

                        @if ($item['estimasi_hari_habis'] !== null)
                            <div class="stok-card-stat">
                                Estimasi habis: <strong>{{ $item['estimasi_hari_habis'] }} hari lagi</strong>
                            </div>
                        @else
                            <div class="stok-card-stat text-muted fst-italic">
                                Belum ada cukup riwayat pemakaian.
                            </div>
                        @endif

                        <a href="{{ route('lstm.index', ['bahan' => $item['nama']]) }}" class="stok-card-cta">
    Lihat prediksi detail &rarr;
</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection