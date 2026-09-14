# Validasi publikasi

## Backend demo — 14 September 2026

- Proyek 4–11 dibuka dalam frame tanpa tab baru; buku tamu berhasil menyimpan pesan contoh.
- Login perpustakaan 5–6, kasir 7, inventaris 8 lolos; tahap 9, booking 10, dan PKLFinder 11 terbuka tanpa halaman error.
- Database latihan MariaDB dan SQLite berada di `.demo-runtime`, terpisah dari data sumber. Akun demo ditampilkan pada panel.
- Aset build inventaris dilengkapi dan proxy loopback dipercaya agar navigasi Laravel kembali melalui portofolio.
- Backend belum dipublikasikan; batas pengujian dan cara menjalankan tersedia di `BACKEND-DEMO.md`.

## Preview proyek di dalam portofolio — 13 September 2026

- Chrome: tombol proyek frontend dan tautan dari dialog detail membuka panel di halaman yang sama tanpa tab baru.
- To-do list dan kalkulator AC (4 × Rp85.000 = Rp340.000) tetap berfungsi di frame.
- Escape dari dalam frame, tombol kembali, dan pengembalian fokus ke kartu proyek lolos.
- Panel diperiksa pada lebar 390px; toolbar kembali tetap terlihat.
- URL preview dibatasi ke tiga demo bisnis dan proyek frontend bulan 1–3 pada origin yang sama. Backend PHP/Laravel tidak diklaim sebagai demo aktif.

## Integrasi DevJourney — 13 September 2026

- Navigasi beranda → Journey → demo UMKM dan tombol kembali dari proyek bulanan lolos pengujian Chrome.
- Pencarian kasir, filter frontend, favorit, modal detail, tampilan grid dan penambahan tugas pada to-do list lolos.
- Seluruh aset lokal yang dimuat selama tes mengembalikan respons sukses; tidak ada exception JavaScript.
- Tampilan lebar 390px tidak memiliki overflow horizontal pada halaman DevJourney.
- Sinkronisasi aset memakai daftar file eksplisit. Backend Laravel, database, dan konfigurasi privat tidak dipindahkan.

## Penambahan demo — 13 September 2026

- `npm run lint`: lolos.
- `npm run build`: lolos kompilasi, TypeScript, dan prerender tiga rute demo.
- Browser Chrome melalui Playwright: kalkulator AC (3 × Rp100.000) dan katering (30 × Rp38.000), filter hunian/komersial, pembuatan ringkasan, serta penghapusan ringkasan lama saat input berubah lolos.
- Rute demo tidak dikenal mengembalikan HTTP 404; tidak ada exception browser pada alur yang diuji.
- Lebar desktop 1440 dan mobile 390 diperiksa; tidak ada overflow horizontal pada tiga demo.
- Formulir adalah simulasi; tidak diuji sebagai layanan pemesanan nyata. Tombol salin menyediakan pesan fallback ketika izin clipboard tidak tersedia.
- Tautan homepage Vercel di metadata GitHub mengembalikan 404 saat diperiksa. Deployment publik belum dilakukan.
- Build memberikan peringatan workspace root karena ada lockfile lain di direktori induk komputer lokal; build tetap berhasil.

## Pemeriksaan publikasi sebelumnya

Tanggal: 10 September 2026.

- TypeScript: tsc --noEmit --incremental false berhasil.
- Build: npm run build -- --webpack berhasil pada Next.js 16.1.6.
- Halaman utama dan halaman not-found berhasil diprerender.
- Pemeriksaan source publikasi tidak menemukan pola private key/token atau file konfigurasi rahasia yang diperiksa.

Belum dilakukan: pengujian interaksi end-to-end atau audit keamanan produksi. Status frontend/prototipe dijelaskan di README.
