<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\StockStatusService;

class StockOpnameController extends Controller
{
    /**
     * Daftar semua sesi stok opname (riwayat).
     */
    public function index()
    {
        $opnames = StockOpname::with('user')
            ->withCount('details')
            ->latest('tanggal_opname')
            ->paginate(15);

        return view('stock-opname.index', compact('opnames'));
    }

    /**
     * Buat sesi opname baru (status: draft).
     * Otomatis snapshot semua bahan baku (is_menu = 0) beserta stok sistem saat ini.
     */
    public function store(Request $request)
    {
        $ingredients = Product::where('is_menu', false)->get();

        if ($ingredients->isEmpty()) {
            return back()->with('error', 'Belum ada bahan baku yang bisa di-opname.');
        }

        $opname = null;

        DB::transaction(function () use ($request, $ingredients, &$opname) {
            $opname = StockOpname::create([
                'kode_opname'     => 'OPN-' . strtoupper(uniqid()),
                'tanggal_opname'  => now()->toDateString(),
                'user_id'         => Auth::id(),
                'status'          => 'draft',
                'catatan'         => $request->catatan,
            ]);

            foreach ($ingredients as $item) {
                StockOpnameDetail::create([
                    'stock_opname_id' => $opname->id,
                    'product_id'      => $item->id,
                    'stok_sistem'     => $item->stock,
                    'stok_fisik'      => null,
                    'selisih'         => null,
                ]);
            }
        });

        return redirect()->route('stock-opname.show', $opname->id)
            ->with('success', 'Sesi stok opname baru berhasil dibuat. Silakan isi stok fisik di bawah.');
    }

    /**
     * Form pengisian stok fisik untuk satu sesi opname.
     */
    public function show($id)
    {
        $opname = StockOpname::with(['details.product', 'user'])->findOrFail($id);

        return view('stock-opname.show', compact('opname'));
    }

    /**
     * Simpan input stok fisik (bisa disimpan sebagian dulu, status tetap draft).
     */
    public function updateDetails(Request $request, $id)
    {
        $opname = StockOpname::findOrFail($id);

        if ($opname->status === 'selesai') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Sesi opname ini sudah selesai dan tidak bisa diubah lagi.'], 422);
            }
            return back()->with('error', 'Sesi opname ini sudah selesai dan tidak bisa diubah lagi.');
        }

        $request->validate([
            'stok_fisik'   => 'required|array',
            'stok_fisik.*' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $opname) {
            foreach ($request->stok_fisik as $detailId => $fisik) {
                if ($fisik === null || $fisik === '') {
                    continue;
                }

                $detail = StockOpnameDetail::where('stock_opname_id', $opname->id)->findOrFail($detailId);
                $selisih = (int) $fisik - $detail->stok_sistem;

                $detail->update([
                    'stok_fisik' => $fisik,
                    'selisih'    => $selisih,
                    'keterangan' => $request->keterangan[$detailId] ?? $detail->keterangan,
                ]);
            }
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data stok fisik berhasil disimpan.']);
        }

        return back()->with('success', 'Data stok fisik berhasil disimpan.');
    }

    /**
     * Selesaikan sesi opname: kunci data, update stok produk ke stok fisik,
     * dan catat StockLog untuk setiap penyesuaian yang ada selisihnya.
     */
    public function finish($id)
    {
        $opname = StockOpname::with('details')->findOrFail($id);

        if ($opname->status === 'selesai') {
            return back()->with('error', 'Sesi opname ini sudah pernah diselesaikan.');
        }

        $belumDiisi = $opname->details->whereNull('stok_fisik')->count();
        if ($belumDiisi > 0) {
            return back()->with('error', 'Masih ada ' . $belumDiisi . ' bahan yang belum diisi stok fisiknya.');
        }

        DB::transaction(function () use ($opname) {
            foreach ($opname->details as $detail) {
                if ($detail->selisih != 0) {
                    $product = Product::find($detail->product_id);
                    if ($product) {
                        $product->update(['stock' => $detail->stok_fisik]);

                        StockLog::create([
                            'product_id' => $product->id,
                            'type'       => $detail->selisih > 0 ? 'in' : 'out',
                            'amount'     => abs($detail->selisih),
                            'reason'     => 'Penyesuaian Stok Opname (' . $opname->kode_opname . '): '
                                . ($detail->selisih > 0 ? '+' : '') . $detail->selisih . ' ' . $product->unit,
                        ]);
                    }
                }
            }

            $opname->update(['status' => 'selesai']);
        });

        app(StockStatusService::class)->clearCache();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('stock-opname.index')->with('success', 'Opname selesai');
    }

    /**
     * Hapus sesi opname yang masih draft (belum mempengaruhi stok).
     */
    public function destroy($id)
    {
        $opname = StockOpname::findOrFail($id);

        if ($opname->status === 'selesai') {
            return back()->with('error', 'Sesi opname yang sudah selesai tidak bisa dihapus (sudah mempengaruhi stok & riwayat).');
        }

        // Hapus detail terkait terlebih dahulu untuk menghindari orphan records
        $opname->details()->delete();
        $opname->delete();

        return redirect()->route('stock-opname.index')->with('success', 'Sesi opname draft berhasil dihapus.');
    }
}
