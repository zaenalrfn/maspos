<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class TransactionExportExcel implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $type = $this->request->get('type');
        $start = $this->request->get('start');
        $end = $this->request->get('end');

        $query = Transaction::query();

        if ($type === 'daily' && $start) {
            $query->whereDate('created_at', $start);
        } elseif ($type === 'range' && $start && $end) {
            $query->whereBetween('created_at', [$start, $end]);
        } elseif ($type === 'monthly' && $start) {
            $date = Carbon::parse($start);
            $query->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);
        } elseif ($type === 'yearly' && $start) {
            $query->whereYear('created_at', $start);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return view('exports.transactions_excel', [
            'transactions' => $transactions,
            'total' => $transactions->sum('total_amount'),
        ]);
    }
}
