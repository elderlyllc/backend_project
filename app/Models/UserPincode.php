<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPincode extends Model
{
    use HasFactory;

    protected $table = 'user_pincodes';

    protected $fillable = [
        'user_id',
        'pincode',
    ];

    /**
     * Get the user associated with this pincode
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
