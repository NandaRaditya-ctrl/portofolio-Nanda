# Menjalankan seluruh demo DevJourney

Frontend HTML dan demo UMKM berjalan langsung dengan Next.js. Buku tamu, perpustakaan, kasir, inventaris, booking, dan PKLFinder menggunakan backend PHP/Laravel terpisah yang diproksi melalui domain portofolio. Tombol proyek tetap membuka panel yang sama.

## Lokal Windows / Laragon

Prasyarat: checkout `C:/laragon/www/portofolio-v3` dengan Composer dependencies dan hasil `npm run build`, PHP 8.5, MariaDB 12.2, Git, Node.js dan PowerShell 7. Path dapat diubah melalui parameter `-Source`, `-Php`, dan `-MariaBin`.

```powershell
./scripts/start-backend-demo.ps1
npm run dev -- --hostname 127.0.0.1 --port 3107
```

Buka `http://127.0.0.1:3107/journey/index.html`. Script memakai snapshot tracked Git dari checkout v3, bukan `.env` atau database asli. Composer dependencies dipakai melalui junction; aset build publik disalin. `.demo-runtime` diabaikan Git dan ESLint. MariaDB demo hanya mendengar `127.0.0.1:3317`; PHP hanya `127.0.0.1:3118`. Laravel memakai SQLite terpisah. Session, log, data latihan dan kunci aplikasi berada pada runtime ini. Seeder menyiapkan data lowongan contoh dan akun demo.

| Proyek | Email demo | Password demo |
| --- | --- | --- |
| Perpustakaan bulan 5–6 | admin@perpustakaan.com | admin123 |
| Kasir bulan 7 | admin@sembako.com | admin123 |
| Inventaris, booking, PKLFinder | demo@portfolio.test | DemoPortfolio2026! |

Akun tersebut khusus database latihan terisolasi. Petunjuknya juga muncul di atas panel. Gunakan data contoh. Bulan 8 dan 9 memakai aplikasi inventaris yang sama, sesuai rute proyek v3. Bulan 11–12 memakai PKLFinder dan dashboard-nya.

Untuk menghentikan PHP dan MariaDB demo tanpa menghapus data:

```powershell
./scripts/stop-backend-demo.ps1
```

## Publikasi

Integrasi ini belum mempublikasikan backend. Next.js/Vercel tidak mengeksekusi PHP. Untuk demo publik, siapkan hosting PHP/Laravel khusus data fiktif, batasi akses/perubahan sesuai kebutuhan demo, dan set `DEMO_BACKEND_URL` sebelum build Next.js. Jangan memakai akun demo ini pada aplikasi atau database produksi. Proxy backend harus mempercayai hanya proxy hosting yang sesuai; script lokal hanya mempercayai loopback. Endpoint `/api/demo-status` memeriksa `/up`; panel menampilkan pesan jika backend belum aktif.

## Pemeriksaan 14 September 2026

Pengujian browser berhasil membuka tahap 4–11 di panel tanpa tab baru. Pesan contoh buku tamu tersimpan; login admin perpustakaan bulan 5–6, kasir bulan 7, dan inventaris bulan 8 berhasil. Inventaris lanjutan, booking, dan PKLFinder terbuka. Pengujian ini bukan audit seluruh operasi CRUD atau alur transaksi.
