<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('perusahaan')->nullable();
            $table->string('wa')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_website')->nullable();
            $table->string('tujuan_website')->nullable();
            $table->text('deskripsi_usaha')->nullable();
            $table->text('target_pengguna')->nullable();
            $table->string('umur_target')->nullable();
            $table->string('wilayah_target')->nullable();
            $table->text('jenis_website')->nullable();
            $table->text('fitur')->nullable();
            $table->text('halaman')->nullable();
            $table->string('warna_utama')->nullable();
            $table->string('warna_kedua')->nullable();
            $table->string('font')->nullable();
            $table->string('referensi')->nullable();
            $table->string('logo_tersedia')->nullable();
            $table->string('teks_tersedia')->nullable();
            $table->string('foto_tersedia')->nullable();
            $table->string('domain_tersedia')->nullable();
            $table->string('hosting_tersedia')->nullable();
            $table->string('budget')->nullable();
            $table->string('target_tanggal')->nullable();
            $table->string('estimasi_harga')->nullable();
            $table->text('catatan')->nullable();
            $table->string('persetujuan_nama')->nullable();
            $table->string('persetujuan_tanggal')->nullable();
            $table->text('tanda_tangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_requests');
    }
};
