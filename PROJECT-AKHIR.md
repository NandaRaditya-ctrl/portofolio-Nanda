# DevJourney — Portofolio Nanda sampai proyek akhir

Proyek utama: `C:\laragon\www\portofolio-v3`.

## Menjalankan

PHP aktif saat pengujian: 8.5.6. Dependensi terkunci membutuhkan PHP minimal 8.4.1. Gunakan PHP Laragon yang sesuai, bukan versi 8.1. Node.js 24 digunakan untuk build.

```powershell
cd 'C:\laragon\www\portofolio-v3'
php artisan serve --host=127.0.0.1 --port=8013
```

Buka http://127.0.0.1:8013. Server harus tetap berjalan. Jika memakai virtual host Laragon, document root harus menunjuk ke folder `public`.

Database SQLite asli sudah dicadangkan sebelum migrasi ke `storage/app/backups/database-before-journey-20260912.sqlite`. Migrasi menambah tabel, tidak menghapus data lama. Jangan menjalankan `migrate:fresh` pada database kerja.

Pada instalasi baru:

```powershell
composer install
# Salin .env.example menjadi .env hanya jika belum ada, lalu php artisan key:generate.
# Pilih DB_CONNECTION=sqlite dan buat database/database.sqlite jika belum ada.
php artisan migrate
php artisan db:seed --class=JourneySeeder
npm ci
npm run build
php artisan serve --host=127.0.0.1 --port=8013
```

## Proyek bulanan

| Bulan | Proyek | Alamat | Keterangan |
|---|---|---|---|
| 1 | HTML portofolio | `/bulan-1/index.html` | Proyek lama dipertahankan |
| 2 | CSS sekolah | `/bulan-2/index.html` | Proyek lama dipertahankan |
| 3 | JavaScript to-do | `/bulan-3/index.html` | Proyek lama dipertahankan |
| 4 | PHP buku tamu | `/bulan-4/index.php` | Proyek lama dipertahankan |
| 5–6 | Perpustakaan | `/bulan-5/index.php`, `/bulan-6/index.php` | PHP native, membutuhkan MySQL Laragon dan `perpustakaan_db` |
| 7 | Kasir | `/bulan-7/index.php` | PHP native, membutuhkan MySQL Laragon dan `db_bulan7` |
| 8–9 | Inventaris Laravel + Tailwind | `/inventaris` | CRUD kategori/barang, pencarian/filter, kondisi barang, laporan cetak, layout responsif |
| 10 | Booking lapangan | `/booking` | Slot satu jam 08.00–22.00 WIB, harga dari server, pencegahan bentrok dengan unique constraint, pembatalan milik sendiri sebelum mulai |
| 11–12 | PKLFinder | `/pkl`, `/pkl/dashboard` | Pencarian/filter, pagination, lamaran PDF, multi-role, statistik, kelola lowongan/status, ekspor |

Inventaris bulan 8 dan upgrade bulan 9 memakai basis data dan aplikasi yang sama. Halaman yang sebelumnya belum ada sudah dilengkapi, dan CSS Tailwind sudah dibuild.

## Akun dan alur mencoba

Daftar di `/login` untuk akun siswa. Akun lama tetap dapat digunakan. Registrasi publik selalu membuat siswa; nilai role dari form tidak digunakan.

Akun demo lokal tersedia di `storage/app/private/journey-demo-accounts.md`. File ini berada di penyimpanan privat dan tidak ikut Git. Untuk membuat akun demo di instalasi lokal baru, jalankan `php artisan journey:demo`; perintah tidak mengganti kata sandi akun yang sudah ada.

1. Siswa: cari lowongan → lihat detail → unggah PDF maksimal 2 MB + motivasi → cek dashboard.
2. Perusahaan: buka dashboard → buat lowongan → lihat pelamar untuk lowongannya sendiri → unduh CV → terima/tolak. Penerimaan dibatasi kuota.
3. Admin: dapat mengelola semua lowongan/lamaran. Untuk mengganti peran akun terdaftar, gunakan terminal pengelola:

```powershell
php artisan journey:role email-pengguna@example.com company
php artisan journey:role email-pengguna@example.com admin
```

CV disimpan pada disk `local` privat, tidak menggunakan storage link publik. Unduhan diperiksa berdasarkan siswa pemilik, perusahaan pemilik lowongan, atau admin. Ekspor juga dibatasi berdasarkan akun yang sedang masuk.

PDF dihasilkan menggunakan Dompdf. Ekspor Excel menggunakan **SpreadsheetML XML (.xml)** yang dapat dibuka Microsoft Excel, bukan file `.xlsx`; semua sel disimpan sebagai teks agar masukan tidak berubah menjadi formula.

Tiga lapangan dan enam lowongan contoh disiapkan oleh `JourneySeeder`. Lowongan bertanda Demo adalah data latihan, bukan lowongan nyata. Booking adalah sistem reservasi lokal dengan pembayaran di lokasi; tidak terhubung payment gateway.

## Validasi

```powershell
php artisan test
npm run build
composer audit
```

Pengujian mencakup registrasi/login/logout, role tidak dapat dinaikkan lewat form, slot bentrok, harga booking dari server, pembatalan milik sendiri, CV privat, lowongan tutup, validasi PDF, lamaran ganda, kuota penerimaan, pencarian, CRUD lowongan, PDF/Excel, dashboard dan CRUD inventaris. Database pengujian menggunakan SQLite `:memory:` sehingga terpisah dari data kerja.

Proyek disiapkan untuk dijalankan dan dipresentasikan secara lokal. Belum dipublikasikan ke hosting. Modul permintaan website/WhatsApp lama tetap terpisah; konfigurasi email dan layanan eksternal mengikuti pengaturan lama. Modul PHP native bulan 5–7 menggunakan MySQL terpisah dan tidak termasuk pengujian Laravel.

## Maintenance September 2026

Sebelum pembaruan, salinan proyek dibuat di `storage/app/backups/maintenance-20260912-173500`. Jangan hapus folder ini sebelum aplikasi dipakai dan diperiksa kembali.

Seluruh proyek sekarang memiliki panel pindah proyek di bagian bawah halaman. Proyek bulan 1–3 diperbarui agar form latihan tidak mengirim data ke pihak lain, menu sekolah dapat digunakan di ponsel, dan data to-do diperlakukan sebagai teks biasa.

Proyek PHP bulan 4–7 memakai token formulir dan cookie sesi yang lebih aman. Tautan yang mengubah data memerlukan halaman konfirmasi. Peminjaman buku, pengembalian, dan checkout kasir menggunakan transaksi database; harga dan stok dibaca kembali dari database sebelum data disimpan. Dengan begitu, stok tidak berkurang dua kali ketika dua pengguna melakukan tindakan berdekatan.

Laporan permintaan website serta daftar request admin kini hanya dapat dibuka oleh akun Laravel dengan peran `admin`. Kirim formulir request dibatasi lima kali per menit dan memvalidasi email, nomor WhatsApp, tanggal, serta panjang isi.

Validasi maintenance terakhir: `php artisan test` menghasilkan 13 tes lulus dengan 102 pemeriksaan, PHP lint sukses pada 61 berkas PHP proyek lama, `npm run build` berhasil, serta audit Composer dan npm tidak menemukan advisori keamanan.
