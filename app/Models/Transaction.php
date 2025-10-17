<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'transaction_code',
        'total_amount',
        'payment_method',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            $transaction->transaction_code = self::generateTransactionCode();
        });
    }

    public static function generateTransactionCode()
    {
        $prefix = 'TRX' . date('Ymd') . '-';
        $lastCode = self::where('transaction_code', 'like', $prefix . '%')
            ->orderByDesc('transaction_code')
            ->value('transaction_code');

        if ($lastCode) {
            $lastNumber = (int) str_replace($prefix, '', $lastCode);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_code', 'transaction_code');
    }
}
