<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\TransactionExportExcel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'daily');
        $start = $request->get('start');
        $end = $request->get('end');

        $query = $this->filterQuery($type, $start, $end);
        $transactions = $query->orderBy('created_at', 'desc')->get();

        $summary = [
            'total_transactions' => $transactions->count(),
            'total_amount' => $transactions->sum('total_amount'),
        ];

        return Inertia::render('Reports/Index', [
            'transactions' => $transactions,
            'summary' => $summary,
            'filters' => [
                'type' => $type,
                'start' => $start,
                'end' => $end,
            ]
        ]);
    }

    public function exportPdf(Request $request)
    {
        $transactions = $this->filterQuery(
            $request->get('type'),
            $request->get('start'),
            $request->get('end')
        )->get();

        $pdf = PDF::loadView('exports.transactions_pdf', [
            'transactions' => $transactions,
            'total' => $transactions->sum('total_amount'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('report-transactions.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new TransactionExportExcel($request), 'report-transactions.xlsx');
    }

    private function filterQuery($type, $start, $end)
    {
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

        return $query;
    }
}
