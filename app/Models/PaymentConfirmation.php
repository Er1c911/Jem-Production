<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_email',
        'total_amount',
        'items',
        'payment_proof_path',
        'status',
        'cancel_reason',
        'accepted_at',
        'accepted_by',
        'cancelled_at',
        'cancelled_by',
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'accepted_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];
}
