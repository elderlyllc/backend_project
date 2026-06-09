<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    use HasFactory;

    protected $table = 'pincodes';

    protected $fillable = [
        'pincode',
        'city',
        'state',
        'region',
    ];

    /**
     * Get all users mapped to this pincode
     */
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'user_pincode_mappings',
            'pincode_id',
            'user_id'
        )->withTimestamps();
    }
}
