<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Permintaan Website</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="request-body">
    <div class="request-page">
        <div class="request-shell glass-card">
            <div class="request-header">
                <a href="/" class="back-link">← Kembali</a>
                <h1>Daftar Permintaan Website</h1>
                <p>Semua client yang mengajukan kebutuhan website custom akan tampil di sini.</p>
            </div>

            <div class="form-section">
                @if($requests->isEmpty())
                    <p>Belum ada permintaan website.</p>
                @else
                    <div class="checkbox-grid">
                        @foreach($requests as $request)
                            <div class="glass-card" style="padding:16px;">
                                <h3>{{ $request->nama }}</h3>
                                <p><strong>WA:</strong> {{ $request->wa ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $request->email ?? '-' }}</p>
                                <p><strong>Website:</strong> {{ $request->nama_website ?? '-' }}</p>
                                <p><strong>Tujuan:</strong> {{ $request->tujuan_website ?? '-' }}</p>
                                <p><strong>Estimasi Harga:</strong> Rp {{ number_format((float) ($request->estimasi_harga ?? 0), 0, ',', '.') }}</p>
                                <p><strong>Waktu:</strong> {{ $request->created_at->format('d M Y H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
