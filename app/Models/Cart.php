<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    public $timestamps = false;

    protected $fillable = [
        'cardnumber',
        'createddate',
        'isactive',
        'is_default',
        'createdBy',
        'subscription_id',
        'subscription_value',
    ];

    protected $casts = [
        'createddate' => 'datetime',
        'isactive' => 'boolean',
        'is_default' => 'boolean',
        'subscription_value' => 'decimal:2',
    ];

    /**
     * Get the cart details for this cart.
     */
    public function details(): HasMany
    {
        return $this->hasMany(CartDetails::class, 'cart_id');
    }

    /**
     * Get the user who created this cart.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    /**
     * Get the subscription linked to this cart.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}