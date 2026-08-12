<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lomba extends Model
{
    protected $fillable = ['nama_lomba','gender','jenjang','aktif','tipe','min_anggota','max_anggota','kuota','pemenang','tampil_pemenang','gambar','file_guidebook'];
    protected function casts(): array {
        return ['aktif'=>'boolean','tampil_pemenang'=>'boolean','kuota'=>'integer','min_anggota'=>'integer','max_anggota'=>'integer'];
    }

    public function pendaftars(): HasMany { return $this->hasMany(Pendaftar::class,'id_lomba'); }

    /**
     * Fallback untuk `withCount('pendaftars')`.
     * Jika attribute `pendaftars_count` tidak ada di model, hitung langsung dari relasi.
     */
    public function getPendaftarsCountAttribute(): int
    {
        if (array_key_exists('pendaftars_count', $this->attributes)) {
            return (int) $this->attributes['pendaftars_count'];
        }

        return $this->pendaftars()->count();
    }

    public function isFull(): bool {
        if (is_null($this->kuota) || $this->kuota <= 0) return false;
        // PERF: Use eager-loaded pendaftars_count if available (via withCount())
        $count = array_key_exists('pendaftars_count', $this->attributes)
            ? (int) $this->attributes['pendaftars_count']
            : $this->pendaftars()->count();
        return $count >= $this->kuota;
    }

    public function sisaKuota(): ?int {
        if (is_null($this->kuota) || $this->kuota <= 0) return null;
        // PERF: Use eager-loaded pendaftars_count if available (via withCount())
        $count = array_key_exists('pendaftars_count', $this->attributes)
            ? (int) $this->attributes['pendaftars_count']
            : $this->pendaftars()->count();
        return max(0, $this->kuota - $count);
    }
}

