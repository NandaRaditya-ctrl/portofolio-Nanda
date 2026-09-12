<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarisBarang extends Model
{
    protected $table = 'inventaris_barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'jumlah',
        'kondisi',
        'lokasi',
        'tanggal_masuk',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(InventarisKategori::class, 'kategori_id');
    }

    /**
     * Get human-readable condition label.
     */
    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->kondisi,
        };
    }

    /**
     * Get condition badge CSS class for Tailwind.
     */
    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
            'rusak_ringan' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            'rusak_berat' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Auto-generate kode_barang based on kategori and current count.
     */
    public static function generateKode(int $kategoriId): string
    {
        $kategori = InventarisKategori::find($kategoriId);
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $kategori->nama_kategori ?? 'BRG'), 0, 3));
        return $prefix . '-' . strtoupper((string) \Illuminate\Support\Str::ulid());
    }
}
