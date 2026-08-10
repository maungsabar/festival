<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username', 'password', 'role'];

    protected $hidden = ['password'];

    // Laravel 11: casts sebagai method (bukan property)
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // auto-hash saat set
        ];
    }
}
