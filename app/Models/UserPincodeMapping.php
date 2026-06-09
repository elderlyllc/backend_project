<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPincodeMapping extends Model
{
    use HasFactory;

    protected $table = 'user_pincode_mappings';

    protected $fillable = [
        'user_id',
        'pincode_id',
    ];

    /**
     * Get the user associated with this mapping
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the pincode associated with this mapping
     */
    public function pincode()
    {
        return $this->belongsTo(Pincode::class, 'pincode_id', 'id');
    }
}
