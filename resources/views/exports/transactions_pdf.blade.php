<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center">LAPORAN TRANSAKSI PENJUALAN</h3>

    <table>
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Total (Rp)</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $trx)
                <tr>
                    <td>{{ $trx->transaction_code }}</td>
                    <td>{{ ucfirst($trx->payment_method) }}</td>
                    <td>{{ ucfirst($trx->status) }}</td>
                    <td>{{ number_format($trx->total_amount, 2, ',', '.') }}</td>
                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-align:right;margin-top:10px">
        Total Penjualan: Rp {{ number_format($total, 2, ',', '.') }}
    </h4>
</body>

</html>
