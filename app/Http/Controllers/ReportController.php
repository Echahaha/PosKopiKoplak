<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $today = \Carbon\Carbon::today();

        $hasCustomFilter = $request->filled('from')
            || $request->filled('to')
            || $request->filled('search')
            || $request->filled('payment_method');

        $filter = $hasCustomFilter ? 'custom' : $request->get('filter', 'today');

        $selectedDate  = $request->get('date');
        $selectedMonth = $request->get('month');
        $selectedYear  = $request->get('year', $today->year);

        $transactions   = collect();
        $reportData     = collect();
        $activeFilter   = $hasCustomFilter ? null : $filter;
        $totalTransaksi = 0;
        $totalOmzet     = 0;
        $totalHpp       = 0;
        $totalPengeluaran = 0;
        $labaKotor      = 0;
        $labaBersih     = 0;

        if ($filter == 'monthly') {
            $month = $selectedMonth ?? $today->month;

            $reportData = Transaction::whereMonth('created_at', $month)
                ->whereYear('created_at', $selectedYear)
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total_income'),
                    DB::raw('COUNT(*) as total_trx')
                )
                ->groupBy('date')->orderBy('date', 'desc')->get();

            // Hitung HPP per tanggal (join ke transaction_details + products)
            $hppPerTanggal = DB::table('transaction_details')
                ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->join('products', 'products.id', '=', 'transaction_details.product_id')
                ->whereMonth('transactions.created_at', $month)
                ->whereYear('transactions.created_at', $selectedYear)
                ->select(
                    DB::raw('DATE(transactions.created_at) as date'),
                    DB::raw('SUM(transaction_details.quantity * products.purchase_price) as total_hpp')
                )
                ->groupBy('date')
                ->pluck('total_hpp', 'date');

            $pengeluaranPerTanggal = Expense::whereMonth('date', $month)
                ->whereYear('date', $selectedYear)
                ->select(DB::raw('DATE(date) as tgl'), DB::raw('SUM(amount) as total'))
                ->groupBy('tgl')
                ->pluck('total', 'tgl');

            // Tempel hpp & laba bersih ke tiap baris reportData
            $reportData = $reportData->map(function ($row) use ($hppPerTanggal, $pengeluaranPerTanggal) {
                $hpp = $hppPerTanggal[$row->date] ?? 0;
                $pengeluaran = $pengeluaranPerTanggal[$row->date] ?? 0;
                $row->total_hpp = $hpp;
                $row->total_pengeluaran = $pengeluaran;
                $row->laba_bersih = $row->total_income - $hpp - $pengeluaran;
                return $row;
            });

        } elseif ($filter == 'yearly') {
            $reportData = Transaction::whereYear('created_at', $selectedYear)
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_amount) as total_income'),
                    DB::raw('COUNT(*) as total_trx')
                )
                ->groupBy('month')->orderBy('month', 'desc')->get();

            $hppPerBulan = DB::table('transaction_details')
                ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->join('products', 'products.id', '=', 'transaction_details.product_id')
                ->whereYear('transactions.created_at', $selectedYear)
                ->select(
                    DB::raw('MONTH(transactions.created_at) as month'),
                    DB::raw('SUM(transaction_details.quantity * products.purchase_price) as total_hpp')
                )
                ->groupBy('month')
                ->pluck('total_hpp', 'month');

            $pengeluaranPerBulan = Expense::whereYear('date', $selectedYear)
                ->select(DB::raw('MONTH(date) as bln'), DB::raw('SUM(amount) as total'))
                ->groupBy('bln')
                ->pluck('total', 'bln');

            $reportData = $reportData->map(function ($row) use ($hppPerBulan, $pengeluaranPerBulan) {
                $hpp = $hppPerBulan[$row->month] ?? 0;
                $pengeluaran = $pengeluaranPerBulan[$row->month] ?? 0;
                $row->total_hpp = $hpp;
                $row->total_pengeluaran = $pengeluaran;
                $row->laba_bersih = $row->total_income - $hpp - $pengeluaran;
                return $row;
            });

        } else {
            // ── Mode Detail: Hari Ini, Kemarin, Tanggal Spesifik, atau Filter Custom ──
            $query = Transaction::with(['details.product', 'user']);

            if ($hasCustomFilter) {
                if ($request->filled('from')) {
                    $query->whereDate('created_at', '>=', $request->from);
                }
                if ($request->filled('to')) {
                    $query->whereDate('created_at', '<=', $request->to);
                }
                if ($request->filled('search')) {
                    $query->where('invoice_number', 'like', '%' . $request->search . '%');
                }
                if ($request->filled('payment_method')) {
                    $query->where('payment_method', $request->payment_method);
                }
            } elseif ($selectedDate) {
                $query->whereDate('created_at', $selectedDate);
            } elseif ($filter == 'yesterday') {
                $query->whereDate('created_at', \Carbon\Carbon::yesterday());
            } else {
                $query->whereDate('created_at', $today);
            }

            $totalTransaksi = (clone $query)->count();
            $totalOmzet     = (clone $query)->sum('total_amount');

            // HPP: ambil transaction id yang lolos filter, lalu join ke detail+products
            $trxIds = (clone $query)->pluck('id');

            $totalHpp = DB::table('transaction_details')
                ->join('products', 'products.id', '=', 'transaction_details.product_id')
                ->whereIn('transaction_details.transaction_id', $trxIds)
                ->sum(DB::raw('transaction_details.quantity * products.purchase_price'));

            // Pengeluaran: pakai rentang tanggal yang sama dengan filter transaksi
            $expenseQuery = Expense::query();

            if ($hasCustomFilter) {
                if ($request->filled('from')) {
                    $expenseQuery->whereDate('date', '>=', $request->from);
                }
                if ($request->filled('to')) {
                    $expenseQuery->whereDate('date', '<=', $request->to);
                }
            } elseif ($selectedDate) {
                $expenseQuery->whereDate('date', $selectedDate);
            } elseif ($filter == 'yesterday') {
                $expenseQuery->whereDate('date', \Carbon\Carbon::yesterday());
            } else {
                $expenseQuery->whereDate('date', $today);
            }

            $totalPengeluaran = $expenseQuery->sum('amount');

            $labaKotor  = $totalOmzet - $totalHpp;
            $labaBersih = $labaKotor - $totalPengeluaran;

            $transactions = $hasCustomFilter
                ? $query->latest()->paginate(20)->withQueryString()
                : $query->latest()->get();
        }

        return view('reports.index', compact(
            'transactions',
            'reportData',
            'filter',
            'selectedDate',
            'activeFilter',
            'hasCustomFilter',
            'totalTransaksi',
            'totalOmzet',
            'totalHpp',
            'totalPengeluaran',
            'labaKotor',
            'labaBersih'
        ));
    }

    public function exportCSV(Request $request)
    {
        $today = \Carbon\Carbon::today();
        $query = Transaction::with(['details.product']);

        // Terapkan filter yang sama dengan halaman laporan
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $filter = $request->get('filter', 'today');

        if (!$request->filled('from') && !$request->filled('to')) {
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            } elseif ($filter == 'yesterday') {
                $query->whereDate('created_at', \Carbon\Carbon::yesterday());
            } elseif ($filter == 'monthly') {
                $month = $request->get('month', $today->month);
                $year  = $request->get('year', $today->year);
                $query->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            } else {
                $query->whereDate('created_at', $today);
            }
        }

        $transactions = $query->latest()->get();

        $exporter = new \App\Services\CsvExporterService();
        $filename = 'laporan-penjualan-' . now()->format('Y-m-d_His') . '.csv';

        return $exporter->download($transactions, $filename);
    }
}