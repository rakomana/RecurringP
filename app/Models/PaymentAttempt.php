<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'payment_id',
        'invoice_id',
        'provider',
        'provider_attempt_id',
        'status',
        'amount',
        'currency',
        'failure_reason',
        'attempted_at',
        'response_payload',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'attempted_at' => 'datetime',
            'response_payload' => 'array',
        ];
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
