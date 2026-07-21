<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        // Jika ada filter bulan/tahun, gunakan itu. Jika tidak, hanya tampilkan hari ini
        $month = $request->get('month');
        $year = $request->get('year');
        $showingToday = false;

        if ($month && $year) {
            // User memilih bulan/tahun tertentu
            $expenses = Expense::whereMonth('date', $month)
                ->whereYear('date', $year)
                ->latest('date')
                ->get();
        } else {
            // Default: hanya tampilkan hari ini
            $today = date('Y-m-d');
            $expenses = Expense::whereDate('date', $today)
                ->latest('date')
                ->get();

            $month = date('m');
            $year = date('Y');
            $showingToday = true;
        }

        // Hitung total untuk ringkasan di view
        $totalExpense = $expenses->sum('amount');

        return view('expenses.index', compact('expenses', 'totalExpense', 'month', 'year', 'showingToday'));
    }

    public function store(Request $request)
    {
        // Validasi ditambahkan -- sebelumnya tidak ada sama sekali, sehingga
        // field kosong/format salah bisa lolos ke database atau memicu error 500.
        // withErrors() di sini otomatis tersedia di $errors pada view,
        // dan old() akan mengisi ulang field yang sudah benar (lihat view).
        $validated = $request->validate([
            'date'        => ['required', 'date'],
            'category'    => ['required', 'in:Bahan Baku,Operasional,Perlengkapan,Marketing,Lainnya'],
            'amount'      => ['required', 'numeric', 'min:1'],
            'description' => ['required', 'string', 'max:255'],
            'note'        => ['nullable', 'string', 'max:500'],
        ], [
            'date.required'        => 'Tanggal wajib diisi.',
            'category.required'    => 'Kategori wajib dipilih.',
            'amount.required'      => 'Nominal wajib diisi.',
            'amount.numeric'       => 'Nominal harus berupa angka.',
            'amount.min'           => 'Nominal harus lebih dari 0.',
            'description.required' => 'Keterangan wajib diisi.',
        ]);

        Expense::create($validated);

        return back()->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return back()->with('success', 'Catatan pengeluaran berhasil dihapus!');
    }
}