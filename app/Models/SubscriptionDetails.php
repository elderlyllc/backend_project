<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionDetails extends Model
{
    use HasFactory;

    protected $table = 'subscription_details';

    protected $fillable = [
        'subscription_id',
        'monthly',
        'yearly',
        'amount',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'monthly' => 'boolean',
        'yearly' => 'boolean',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Get the subscription that owns this detail.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Get the user who created this subscription detail.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
