<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'elderly_roles';

    protected $fillable = [
        'name',
        'created_by',
    ];

    public $timestamps = true;

    /**
     * Get all users with this role
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
