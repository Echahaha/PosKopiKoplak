@extends('dashboard')

@section('title', 'Pengeluaran')

@section('content')

<style>
.exp-wrap *{box-sizing:border-box}
.exp-wrap{background:#f5f4f0;min-height:100vh;padding:2px 0 32px}

/* ── ALERT (sukses / error) ── */
.exp-alert{display:flex;align-items:center;justify-content:space-between;gap:10px;border-radius:13px;padding:12px 16px;font-size:13px;font-weight:600;margin-bottom:16px;animation:expAlertIn .25s ease-out}
@keyframes expAlertIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.exp-alert-error{background:#fdecea;color:#c0392b;border:1px solid rgba(192,57,43,.25)}
.exp-alert-left{display:flex;align-items:center;gap:8px}
.exp-alert-close{background:none;border:none;cursor:pointer;opacity:.5;font-size:16px;line-height:1;color:inherit;padding:0 0 0 8px;flex-shrink:0}
.exp-alert-close:hover{opacity:1}

/* summary */
.exp-summary{background:#3d7a5e;border-radius:18px;padding:22px 28px;display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;position:relative;overflow:hidden}
.exp-summary::before{content:'';position:absolute;right:-15px;top:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.08)}
.exp-s-label{font-size:11px;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:rgba(255,255,255,.7);margin-bottom:5px}
.exp-s-amount{font-size:26px;font-weight:800;color:#fff;letter-spacing:-.5px;line-height:1}
.exp-s-icon{width:46px;height:46px;background:rgba(255,255,255,.15);border-radius:13px;display:flex;align-items:center;justify-content:center;position:relative;z-index:1;flex-shrink:0}
.exp-s-count{font-size:11.5px;color:rgba(255,255,255,.7);margin-top:6px;font-weight:600}

/* grid */
.exp-grid{display:grid;grid-template-columns:320px 1fr;gap:16px;align-items:start}
@media(max-width:860px){.exp-grid{grid-template-columns:1fr}}

/* cards */
.exp-card{background:#fff;border-radius:20px;border:1px solid rgba(0,0,0,.06);overflow:hidden}
.exp-card-body{padding:26px}
.exp-card-title{font-size:15px;font-weight:700;color:#1c1b18;margin:0 0 20px;letter-spacing:-.2px}

/* form */
.exp-field{margin-bottom:14px}
.exp-label{display:block;font-size:11px;font-weight:700;color:#7a776e;letter-spacing:.6px;text-transform:uppercase;margin-bottom:6px}
.exp-input,.exp-select,.exp-textarea{width:100%;border:1.5px solid #eeede8;border-radius:11px;padding:10px 13px;font-size:13.5px;font-weight:500;color:#1c1b18;background:#faf9f7;outline:none;transition:border-color .15s;-webkit-appearance:none;appearance:none;display:block}
.exp-input:focus,.exp-select:focus,.exp-textarea:focus{border-color:#3d7a5e;background:#fff}
.exp-select{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23a8a59e' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:34px;cursor:pointer}
.exp-textarea{resize:none}

/* State error per-field */
.exp-input.has-error,.exp-select.has-error,.exp-textarea.has-error,.rp-wrap.has-error{border-color:#e74c3c;background:#fdecea}
.exp-error-text{font-size:11px;color:#e74c3c;font-weight:600;margin-top:5px;display:flex;align-items:center;gap:4px}

.rp-wrap{display:flex;align-items:center;border:1.5px solid #eeede8;border-radius:11px;background:#faf9f7;overflow:hidden;transition:border-color .15s}
.rp-wrap:focus-within{border-color:#3d7a5e;background:#fff}
.rp-pfx{padding:10px 11px 10px 13px;font-size:12.5px;font-weight:700;color:#7a776e;border-right:1.5px solid #eeede8;white-space:nowrap;background:transparent}
.rp-wrap input{border:none;background:transparent;padding:10px 13px;font-size:13.5px;font-weight:600;color:#1c1b18;outline:none;width:100%;border-radius:0}
.rp-hint{font-size:11px;color:#9a978d;margin-top:5px;font-weight:500}

.exp-btn-save{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;background:#3d7a5e;color:#fff;border:none;border-radius:12px;padding:13px;font-size:13.5px;font-weight:700;cursor:pointer;margin-top:8px;transition:background .15s,opacity .15s}
.exp-btn-save:hover{background:#326650}
.exp-btn-save:disabled{opacity:.7;cursor:not-allowed;pointer-events:none}
.exp-btn-spinner{display:none;width:13px;height:13px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:expSpin .7s linear infinite}
.exp-btn-save.loading .exp-btn-spinner{display:inline-block}
.exp-btn-save.loading .exp-btn-icon{display:none}
@keyframes expSpin{to{transform:rotate(360deg)}}

/* history */
.exp-hist-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;flex-wrap:wrap;gap:10px}
.exp-hist-meta{font-size:12px;color:#9a978d;font-weight:600;margin-bottom:18px}
.exp-filter-row{display:flex;gap:7px;align-items:center;flex-wrap:wrap}
.exp-fsel{border:1.5px solid #eeede8;border-radius:9px;background:#faf9f7;padding:7px 26px 7px 11px;font-size:12.5px;font-weight:600;color:#1c1b18;-webkit-appearance:none;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%23a8a59e' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 9px center;outline:none;cursor:pointer}
.exp-fbtn{background:#3d7a5e;color:#fff;border:none;border-radius:9px;padding:7px 14px;font-size:12.5px;font-weight:600;cursor:pointer;transition:background .15s,opacity .15s;display:inline-flex;align-items:center;gap:5px}
.exp-fbtn:hover{background:#326650}
.exp-fbtn:disabled{opacity:.7;cursor:not-allowed}
.exp-fbtn-outline{background:#faf9f7;color:#1c1b18;border:1.5px solid #eeede8}
.exp-fbtn-outline:hover{background:#f0efeb}

/* rows */
.exp-row{display:grid;grid-template-columns:46px 1fr auto 34px;align-items:center;gap:13px;padding:13px 8px;border-bottom:1px solid #f5f4f0;border-radius:9px;margin:0 -4px;transition:background .15s}
.exp-row:last-child{border-bottom:none}
.exp-row:hover{background:#faf9f7}
.exp-date{text-align:center;min-width:46px}
.exp-day{font-size:17px;font-weight:800;color:#1c1b18;line-height:1}
.exp-mon{font-size:10.5px;font-weight:600;color:#7a776e;text-transform:uppercase;letter-spacing:.4px;margin-top:1px}
.exp-desc{font-size:13.5px;font-weight:600;color:#1c1b18;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.exp-sub{font-size:11.5px;color:#8a8780;margin-top:2px;display:flex;align-items:center;gap:5px;flex-wrap:wrap}
.exp-badge{display:inline-block;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600;white-space:nowrap}
.b-bahan{background:#e6f4ff;color:#1a6fb5}
.b-ops{background:#fff6e0;color:#b87a0a}
.b-perlkp{background:#e6f7f0;color:#1a7a52}
.b-mkt{background:#f2eaff;color:#7340c8}
.b-lain{background:#f0efeb;color:#5a5750}
.exp-amt{font-size:13.5px;font-weight:700;color:#d94f3b;letter-spacing:-.3px;white-space:nowrap;text-align:right}
.exp-del{width:32px;height:32px;border-radius:8px;border:1.5px solid #eeede8;background:#faf9f7;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#9a978d;transition:all .15s;padding:0;flex-shrink:0}
.exp-del:hover{background:#fdecea;border-color:#f5b8b1;color:#c0392b}

.exp-empty{text-align:center;padding:52px 20px}
.exp-empty-icon{width:56px;height:56px;background:#f5f4f0;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px}
.exp-empty-text{font-size:13.5px;font-weight:600;color:#9a978d;margin:0 0 4px}
.exp-empty-sub{font-size:12px;color:#b3b0a8;margin:0}

/* page header */
.exp-ph-label{font-size:11px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#7a776e;margin-bottom:3px}
.exp-ph-title{font-size:22px;font-weight:800;color:#1c1b18;letter-spacing:-.4px;margin:0 0 20px}

/* ── MODAL KONFIRMASI HAPUS (pengganti confirm() browser native) ── */
.exp-modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2100;align-items:center;justify-content:center;padding:20px}
.exp-modal-backdrop.show{display:flex}
.exp-modal-box{background:#fff;border-radius:18px;padding:24px;max-width:340px;width:100%;text-align:center;animation:expModalIn .2s ease-out}
@keyframes expModalIn{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.exp-modal-icon{width:48px;height:48px;border-radius:50%;background:#fdecea;color:#c0392b;display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.exp-modal-title{font-size:15px;font-weight:700;color:#1c1b18;margin-bottom:6px}
.exp-modal-text{font-size:13px;color:#7a776e;margin-bottom:18px}
.exp-modal-actions{display:flex;gap:10px}
.exp-modal-btn{flex:1;padding:10px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;border:none;transition:background .15s}
.exp-modal-btn-cancel{background:#f5f4f0;color:#1c1b18}
.exp-modal-btn-cancel:hover{background:#eceae4}
.exp-modal-btn-confirm{background:#c0392b;color:#fff}
.exp-modal-btn-confirm:hover{background:#a5301f}
</style>

<div class="exp-wrap">

    {{-- Page Header --}}
    <h1 class="exp-ph-title">Pengeluaran</h1>
    
    {{-- ── ALERT: error --}
    @if($errors->any())
    <div class="exp-alert exp-alert-error" role="alert" aria-live="assertive">
        <span class="exp-alert-left">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $errors->first() }}
        </span>
        <button type="button" class="exp-alert-close" aria-label="Tutup pesan" onclick="this.closest('.exp-alert').remove()">&times;</button>
    </div>
    @endif

    {{-- Summary Strip --}}
    <div class="exp-summary">
        <div>
            <div class="exp-s-label">{{ $showingToday ? 'Total Pengeluaran Hari Ini' : 'Total Pengeluaran Periode Ini' }}</div>
            <div class="exp-s-amount">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            <div class="exp-s-count">{{ $expenses->count() }} transaksi tercatat</div>
        </div>
        <div class="exp-s-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12V7H5a2 2 0 010-4h14v4"/>
                <path d="M3 5v14a2 2 0 002 2h16v-5"/>
                <path d="M18 12a2 2 0 000 4h3v-4h-3z"/>
            </svg>
        </div>
    </div>

    {{-- Grid --}}
    <div class="exp-grid">

        {{-- Form Card --}}
        <div class="exp-card">
            <div class="exp-card-body">
                <div class="exp-card-title">Catat Pengeluaran</div>
                <form action="{{ route('expenses.store') }}" method="POST" id="expenseForm" novalidate>
                    @csrf

                    <div class="exp-field">
                        <label class="exp-label" for="exp-date">Tanggal</label>
                        {{-- old('date') dipakai supaya kalau validasi gagal, tanggal yang
                             sudah diisi tidak hilang -- sebelumnya selalu reset ke hari ini. --}}
                        <input type="date" id="exp-date" name="date"
                               class="exp-input {{ $errors->has('date') ? 'has-error' : '' }}"
                               value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date')
                        <p class="exp-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="exp-field">
                        <label class="exp-label" for="exp-category">Kategori</label>
                        <select id="exp-category" name="category" class="exp-select {{ $errors->has('category') ? 'has-error' : '' }}" required>
                            <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori...</option>
                            <option value="Bahan Baku" {{ old('category') == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku — Kopi, Susu, dll</option>
                            <option value="Operasional" {{ old('category') == 'Operasional' ? 'selected' : '' }}>Operasional — Listrik, Wifi</option>
                            <option value="Perlengkapan" {{ old('category') == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan — Cup, Plastik</option>
                            <option value="Marketing" {{ old('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                        <p class="exp-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="exp-field">
                        <label class="exp-label" for="exp-amount">Nominal</label>
                        <div class="rp-wrap {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <span class="rp-pfx">Rp</span>
                            <input type="text" inputmode="numeric" id="exp-amount" name="amount_display" placeholder="0" autocomplete="off">
                            {{-- Input asli yang dikirim ke server (angka murni tanpa titik),
                                 disinkronkan via JS dari field tampilan di atas. Memisahkan
                                 keduanya supaya user bisa lihat format "50.000" yang mudah
                                 diverifikasi, tapi server tetap terima angka murni. --}}
                            <input type="hidden" name="amount" id="exp-amount-raw" value="{{ old('amount') }}">
                        </div>
                        <p class="rp-hint">Contoh: ketik 50000, otomatis jadi Rp 50.000</p>
                        @error('amount')
                        <p class="exp-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="exp-field">
                        <label class="exp-label" for="exp-description">Keterangan</label>
                        <input type="text" id="exp-description" name="description"
                               class="exp-input {{ $errors->has('description') ? 'has-error' : '' }}"
                               placeholder="Misal: Beli Susu Diamond"
                               value="{{ old('description') }}" required maxlength="255">
                        @error('description')
                        <p class="exp-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="exp-field">
                        <label class="exp-label" for="exp-note">Catatan <span style="font-size:10.5px;font-weight:400;color:#9a978d;text-transform:none;letter-spacing:0">(opsional)</span></label>
                        <textarea id="exp-note" name="note" class="exp-textarea" rows="2" placeholder="Detail tambahan..." maxlength="500">{{ old('note') }}</textarea>
                    </div>

                    <button type="submit" class="exp-btn-save" id="expenseSaveBtn">
                        <span class="exp-btn-spinner" aria-hidden="true"></span>
                        <svg class="exp-btn-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        <span id="expenseSaveBtnText">Simpan Pengeluaran</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- History Card --}}
        <div class="exp-card">
            <div class="exp-card-body">

                <div class="exp-hist-header">
                    <div class="exp-card-title" style="margin-bottom:0">Riwayat</div>
                    <form action="{{ route('expenses.index') }}" method="GET" class="exp-filter-row" id="filterForm">
                        {{-- Ikon diganti dari "tiga titik" (terlihat seperti menu "lainnya")
                             ke ikon "kalender/refresh" yang lebih jelas maksudnya: kembali
                             melihat data hari ini. --}}
                        <a href="{{ route('expenses.index') }}" class="exp-fbtn exp-fbtn-outline" style="text-decoration:none">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                            </svg>
                            Hari Ini
                        </a>
                        <select name="month" class="exp-fsel">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $month == $m ? 'selected' : '' }}>
                                    {{ date('M', mktime(0,0,0,$m,1)) }}
                                </option>
                            @endfor
                        </select>
                        <select name="year" class="exp-fsel">
                            @for ($y = date('Y'); $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="exp-fbtn" id="filterSubmitBtn">Tampilkan</button>
                    </form>
                </div>

                {{-- Indikator periode aktif + jumlah hasil, supaya user tahu persis
                     data apa yang sedang dilihat (sebelumnya tidak ada info ini sama sekali). --}}
                <div class="exp-hist-meta">
                    @if($showingToday)
                        Menampilkan pengeluaran hari ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }}) · {{ $expenses->count() }} item
                    @else
                        Menampilkan periode {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }} · {{ $expenses->count() }} item
                    @endif
                </div>

                <div>
                    @forelse($expenses as $exp)
                    @php
                        $badgeClass = match($exp->category) {
                            'Bahan Baku'   => 'b-bahan',
                            'Operasional'  => 'b-ops',
                            'Perlengkapan' => 'b-perlkp',
                            'Marketing'    => 'b-mkt',
                            default        => 'b-lain',
                        };
                    @endphp
                    <div class="exp-row">
                        <div class="exp-date">
                            <div class="exp-day">{{ \Carbon\Carbon::parse($exp->date)->format('d') }}</div>
                            <div class="exp-mon">{{ \Carbon\Carbon::parse($exp->date)->format('M') }}</div>
                        </div>
                        <div style="min-width:0">
                            <div class="exp-desc">{{ $exp->description }}</div>
                            <div class="exp-sub">
                                <span class="exp-badge {{ $badgeClass }}">{{ $exp->category }}</span>
                                @if($exp->note)<span style="color:#c8c5bc">·</span> {{ $exp->note }}@endif
                            </div>
                        </div>
                        <div class="exp-amt">Rp {{ number_format($exp->amount, 0, ',', '.') }}</div>
                        {{-- Form hapus TIDAK lagi pakai confirm() browser native.
                             Sekarang trigger modal kustom (lihat #expDeleteModal di bawah)
                             via JS, supaya tampilannya konsisten dengan desain aplikasi. --}}
                        <form action="{{ route('expenses.destroy', $exp->id) }}" method="POST"
                              class="exp-delete-form" style="display:contents">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="exp-del" title="Hapus" data-desc="{{ $exp->description }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6"/>
                                    <path d="M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="exp-empty">
                        <div class="exp-empty-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c8c5bc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                                <polyline points="13 2 13 9 20 9"/>
                            </svg>
                        </div>
                        {{-- Pesan empty state sekarang dinamis sesuai periode yang
                             difilter -- sebelumnya selalu bilang "bulan ini" walau
                             user sedang melihat bulan/tahun lain. --}}
                        <p class="exp-empty-text">
                            @if($showingToday)
                                Belum ada pengeluaran hari ini
                            @else
                                Tidak ada pengeluaran di {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
                            @endif
                        </p>
                        <p class="exp-empty-sub">Catat pengeluaran lewat form di sebelah kiri</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>

{{-- ── MODAL KONFIRMASI HAPUS ── --}}
<div class="exp-modal-backdrop" id="expDeleteModal">
    <div class="exp-modal-box">
        <div class="exp-modal-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6"/>
                <path d="M9 6V4h6v2"/>
            </svg>
        </div>
        <div class="exp-modal-title">Hapus pengeluaran ini?</div>
        <div class="exp-modal-text" id="expDeleteModalText">Tindakan ini tidak dapat dibatalkan.</div>
        <div class="exp-modal-actions">
            <button type="button" class="exp-modal-btn exp-modal-btn-cancel" id="expDeleteCancelBtn">Batal</button>
            <button type="button" class="exp-modal-btn exp-modal-btn-confirm" id="expDeleteConfirmBtn">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
(function () {
    // ── Auto-dismiss alert sukses setelah 5 detik ──
    document.querySelectorAll('[data-auto-dismiss="true"]').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .3s ease-out';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 300);
        }, 5000);
    });

    // ── Format nominal otomatis: ketik "50000" -> tampil "50.000" ──
    // Input asli (visible) cuma untuk tampilan; nilai murni dikirim lewat
    // input hidden #exp-amount-raw supaya server tetap terima angka biasa.
    const amountDisplay = document.getElementById('exp-amount');
    const amountRaw      = document.getElementById('exp-amount-raw');

    function formatRupiah(angka) {
        if (!angka) return '';
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function syncAmountFromDisplay() {
        const onlyDigits = amountDisplay.value.replace(/\D/g, '');
        amountRaw.value = onlyDigits;
        amountDisplay.value = formatRupiah(onlyDigits);
    }

    // Isi ulang tampilan dari old('amount') saat halaman reload akibat validasi gagal
    if (amountRaw.value) {
        amountDisplay.value = formatRupiah(amountRaw.value);
    }

    amountDisplay.addEventListener('input', syncAmountFromDisplay);

    // ── Validasi sebelum submit + loading state form Catat Pengeluaran ──
    const expenseForm    = document.getElementById('expenseForm');
    const saveBtn         = document.getElementById('expenseSaveBtn');
    const saveBtnText      = document.getElementById('expenseSaveBtnText');

    expenseForm.addEventListener('submit', function (e) {
        // Pastikan nominal benar-benar terisi & > 0 sebelum kirim
        if (!amountRaw.value || parseInt(amountRaw.value, 10) <= 0) {
            e.preventDefault();
            amountDisplay.closest('.rp-wrap').classList.add('has-error');
            amountDisplay.focus();
            return;
        }

        saveBtn.classList.add('loading');
        saveBtn.disabled = true;
        saveBtnText.textContent = 'Menyimpan...';
    });

    amountDisplay.addEventListener('input', function () {
        if (amountRaw.value && parseInt(amountRaw.value, 10) > 0) {
            amountDisplay.closest('.rp-wrap').classList.remove('has-error');
        }
    });

    // ── Loading state untuk form filter (Tampilkan) ──
    const filterForm = document.getElementById('filterForm');
    const filterBtn   = document.getElementById('filterSubmitBtn');
    filterForm.addEventListener('submit', function () {
        filterBtn.disabled = true;
        filterBtn.textContent = 'Memuat...';
    });

    // ── Modal konfirmasi hapus (pengganti confirm() browser native) ──
    const deleteModal      = document.getElementById('expDeleteModal');
    const deleteModalText  = document.getElementById('expDeleteModalText');
    const deleteCancelBtn  = document.getElementById('expDeleteCancelBtn');
    const deleteConfirmBtn = document.getElementById('expDeleteConfirmBtn');
    let formToDelete        = null;

    document.querySelectorAll('.exp-del').forEach(function (btn) {
        btn.addEventListener('click', function () {
            formToDelete = btn.closest('form');
            const desc = btn.getAttribute('data-desc') || 'pengeluaran ini';
            deleteModalText.textContent = `Catatan "${desc}" akan dihapus permanen dan tidak dapat dikembalikan.`;
            deleteModal.classList.add('show');
        });
    });

    deleteCancelBtn.addEventListener('click', function () {
        deleteModal.classList.remove('show');
        formToDelete = null;
    });

    deleteModal.addEventListener('click', function (e) {
        if (e.target === deleteModal) {
            deleteModal.classList.remove('show');
            formToDelete = null;
        }
    });

    deleteConfirmBtn.addEventListener('click', function () {
        if (formToDelete) {
            deleteConfirmBtn.textContent = 'Menghapus...';
            deleteConfirmBtn.disabled = true;
            formToDelete.submit();
        }
    });
})();
</script>

@endsection