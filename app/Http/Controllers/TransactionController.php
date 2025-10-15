<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout/Index');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,qris',
            'total_amount' => 'required|numeric|min:1',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Simpan transaksi utama
            $transaction = Transaction::create([
                'payment_method' => $validated['payment_method'],
                'total_amount' => $validated['total_amount'],
                'status' => 'completed',
            ]);

            // Simpan detail item transaksi
            foreach ($validated['items'] as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            // Respon inertia redirect ke halaman sukses
            return redirect()->route('dashboard')->with('success', 'Transaksi berhasil dibuat!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $th->getMessage());
        }
    }
}
