<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteRequest extends Model
{
    protected $fillable = [
        'nama',
        'perusahaan',
        'wa',
        'email',
        'alamat',
        'nama_website',
        'tujuan_website',
        'deskripsi_usaha',
        'target_pengguna',
        'umur_target',
        'wilayah_target',
        'jenis_website',
        'fitur',
        'halaman',
        'warna_utama',
        'warna_kedua',
        'font',
        'referensi',
        'logo_tersedia',
        'teks_tersedia',
        'foto_tersedia',
        'domain_tersedia',
        'hosting_tersedia',
        'budget',
        'target_tanggal',
        'estimasi_harga',
        'status',
        'estimasi_diterima',
        'estimasi_proses',
        'diterima_pada',
        'diproses_pada',
        'catatan',
        'persetujuan_nama',
        'persetujuan_tanggal',
        'tanda_tangan',
    ];
}
