<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchandise extends Model
{
    protected $fillable = ['gender', 'nama', 'deskripsi', 'harga', 'gambar', 'stok', 'aktif'];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'harga' => 'integer',
            'stok'  => 'integer',
        ];
    }
}
