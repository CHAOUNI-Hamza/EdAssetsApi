<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the user that owns the phone.
     */
    /*public function user()
    {
        return $this->hasMany(User::class);
    }*/
}
