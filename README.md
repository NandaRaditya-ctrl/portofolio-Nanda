# Nanda — Developer Portfolio

Portofolio Nandadev berbasis Next.js yang menampilkan proyek PHP, Laravel, dan frontend.

**Status:** Portofolio frontend dengan delapan repositori proyek dan tiga demo bisnis interaktif. Demo dapat dijalankan lokal; publikasi source tidak berarti deployment website sudah aktif.

## Demo bisnis UMKM

| Rute | Konsep | Interaksi |
| --- | --- | --- |
| `/demo/sejuk` | Sejuk, jasa servis AC | Pilihan layanan, jumlah unit, estimasi, ringkasan yang bisa disalin |
| `/demo/saji` | Saji & Cerita, katering | Pilihan menu, jumlah porsi, simulasi anggaran dan ringkasan |
| `/demo/ruang` | Ruang Karya, company profile kontraktor | Filter konsep hunian/komersial, validasi brief dan ringkasan |

Semua merek, harga, dan proyek demo fiktif, bukan pekerjaan klien. Formulir hanya membuat ringkasan di browser, tanpa backend, penyimpanan, pembayaran, atau pengiriman ke WhatsApp. Harga bukan penawaran pasar. Tampilan memakai ilustrasi CSS lokal tanpa ketergantungan gambar pihak ketiga.

Kartu demo ada di bagian `/#business-demos`. Data layanan dan interaksi terdapat di `app/demo/[slug]/site.tsx`, gaya demo di `site.module.css`, dan kartu portofolio di `app/components/BusinessDemos.tsx`.

Klik biasa pada demo UMKM dan proyek frontend DevJourney membuka panel preview di halaman portofolio yang sama. Panel mendukung tombol kembali, Escape, dan pengembalian fokus. Demo di dalam panel tetap interaktif. Tanpa JavaScript, tautan tetap membuka halaman proyek langsung. Proyek PHP/Laravel juga dibuka dalam panel ketika backend demo aktif; lihat [BACKEND-DEMO.md](BACKEND-DEMO.md). Implementasi bersama ada di `public/project-preview.js` dan `public/project-preview.css`.

## Fitur dalam source

### Integrasi portofolio-v3 / DevJourney

Menu Journey dan bagian Perjalanan Belajar membuka `/journey/index.html` pada domain yang sama. DevJourney menyediakan tautan kembali ke beranda dan demo UMKM. Roadmap, pencarian, filter teknologi, favorit, detail proyek, tema dan latihan frontend bulan 1–3 diambil dari versi statis portofolio-v3. PHP/Laravel dihubungkan melalui proxy ke lingkungan demo terpisah; lihat [BACKEND-DEMO.md](BACKEND-DEMO.md).

Untuk menyinkronkan ulang dari checkout lokal v3:

```bash
node scripts/sync-journey.mjs C:/laragon/www/portofolio-v3
```

Skrip hanya menyalin daftar aset publik yang ditentukan, melengkapi aset bersama, dan menyesuaikan navigasi. Tidak menyalin `.env`, `.vercel`, database, atau dependency. Snapshot aset tersimpan dalam Git sehingga tidak memerlukan checkout v3 saat deployment. Perubahan v3 berikutnya memerlukan sinkronisasi ulang.

- Profil singkat, keahlian, dan daftar proyek nyata.
- Tautan langsung ke repositori GitHub.
- Antarmuka responsif dengan Framer Motion.

## Menjalankan secara lokal

Memerlukan Node.js 20.9+ dan npm.

```bash
npm ci
npm run dev
```

Buka http://localhost:3000. Untuk build: `npm run build`, lalu `npm start`. Versi dependensi tersedia pada `package.json` dan lockfile.

## Cakupan publikasi

Source dipisahkan dari dependensi, cache, file konfigurasi lokal, database berisi data pengguna, dan unggahan pribadi. Skema SQL yang disertakan hanya berisi struktur tabel. 

## Validasi

Lihat [VALIDATION.md](VALIDATION.md) untuk pemeriksaan yang benar-benar dijalankan dan batasannya.

## Pengembang

[Nandadev](https://github.com/NandaRaditya-ctrl)
