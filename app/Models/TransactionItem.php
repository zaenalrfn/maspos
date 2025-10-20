<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_code', 'transaction_code');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
