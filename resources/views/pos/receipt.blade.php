<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Belanja - Kopi Koplak</title>
    <style>
        @page { size: 58mm 200mm; margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 58mm; 
            margin: 0; 
            padding: 5mm; 
            font-size: 12px;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        .total { font-weight: bold; font-size: 14px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <strong>KOPI KOPLAK</strong><br>
        Rest Area Banjaratma 260B, Brebes<br>
        ---------------------------
    </div>

    <div style="margin-top: 5px;">
        No: {{ $transaction->invoice_number }}<br>
        Tgl: {{ $transaction->created_at->format('d/m/Y H:i') }}<br>
        Kasir: {{ $transaction->user->name ?? 'Kasir' }}<br>
        Bayar: {{ $transaction->payment_method }}
    </div>

    <div class="divider"></div>

    <table>
        @foreach($transaction->details as $detail)
        <tr>
            <td colspan="2">{{ $detail->product->name }}</td>
        </tr>
        @if(!empty($detail->addons) && count($detail->addons) > 0)
        <tr>
            <td colspan="2" style="font-size:10px; padding-left:6px;">
                + {{ implode(', ', $detail->addons) }}
            </td>
        </tr>
        @endif
        <tr>
            <td>{{ $detail->quantity }} x {{ number_format($detail->price_at_time + ($detail->addon_total ?? 0), 0, ',', '.') }}</td>
            <td align="right">{{ number_format($detail->quantity * ($detail->price_at_time + ($detail->addon_total ?? 0)), 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    <table>
        <tr class="total">
            <td>TOTAL</td>
            <td align="right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        @if($transaction->payment_method === 'Cash')
        <tr>
            <td>Bayar</td>
            <td align="right">Rp {{ number_format($transaction->pay_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td align="right">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 10px;">
        Terima Kasih!<br>
        Enjoy Your Coffee
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.close()" style="padding: 5px 15px; cursor: pointer;">Tutup Halaman</button>
    </div>
</body>
</html>