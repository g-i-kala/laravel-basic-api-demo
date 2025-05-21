<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'amount',
        'status',
        'billed_date',
        'paid_date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
