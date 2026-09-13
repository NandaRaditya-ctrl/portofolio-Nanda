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

## Fitur dalam source

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
