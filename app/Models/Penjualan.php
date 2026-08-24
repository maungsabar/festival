<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    protected $fillable = [
        'gender', 'merchandise_id', 'nama_pembeli', 'hp_pembeli',
        'jumlah', 'harga_satuan', 'total_harga', 'bukti_transfer', 'status', 'struk_token',
    ];

    protected function casts(): array
    {
        return [
            'jumlah'       => 'integer',
            'harga_satuan' => 'integer',
            'total_harga'  => 'integer',
        ];
    }

    public function merchandise(): BelongsTo
    {
        return $this->belongsTo(Merchandise::class);
    }
}
