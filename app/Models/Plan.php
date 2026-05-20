<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'amount',
        'currency',
        'billing_interval',
        'trial_days',
        'is_active',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'trial_days' => 'integer',
            'is_active' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
