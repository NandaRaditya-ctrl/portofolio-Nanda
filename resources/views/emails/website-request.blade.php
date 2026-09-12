<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Website Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
    <h2>Permintaan Website Baru</h2>
    <p>Berikut data yang masuk dari formulir website custom:</p>
    <ul>
        <li><strong>Nama:</strong> {{ $request->nama }}</li>
        <li><strong>Perusahaan:</strong> {{ $request->perusahaan ?? '-' }}</li>
        <li><strong>WhatsApp:</strong> {{ $request->wa ?? '-' }}</li>
        <li><strong>Email:</strong> {{ $request->email ?? '-' }}</li>
        <li><strong>Nama Website:</strong> {{ $request->nama_website ?? '-' }}</li>
        <li><strong>Tujuan Website:</strong> {{ $request->tujuan_website ?? '-' }}</li>
        <li><strong>Estimasi Harga:</strong> Rp {{ number_format((float) ($request->estimasi_harga ?? 0), 0, ',', '.') }}</li>
    </ul>
    <p>Silakan balas ke client sesuai kebutuhan proyek.</p>
</body>
</html>
