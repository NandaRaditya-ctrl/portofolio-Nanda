<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kebutuhan Website | DevJourney</title>
    <meta name="description" content="Form permintaan pembuatan website custom untuk client.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
</head>
<body class="request-body">
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <div class="request-page">
        <div class="request-shell glass-card">
            <div class="request-header">
                <a href="/" class="back-link">← Kembali ke Portofolio</a>
                <h1>FORM KEBUTUHAN WEBSITE</h1>
                <p>Isi formulir berikut untuk mengajukan website custom sesuai kebutuhan bisnis atau proyek Anda.</p>

                <div class="free-option-banner">
                    <div class="free-option-badge">Gratis + Email Only</div>
                    <p>Untuk kebutuhan sederhana, Anda bisa mengirim permintaan tanpa biaya tambahan. Kami akan menindaklanjuti melalui email terlebih dahulu.</p>
                </div>

                <div class="price-card">
                    <h3>Estimasi Harga</h3>
                    <p id="priceValue">Rp 0</p>
                    <small>Perkiraan berdasarkan jenis website, fitur, dan halaman yang dipilih.</small>
                </div>

                <div class="pricing-table-wrapper">
                    <table class="pricing-table">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Harga Mulai</th>
                                <th>Fokus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Basic</td>
                                <td>Rp 2.000.000</td>
                                <td>Company Profile / Landing Page</td>
                            </tr>
                            <tr>
                                <td>Business</td>
                                <td>Rp 5.000.000</td>
                                <td>Portofolio / Sekolah / Blog</td>
                            </tr>
                            <tr>
                                <td>Professional</td>
                                <td>Rp 8.000.000</td>
                                <td>Toko Online / Sistem Informasi</td>
                            </tr>
                            <tr>
                                <td>Enterprise</td>
                                <td>Rp 10.000.000+</td>
                                <td>Custom fitur, dashboard, multi role</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if (session('success'))
                <div class="success-box">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('request-website.store') }}" method="POST">
                @csrf

                <section class="form-section">
                    <h2>A. Informasi Klien</h2>
                    <div class="form-grid">
                        <label>Nama<input type="text" name="nama" required></label>
                        <label>Nama Perusahaan/Usaha<input type="text" name="perusahaan"></label>
                        <label>Nomor WhatsApp<input type="text" name="wa" required></label>
                        <label>Email<input type="email" name="email"></label>
                        <label class="full">Alamat<textarea name="alamat" rows="3"></textarea></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>B. Informasi Website</h2>
                    <div class="form-grid">
                        <label>Nama Website<input type="text" name="nama_website"></label>
                        <label>Tujuan Website<input type="text" name="tujuan_website"></label>
                        <label class="full">Deskripsi singkat usaha/organisasi<textarea name="deskripsi_usaha" rows="3"></textarea></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>C. Target Pengguna</h2>
                    <div class="form-grid">
                        <label class="full">Siapa target pengguna website?<textarea name="target_pengguna" rows="2"></textarea></label>
                        <label>Umur target pengguna<input type="text" name="umur_target"></label>
                        <label>Wilayah target pengguna<input type="text" name="wilayah_target"></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>D. Jenis Website</h2>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="jenis_website[]" value="Company Profile"> Company Profile</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Toko Online"> Toko Online (E-Commerce)</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Landing Page"> Landing Page</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Sekolah"> Sekolah</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Portofolio"> Portofolio</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Blog/Berita"> Blog/Berita</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Sistem Informasi"> Sistem Informasi</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Booking/Reservasi"> Booking/Reservasi</label>
                        <label><input type="checkbox" name="jenis_website[]" value="Lainnya"> Lainnya <input type="text" name="jenis_lainnya" class="inline-input"></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>E. Fitur yang Dibutuhkan</h2>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="fitur[]" value="Login"> Login</label>
                        <label><input type="checkbox" name="fitur[]" value="Register"> Register</label>
                        <label><input type="checkbox" name="fitur[]" value="Dashboard"> Dashboard</label>
                        <label><input type="checkbox" name="fitur[]" value="Profil Pengguna"> Profil Pengguna</label>
                        <label><input type="checkbox" name="fitur[]" value="Chat"> Chat</label>
                        <label><input type="checkbox" name="fitur[]" value="Pembayaran Online"> Pembayaran Online</label>
                        <label><input type="checkbox" name="fitur[]" value="Keranjang Belanja"> Keranjang Belanja</label>
                        <label><input type="checkbox" name="fitur[]" value="Pencarian"> Pencarian</label>
                        <label><input type="checkbox" name="fitur[]" value="Upload File"> Upload File</label>
                        <label><input type="checkbox" name="fitur[]" value="Notifikasi"> Notifikasi</label>
                        <label><input type="checkbox" name="fitur[]" value="Multi Role"> Multi Role</label>
                        <label><input type="checkbox" name="fitur[]" value="Lainnya"> Lainnya <input type="text" name="fitur_lainnya" class="inline-input"></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>F. Halaman yang Diinginkan</h2>
                    <div class="checkbox-grid">
                        <label><input type="checkbox" name="halaman[]" value="Home"> Home</label>
                        <label><input type="checkbox" name="halaman[]" value="Tentang Kami"> Tentang Kami</label>
                        <label><input type="checkbox" name="halaman[]" value="Layanan"> Layanan</label>
                        <label><input type="checkbox" name="halaman[]" value="Produk"> Produk</label>
                        <label><input type="checkbox" name="halaman[]" value="Blog"> Blog</label>
                        <label><input type="checkbox" name="halaman[]" value="Galeri"> Galeri</label>
                        <label><input type="checkbox" name="halaman[]" value="FAQ"> FAQ</label>
                        <label><input type="checkbox" name="halaman[]" value="Kontak"> Kontak</label>
                        <label><input type="checkbox" name="halaman[]" value="Dashboard"> Dashboard</label>
                        <label><input type="checkbox" name="halaman[]" value="Admin Panel"> Admin Panel</label>
                        <label><input type="checkbox" name="halaman[]" value="Lainnya"> Lainnya <input type="text" name="halaman_lainnya" class="inline-input"></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>G. Desain</h2>
                    <div class="form-grid">
                        <label>Warna utama<input type="text" name="warna_utama"></label>
                        <label>Warna kedua<input type="text" name="warna_kedua"></label>
                        <label>Font yang diinginkan<input type="text" name="font"></label>
                        <label>Website referensi<input type="text" name="referensi"></label>
                        <label class="full">Logo sudah tersedia?<br>
                            <select name="logo_tersedia">
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>H. Konten</h2>
                    <div class="form-grid">
                        <label class="full">Apakah teks sudah tersedia?<br>
                            <select name="teks_tersedia">
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </label>
                        <label class="full">Apakah foto/gambar sudah tersedia?<br>
                            <select name="foto_tersedia">
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>I. Domain & Hosting</h2>
                    <div class="form-grid">
                        <label class="full">Sudah memiliki domain?<br>
                            <select name="domain_tersedia">
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </label>
                        <label class="full">Sudah memiliki hosting?<br>
                            <select name="hosting_tersedia">
                                <option value="Ya">Ya</option>
                                <option value="Tidak">Tidak</option>
                            </select>
                        </label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>J. Anggaran</h2>
                    <div class="form-grid">
                        <label>Estimasi budget<input type="text" name="budget"></label>
                        <label>Target tanggal selesai<input type="text" name="target_tanggal"></label>
                    </div>
                </section>

                <section class="form-section">
                    <h2>K. Catatan Tambahan</h2>
                    <label class="full"><textarea name="catatan" rows="5"></textarea></label>
                </section>

                <section class="form-section">
                    <h2>L. Kontak Persetujuan</h2>
                    <div class="form-grid">
                        <label>Nama<input type="text" name="persetujuan_nama"></label>
                        <label>Tanggal<input type="date" name="persetujuan_tanggal"></label>
                        <label class="full">Tanda Tangan<textarea name="tanda_tangan" rows="3" placeholder="Tuliskan nama Anda sebagai tanda tangan digital"></textarea></label>
                    </div>
                </section>

                <input type="hidden" name="estimasi_harga" id="estimasiHarga" value="0">

                <div class="submit-row">
                    <button type="submit">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const priceValue = document.getElementById('priceValue');
        const estimasiHarga = document.getElementById('estimasiHarga');
        const checkboxes = Array.from(document.querySelectorAll('input[type="checkbox"]'));
        const priceBase = {
            'Company Profile': 3000000,
            'Toko Online': 8000000,
            'Landing Page': 2500000,
            'Sekolah': 5000000,
            'Portofolio': 2000000,
            'Blog/Berita': 3500000,
            'Sistem Informasi': 10000000,
            'Booking/Reservasi': 7000000,
        };
        const featurePrices = {
            'Login': 500000,
            'Register': 300000,
            'Dashboard': 700000,
            'Profil Pengguna': 400000,
            'Chat': 600000,
            'Pembayaran Online': 1500000,
            'Keranjang Belanja': 1000000,
            'Pencarian': 300000,
            'Upload File': 400000,
            'Notifikasi': 300000,
            'Multi Role': 800000,
        };
        const pagePrices = {
            'Home': 200000,
            'Tentang Kami': 150000,
            'Layanan': 150000,
            'Produk': 180000,
            'Blog': 180000,
            'Galeri': 150000,
            'FAQ': 100000,
            'Kontak': 120000,
            'Dashboard': 250000,
            'Admin Panel': 300000,
        };

        const calculatePrice = () => {
            let total = 1500000;
            const selectedTypes = document.querySelectorAll('input[name="jenis_website[]"]:checked');
            selectedTypes.forEach(item => {
                const value = item.value;
                if (value === 'Toko Online') total += priceBase['Toko Online'];
                else if (priceBase[value]) total += priceBase[value];
            });

            document.querySelectorAll('input[name="fitur[]"]:checked').forEach(item => {
                total += featurePrices[item.value] || 0;
            });

            document.querySelectorAll('input[name="halaman[]"]:checked').forEach(item => {
                total += pagePrices[item.value] || 0;
            });

            const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(total);
            priceValue.textContent = formatted;
            estimasiHarga.value = total;
        };

        checkboxes.forEach(checkbox => checkbox.addEventListener('change', calculatePrice));
        calculatePrice();
    </script>
</body>
</html>
