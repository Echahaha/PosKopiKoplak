<?php

namespace App\Services;

class CsvExporterService
{
    public function download($transactions, $filename)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Tanggal', 'Invoice', 'Metode Pembayaran', 'Nama Produk', 'Jumlah', 'Harga Satuan', 'Subtotal'];

        $callback = function () use ($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                foreach ($trx->details as $detail) {
                    fputcsv($file, [
                        $trx->created_at->format('Y-m-d H:i:s'),
                        $trx->invoice_number,
                        $trx->payment_method,
                        $detail->product->name,
                        $detail->quantity,
                        $detail->price_at_time,
                        $detail->quantity * $detail->price_at_time,
                    ]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
