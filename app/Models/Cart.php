<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'cardnumber',
        'createddate',
        'isactive',
        'is_default',
        'createdBy',
        'subscription_id',
        'subscription_value',

    ];

    // protected $casts = [
    //     'created_at' => 'datetime',
    // ];

    /**
     * Get the cartdetails for this cart.
     */
    public function details(): HasMany
    {
        return $this->hasMany(CartDetails::class, 'cart_id');
    }
}