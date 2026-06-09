<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get full name (optional helper)
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the role associated with the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Get all pincodes associated with this user
     */
    public function pincodes()
    {
        return $this->belongsToMany(
            Pincode::class,
            'user_pincode_mappings',
            'user_id',
            'pincode_id'
        )->withTimestamps();
    }

    /**
     * Get all pincode mappings for this user
     */
    public function pincodeMapping()
    {
        return $this->hasMany(UserPincodeMapping::class, 'user_id', 'id');
    }

    /**
     * Get all customer service pincode assignments for this user
     */
    public function serviceAssignments()
    {
        return $this->hasMany(CustomerServicePincode::class, 'customer_id', 'id');
    }

    /**
     * JWT: Get identifier
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT: Custom claims
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}