<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan KOSQU</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0369A1; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #0369A1; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; color: #0f172a; }
        .total-row { font-weight: bold; background-color: #e0f2fe; }
        .text-right { text-align: right; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; color: white; background: #10b981; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan KOSQU</h1>
        <p>Periode: {{ $month ? date('F', mktime(0, 0, 0, $month, 10)) . ' ' : 'Tahun ' }}{{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kamar</th>
                <th>Penghuni</th>
                <th>Bulan Tagihan</th>
                <th>Metode</th>
                <th class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $trx)
            <tr>
                <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}</td>
                <td>Unit {{ $trx->penghuni->kamar->nomor_kamar ?? '-' }}</td>
                <td>{{ $trx->penghuni->nama ?? '-' }}</td>
                <td>{{ $trx->bulan_tagihan }}</td>
                <td>{{ $trx->metode_bayar }}</td>
                <td class="text-right">{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN</td>
                <td class="text-right">{{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Pengelola Kos,</p>
        <br><br><br>
        <p>(______________________)</p>
    </div>
</body>
</html>
