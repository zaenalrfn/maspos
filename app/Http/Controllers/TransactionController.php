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
                    'transaction_code' => $transaction->transaction_code,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return redirect()->route('transactions.checkout.success', $transaction);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses transaksi: ' . $th->getMessage());
        }
    }
    public function checkoutSuccess(Transaction $transaction)
    {
        return Inertia::render('Checkout/CheckoutSuccess', [
            'transaction_createdAt' => $transaction->created_at->translatedFormat('d F Y'),
            'total_amount' => $transaction['total_amount'],
        ]);
    }

    public function transactionHistory(Request $request)
    {
        $search = $request->input('search');

        $query = Transaction::orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return Inertia::render('TransactionHistory/Index', [
            'transactions' => $transactions,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function transactionHistoryDetail($transaction_code)
    {
        $transaction = Transaction::with([
            'transactionItems.product' => function ($query) {
                $query->select('id', 'name', 'category_id');
            },
            'transactionItems.product.category' => function ($query) {
                $query->select('id', 'name');
            }
        ])->where('transaction_code', $transaction_code)
            ->firstOrFail();

        return Inertia::render('TransactionHistory/TransactionHistoryDetail', [
            'transaction' => $transaction,
        ]);
    }
}
