<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran KOSQU</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0088A8;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #0088A8;
            margin: 0;
            font-size: 28px;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .invoice-details {
            width: 100%;
            margin-bottom: 40px;
        }
        .invoice-details td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-details .label {
            font-weight: bold;
            color: #555;
            width: 150px;
        }
        .stamp {
            position: absolute;
            top: 250px;
            right: 50px;
            font-size: 40px;
            color: rgba(16, 185, 129, 0.3);
            border: 5px solid rgba(16, 185, 129, 0.3);
            border-radius: 10px;
            padding: 10px 20px;
            transform: rotate(-15deg);
            font-weight: bold;
            letter-spacing: 5px;
        }
        .amount-box {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 20px;
            text-align: right;
            border-radius: 8px;
            margin-top: 30px;
        }
        .amount-box h3 {
            margin: 0 0 10px 0;
            color: #64748B;
            font-size: 16px;
        }
        .amount-box .total {
            font-size: 24px;
            font-weight: bold;
            color: #0088A8;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #94A3B8;
            border-top: 1px solid #E2E8F0;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>KOSQU</h1>
        <p>Wisma AAM Management System</p>
        <p>Jl. Contoh Alamat Kos No. 123, Kota Anda</p>
    </div>

    <div class="stamp">LUNAS</div>

    <h2 style="text-align: center; color: #2D3E50; margin-bottom: 30px;">KWITANSI PEMBAYARAN</h2>

    <table class="invoice-details">
        <tr>
            <td class="label">No. Transaksi</td>
            <td>: #TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Bayar</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->tgl_bayar)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Metode Bayar</td>
            <td>: {{ $transaksi->metode_bayar }}</td>
        </tr>
        <tr>
            <td colspan="2"><hr style="border-top: 1px dashed #ccc; margin: 15px 0;"></td>
        </tr>
        <tr>
            <td class="label">Diterima Dari</td>
            <td>: <strong>{{ $transaksi->penghuni->nama }}</strong></td>
        </tr>
        <tr>
            <td class="label">Nomor Kamar</td>
            <td>: Unit {{ $transaksi->penghuni->kamar->nomor_kamar ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Pembayaran Untuk</td>
            <td>: Sewa Kamar Bulan <strong>{{ $transaksi->bulan_tagihan }}</strong></td>
        </tr>
    </table>

    <div class="amount-box">
        <h3>TOTAL PEMBAYARAN</h3>
        <div class="total">Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</div>
    </div>

    <div class="footer">
        <p>Kwitansi ini sah dan diterbitkan secara otomatis oleh Sistem Manajemen KOSQU.</p>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}</p>
    </div>

</body>
</html>
