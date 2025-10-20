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
    <tfoot>
        <tr>
            <th colspan="3">Total</th>
            <th colspan="2">Rp {{ number_format($total, 2, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
