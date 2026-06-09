<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerServicePincode extends Model
{
    use HasFactory;

    protected $table = 'customer_service_pincodes';

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'pincode_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the customer associated with this service assignment
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    /**
     * Get the subscription associated with this assignment
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    /**
     * Get the pincode associated with this assignment
     */
    public function pincode()
    {
        return $this->belongsTo(Pincode::class, 'pincode_id', 'id');
    }

    /**
     * Check if service is currently active
     */
    public function isActive()
    {
        return now()->greaterThanOrEqualTo($this->start_date) 
            && (is_null($this->end_date) || now()->lessThanOrEqualTo($this->end_date));
    }
}
