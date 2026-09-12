<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Permintaan Website</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="request-body">
    <div class="request-page">
        <div class="request-shell glass-card">
            <div class="request-header">
                <a href="/" class="back-link">← Kembali ke Portofolio</a>
                <h1>Laporan Permintaan Website</h1>
                <p>Daftar permintaan website yang telah masuk, dengan data lengkap untuk tindak lanjut.</p>
            </div>

            <div class="form-section">
                @if($requests->isEmpty())
                    <p>Belum ada permintaan website.</p>
                @else
                    <div class="report-table-wrapper">
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th style="width: 12%">Nama</th>
                                    <th style="width: 10%">WA</th>
                                    <th style="width: 13%">Email</th>
                                    <th style="width: 11%">Website</th>
                                    <th style="width: 15%">Tujuan</th>
                                    <th style="width: 10%">Estimasi</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 10%">Diterima</th>
                                    <th style="width: 9%">Diproses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td>{{ $request->nama }}</td>
                                        <td>{{ $request->wa ?? '-' }}</td>
                                        <td>{{ $request->email ?? '-' }}</td>
                                        <td>{{ $request->nama_website ?? '-' }}</td>
                                        <td>{{ $request->tujuan_website ?? '-' }}</td>
                                        <td>Rp {{ number_format((float) ($request->estimasi_harga ?? 0), 0, ',', '.') }}</td>
                                        <td>{{ $request->status ?? 'Menunggu' }}</td>
                                        <td>{{ $request->estimasi_diterima ?? '-' }}</td>
                                        <td>{{ $request->estimasi_proses ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="detail-section">
                        <h2>Detail Lengkap Klien</h2>
                        @foreach($requests as $request)
                            <div class="detail-card glass-card">
                                <h3>{{ $request->nama }} <span class="status-tag">{{ $request->status ?? 'Menunggu' }}</span></h3>
                                <div class="detail-grid">
                                    <div>
                                        <p><strong>WA:</strong> {{ $request->wa ?? '-' }}</p>
                                        <p><strong>Email:</strong> {{ $request->email ?? '-' }}</p>
                                        <p><strong>Perusahaan:</strong> {{ $request->perusahaan ?? '-' }}</p>
                                        <p><strong>Alamat:</strong> {{ $request->alamat ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Nama Website:</strong> {{ $request->nama_website ?? '-' }}</p>
                                        <p><strong>Tujuan Website:</strong> {{ $request->tujuan_website ?? '-' }}</p>
                                        <p><strong>Budget:</strong> {{ $request->budget ?? '-' }}</p>
                                        <p><strong>Target Selesai:</strong> {{ $request->target_tanggal ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Estimasi Harga:</strong> Rp {{ number_format((float) ($request->estimasi_harga ?? 0), 0, ',', '.') }}</p>
                                        <p><strong>Estimasi Diterima:</strong> {{ $request->estimasi_diterima ?? '-' }}</p>
                                        <p><strong>Estimasi Proses:</strong> {{ $request->estimasi_proses ?? '-' }}</p>
                                        <p><strong>Diterima pada:</strong> {{ $request->diterima_pada ? $request->diterima_pada->format('d M Y H:i') : '-' }}</p>
                                        <p><strong>Diproses pada:</strong> {{ $request->diproses_pada ? $request->diproses_pada->format('d M Y H:i') : '-' }}</p>
                                    </div>
                                </div>

                                <div class="detail-grid">
                                    <div>
                                        <p><strong>Deskripsi Usaha:</strong> {{ $request->deskripsi_usaha ?? '-' }}</p>
                                        <p><strong>Target Pengguna:</strong> {{ $request->target_pengguna ?? '-' }}</p>
                                        <p><strong>Umur Target:</strong> {{ $request->umur_target ?? '-' }}</p>
                                        <p><strong>Wilayah Target:</strong> {{ $request->wilayah_target ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Jenis Website:</strong> {{ $request->jenis_website ? implode(', ', json_decode($request->jenis_website, true)) : '-' }}</p>
                                        <p><strong>Fitur:</strong> {{ $request->fitur ? implode(', ', json_decode($request->fitur, true)) : '-' }}</p>
                                        <p><strong>Halaman:</strong> {{ $request->halaman ? implode(', ', json_decode($request->halaman, true)) : '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Desain & Referensi:</strong> {{ $request->referensi ?? '-' }}</p>
                                        <p><strong>Warna Utama:</strong> {{ $request->warna_utama ?? '-' }}</p>
                                        <p><strong>Warna Kedua:</strong> {{ $request->warna_kedua ?? '-' }}</p>
                                        <p><strong>Font:</strong> {{ $request->font ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="detail-grid">
                                    <div>
                                        <p><strong>Logo Tersedia:</strong> {{ $request->logo_tersedia ?? '-' }}</p>
                                        <p><strong>Teks Tersedia:</strong> {{ $request->teks_tersedia ?? '-' }}</p>
                                        <p><strong>Foto Tersedia:</strong> {{ $request->foto_tersedia ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Domain Tersedia:</strong> {{ $request->domain_tersedia ?? '-' }}</p>
                                        <p><strong>Hosting Tersedia:</strong> {{ $request->hosting_tersedia ?? '-' }}</p>
                                        <p><strong>Catatan:</strong> {{ $request->catatan ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
