<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftar extends Model
{
    protected $fillable = [
        'nisn','nama','gender','nama_sekolah','alamat_sekolah',
        'nama_pendamping','hp_pendamping','hp_peserta','link_twibbon','nama_tim',
        'id_lomba','file_kartu_siswa','file_bukti_pembayaran','status_verifikasi',
    ];
    protected function casts(): array { return ['created_at'=>'datetime','updated_at'=>'datetime']; }
    public function lomba(): BelongsTo { return $this->belongsTo(Lomba::class,'id_lomba'); }
    public function anggotaTim(): HasMany { return $this->hasMany(AnggotaTim::class,'pendaftar_id')->orderBy('urutan'); }
}
