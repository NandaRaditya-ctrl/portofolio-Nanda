<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarisKategori extends Model
{
    protected $table = 'inventaris_kategoris';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function barangs(): HasMany
    {
        return $this->hasMany(InventarisBarang::class, 'kategori_id');
    }
}
