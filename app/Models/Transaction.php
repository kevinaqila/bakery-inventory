<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'discount_amount',
        'final_amount',
        'payment_amount',
        'change_amount',
        'payment_method',
        'notes',
    ];

    public function transactionItems(){
        return $this->hasMany(TransactionItem::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
