@extends('dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap');

    :root {
        --kopi-espresso:   #2B1B12;
        --kopi-roast:      #6B4226;
        --kopi-paper:      #FBF4E8;
        --kopi-paper-line: #E9DCC3;
        --kopi-amber:      #C6893F;
        --kopi-sage:       #4F7A56;
        --kopi-gold:       #B8892E;
        --kopi-rust:       #C1602E;
        --kopi-brick:      #9B3226;
    }

    .lstm-page { font-family: 'Space Grotesk', sans-serif; color: var(--kopi-espresso); }

    .lstm-page .lstm-header { margin-bottom: 1.75rem; }

    .lstm-page .lstm-eyebrow {
        display: inline-block;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.72rem;
        font-weight: 500;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--kopi-roast);
        border-bottom: 1px solid var(--kopi-paper-line);
        padding-bottom: 0.4rem;
        margin-bottom: 0.6rem;
    }

    .lstm-page h1.lstm-title {
        font-family: 'Fraunces', serif;
        font-weight: 700;
        font-size: 2.1rem;
        color: var(--kopi-espresso);
        letter-spacing: -0.01em;
        margin-bottom: 0.35rem;
    }

    .lstm-page .lstm-subtitle { color: #7d6b5a; font-size: 0.95rem; max-width: 46ch; }

    /* ---- Ticket container ---- */
    .lstm-ticket {
        background: var(--kopi-paper);
        border-radius: 4px;
        box-shadow: 0 14px 34px -18px rgba(43, 27, 18, 0.45), 0 1px 0 rgba(43,27,18,0.05);
        overflow: hidden;
        position: relative;
        margin-bottom: 2rem;
    }

    .lstm-ticket__tab {
        background: var(--kopi-espresso);
        color: var(--kopi-paper);
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.8rem;
        letter-spacing: 0.06em;
        padding: 0.85rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .lstm-ticket__tab i { color: var(--kopi-amber); }

    .lstm-punch {
        height: 14px;
        background: var(--kopi-paper-line);
        background-image: radial-gradient(circle 6px, var(--kopi-paper) 99%, transparent 100%);
        background-size: 26px 14px;
        background-position: center;
        background-repeat: repeat-x;
    }

    .lstm-ticket__body { padding: 1.85rem 1.9rem 2rem; }

    .lstm-form-label {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        color: var(--kopi-roast);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .lstm-select {
        border: 1.5px solid var(--kopi-paper-line);
        border-radius: 8px;
        padding: 0.7rem 0.9rem;
        background-color: #fff;
        font-family: 'Space Grotesk', sans-serif;
    }

    .lstm-select:focus {
        border-color: var(--kopi-roast);
        box-shadow: 0 0 0 0.2rem rgba(107, 66, 38, 0.14);
    }

    .btn-kopi {
        background: var(--kopi-espresso);
        border: none;
        color: var(--kopi-paper);
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        font-size: 0.92rem;
        transition: background 0.15s ease, transform 0.1s ease;
    }
    .btn-kopi:hover { background: var(--kopi-roast); color: #fff; transform: translateY(-1px); }

    .btn-kopi-outline {
        border: 1.5px solid var(--kopi-roast);
        color: var(--kopi-roast);
        border-radius: 8px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        font-size: 0.92rem;
        background: transparent;
        transition: all 0.15s ease;
    }
    .btn-kopi-outline:hover { background: var(--kopi-paper-line); color: var(--kopi-espresso); }

    /* ---- Result block ---- */
    .lstm-result {
        border: 1px solid var(--kopi-paper-line);
        border-radius: 10px;
        padding: 1.5rem 1.6rem;
        margin-bottom: 1.9rem;
        background: #fffdf9;
    }

    .lstm-result__title {
        font-family: 'Fraunces', serif;
        font-weight: 600;
        font-size: 1.15rem;
        color: var(--kopi-espresso);
        margin-bottom: 1.1rem;
    }

    .lstm-result__grid {
        display: grid;
        grid-template-columns: minmax(0,1fr) auto;
        gap: 1.75rem;
        align-items: start;
    }

    @media (max-width: 767px) {
        .lstm-result__grid { grid-template-columns: 1fr; }
    }

    /* Stamp strip (riwayat 7 hari) */
    .stamp-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem;
        margin-bottom: 1.1rem;
    }

    .stamp {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        border: 1.5px dashed var(--kopi-paper-line);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-family: 'IBM Plex Mono', monospace;
        line-height: 1.05;
        color: #8a7a68;
        background: #fff;
    }

    .stamp small { font-size: 0.58rem; letter-spacing: 0.05em; text-transform: uppercase; opacity: 0.75; }
    .stamp span { font-size: 0.86rem; font-weight: 600; }

    .stamp.is-today {
        border-style: solid;
        border-color: var(--kopi-roast);
        background: var(--kopi-espresso);
        color: var(--kopi-paper);
        transform: rotate(-4deg);
    }

    .stamp-arrow {
        align-self: center;
        color: var(--kopi-paper-line);
        font-size: 1.1rem;
        margin: 0 -0.15rem;
    }

    .stamp.is-prediction {
        border-style: solid;
        border-color: var(--kopi-amber);
        background: var(--kopi-amber);
        color: #fff;
        transform: rotate(3deg);
    }

    .lstm-calendar-note {
        font-size: 0.85rem;
        color: #7d6b5a;
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
    }
    .lstm-calendar-note i { color: var(--kopi-amber); margin-top: 0.2rem; }
    .lstm-calendar-note strong { color: var(--kopi-espresso); }

    /* Coffee-cup gauge, the signature element */
    .cup-gauge { display: flex; flex-direction: column; align-items: center; gap: 0.6rem; }
    .cup-gauge svg { width: 92px; height: 100px; }
    .cup-gauge__label {
        font-family: 'IBM Plex Mono', monospace;
        font-size: 0.7rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        text-align: center;
        color: #8a7a68;
    }

    .status-pill {
        font-family: 'IBM Plex Mono', monospace;
        font-weight: 600;
        font-size: 0.72rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 0.4rem 0.85rem;
        border-radius: 30px;
        color: #fff;
        white-space: nowrap;
        display: inline-block;
        margin-top: 0.4rem;
    }
    .status-pill.habis   { background: var(--kopi-brick); }
    .status-pill.kritis  { background: var(--kopi-rust); }
    .status-pill.menipis { background: var(--kopi-gold); }
    .status-pill.aman    { background: var(--kopi-sage); }

    .lstm-facts { margin-top: 1.4rem; padding-top: 1.2rem; border-top: 1px dashed var(--kopi-paper-line); }
    .lstm-facts .fact-row { font-size: 0.92rem; color: #5b4d40; margin-bottom: 0.4rem; }
    .lstm-facts .fact-row strong { color: var(--kopi-espresso); }
    .text-cukup       { color: var(--kopi-sage); font-weight: 700; }
    .text-tidak-cukup { color: var(--kopi-brick); font-weight: 700; }

    .lstm-alert { border-radius: 8px; border: none; padding: 1rem 1.25rem; font-size: 0.92rem; }
    .lstm-alert.alert-success { background: #eaf2ea; color: #2f5e3a; border-left: 4px solid var(--kopi-sage); }
    .lstm-alert.alert-danger  { background: #f8e9e5; color: #7c2a1c; border-left: 4px solid var(--kopi-brick); }
</style>

<div class="lstm-page container-fluid px-4">

    <div class="lstm-header mt-4">
        <span class="lstm-eyebrow">Kedai Kopi Koplak &middot; Prediksi Stok</span>
        <h1 class="lstm-title">Prediksi AI (LSTM)</h1>
        <p class="lstm-subtitle">Model membaca 7 hari riwayat pemakaian untuk menakar kebutuhan bahan baku esok hari.</p>
    </div>

    <div class="lstm-ticket">
        <div class="lstm-ticket__tab">
            <span><i class="fas fa-brain me-2"></i>Kalkulator Prediksi Kebutuhan Bahan Baku</span>
            <i class="fas fa-mug-hot"></i>
        </div>
        <div class="lstm-punch"></div>

        <div class="lstm-ticket__body">

            {{-- Notifikasi sukses / error --}}
            @if (session('sukses') || ($pesanSukses ?? null))
                <div class="lstm-alert alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <span><strong>Prediksi berhasil.</strong> {{ session('sukses') ?? $pesanSukses }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error') || ($pesanError ?? null))
                <div class="lstm-alert alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <span><strong>Terjadi kesalahan.</strong> {{ session('error') ?? $pesanError }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Hasil detail prediksi --}}
            @if (session('detail_prediksi') || ($detailPrediksi ?? null))
                @php
                    $detail = session('detail_prediksi') ?? $detailPrediksi;
                    $step   = $detail['time_step'] ?? 7;
                    $statusLabel = [
                        'habis'   => 'Stok Habis',
                        'kritis'  => 'Kritis',
                        'menipis' => 'Menipis',
                        'aman'    => 'Aman',
                    ][$detail['status_stok']] ?? 'Aman';

                    // Perkiraan visual isi gelas berdasarkan stok vs 2x stok minimal (murni dekoratif)
                    $denomGauge = ($detail['min_stock'] * 2) ?: 1;
                    $isiPersen  = max(6, min(100, round(($detail['stok_sekarang'] / $denomGauge) * 100)));
                @endphp

                <div class="lstm-result">
                    <div class="lstm-result__title">
                        Riwayat {{ $step }} hari terakhir &amp; prediksi &mdash; {{ $detail['bahan'] }}
                    </div>

                    <div class="lstm-result__grid">
                        <div>
                            {{-- Badge riwayat H-(n) s/d H-0 + chip prediksi, gaya kartu stempel --}}
                            <div class="stamp-strip">
                                @foreach ($detail['history'] as $i => $val)
                                    @php $hLabel = ($step - 1 - $i); @endphp
                                    <div class="stamp {{ $i === count($detail['history']) - 1 ? 'is-today' : '' }}">
                                        <small>{{ $hLabel === 0 ? 'Ini' : "H-{$hLabel}" }}</small>
                                        <span>{{ $val }}</span>
                                    </div>
                                @endforeach
                                <span class="stamp-arrow"><i class="fas fa-arrow-right"></i></span>
                                <div class="stamp is-prediction">
                                    <small>Besok</small>
                                    <span>{{ $detail['nilai'] }}</span>
                                </div>
                            </div>

                            {{-- Label kalender besok --}}
                            @if (!empty($detail['label_kalender_besok']))
                                <div class="lstm-calendar-note mb-3">
                                    <i class="fas fa-calendar-day"></i>
                                    <span>
                                        Besok terdeteksi sebagai
                                        <strong>{{ implode(' + ', $detail['label_kalender_besok']) }}</strong>
                                        &mdash; model mempertimbangkan ini dalam prediksinya.
                                    </span>
                                </div>
                            @endif

                            <div class="lstm-facts">
                                <div class="fact-row">
                                    Stok sekarang: <strong>{{ $detail['stok_sekarang'] }} {{ $detail['satuan'] }}</strong>
                                    <span class="text-muted">(minimal: {{ $detail['min_stock'] }} {{ $detail['satuan'] }})</span>
                                </div>
                                <div class="fact-row">
                                    Untuk kebutuhan besok:
                                    @if ($detail['cukup_untuk_besok'])
                                        <span class="text-cukup">Cukup &#10003;</span>
                                    @else
                                        <span class="text-tidak-cukup">
                                            Tidak cukup &mdash; kurang {{ $detail['kekurangan'] }} {{ $detail['satuan'] }}
                                        </span>
                                    @endif
                                </div>
                                @if ($detail['estimasi_hari_habis'] !== null)
                                    <div class="fact-row mb-0">
                                        Estimasi stok habis dalam
                                        <strong>{{ $detail['estimasi_hari_habis'] }} hari</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Gelas kopi sebagai indikator stok --}}
                        <div class="cup-gauge">
                            <svg viewBox="0 0 60 66" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <defs>
                                    <clipPath id="cupClip">
                                        <path d="M8 10 L12 54 Q12 60 18 60 L42 60 Q48 60 48 54 L52 10 Z" />
                                    </clipPath>
                                </defs>
                                <path d="M8 10 L12 54 Q12 60 18 60 L42 60 Q48 60 48 54 L52 10 Z"
                                      fill="#fff" stroke="var(--kopi-paper-line)" stroke-width="2.5" />
                                <rect x="6" y="{{ 60 - ($isiPersen * 0.5) }}" width="48" height="{{ $isiPersen * 0.5 }}"
                                      fill="var(--kopi-{{ $detail['status_stok'] === 'habis' ? 'brick' : ($detail['status_stok'] === 'kritis' ? 'rust' : ($detail['status_stok'] === 'menipis' ? 'gold' : 'sage')) }})"
                                      clip-path="url(#cupClip)" />
                                <path d="M52 16 Q62 16 60 28 Q58 38 48 36" fill="none"
                                      stroke="var(--kopi-paper-line)" stroke-width="3" stroke-linecap="round" />
                                @if ($detail['status_stok'] === 'aman')
                                    <path d="M20 4 Q16 -2 20 -6" stroke="var(--kopi-roast)" stroke-width="1.6" fill="none" stroke-linecap="round" opacity="0.55"/>
                                    <path d="M32 4 Q28 -3 32 -8" stroke="var(--kopi-roast)" stroke-width="1.6" fill="none" stroke-linecap="round" opacity="0.4"/>
                                @endif
                            </svg>
                            <span class="lstm-facts__spacer"></span>
                            <span class="status-pill {{ $detail['status_stok'] }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form pilih bahan --}}
            <form action="{{ route('lstm.hitung') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="bahan_baku" class="lstm-form-label mb-2">Pilih bahan baku</label>
                    <select class="form-select lstm-select" id="bahan_baku" name="bahan_baku" required>
                        <option value="" disabled selected>&mdash; Pilih bahan baku untuk diprediksi &mdash;</option>
                        @foreach ($daftarBahan as $bahan)
                            <option value="{{ $bahan->name }}"
                                {{ (old('bahan_baku') == $bahan->name || ($bahanTerpilih ?? null) == $bahan->name) ? 'selected' : '' }}>
                                {{ $bahan->name }} ({{ $bahan->unit }}) &mdash; stok: {{ $bahan->stock }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text mt-2">
                        Sistem membaca riwayat pemakaian <strong>7 hari terakhir</strong>
                        untuk memprediksi kebutuhan besok.
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn-kopi">
                        <i class="fas fa-calculator me-1"></i> Hitung Prediksi Besok
                    </button>
                    <a href="{{ route('lstm.dashboard-stok') }}" class="btn-kopi-outline">
                        <i class="fas fa-th-large me-1"></i> Lihat Dashboard Semua Stok
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection