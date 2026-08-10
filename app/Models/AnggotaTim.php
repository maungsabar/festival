<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaTim extends Model
{
    protected $table = 'anggota_tim';
    protected $fillable = ['pendaftar_id','urutan','nisn','nama','kelas'];
    public function pendaftar(): BelongsTo { return $this->belongsTo(Pendaftar::class,'pendaftar_id'); }
}
