<?php
require_once __DIR__.'/../shared/native.php';
/**
 * Setup Database untuk sistem kasir toko sembako
 * Jalankan file ini sekali untuk membuat database, tabel, dan data awal.
 */

$host = 'localhost';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS db_bulan7");
$conn->select_db("db_bulan7");
$conn->set_charset("utf8mb4");

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'kasir') DEFAULT 'kasir',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    nama_produk VARCHAR(200) NOT NULL,
    satuan VARCHAR(50) NOT NULL,
    harga_beli INT NOT NULL DEFAULT 0,
    harga_jual INT NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kasir_id INT NOT NULL,
    total_harga INT NOT NULL DEFAULT 0,
    uang_dibayar INT NOT NULL DEFAULT 0,
    kembalian INT NOT NULL DEFAULT 0,
    catatan VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kasir_id) REFERENCES users(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS detail_transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    produk_id INT NOT NULL,
    qty INT NOT NULL,
    harga_satuan INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
)");

$cekUser = $conn->query("SELECT id FROM users WHERE email = 'admin@sembako.com'");
if ($cekUser->num_rows == 0) {
    $hashPass = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (nama, email, password, role) VALUES ('Pemilik Toko', 'admin@sembako.com', '$hashPass', 'admin')");
}

$cekKategori = $conn->query("SELECT id FROM kategori LIMIT 1");
if ($cekKategori->num_rows == 0) {
    $conn->query("INSERT INTO kategori (nama_kategori) VALUES ('Sembako'), ('Minuman'), ('Bumbu'), ('Mie & Pasta'), ('Snack')");
}

$cekProduk = $conn->query("SELECT id FROM produk LIMIT 1");
if ($cekProduk->num_rows == 0) {
    $conn->query("INSERT INTO produk (kategori_id, nama_produk, satuan, harga_beli, harga_jual, stok) VALUES
        (1, 'Beras Premium 5kg', 'Karung', 65000, 75000, 15),
        (1, 'Minyak Goreng 2L', 'Botol', 28000, 32000, 10),
        (2, 'Teh Celup 25pcs', 'Box', 18000, 22000, 8),
        (3, 'Garam 1kg', 'Sachet', 8000, 12000, 20),
        (4, 'Mie Instan 5pcs', 'Pack', 12000, 15000, 18),
        (5, 'Keripik Singkong', 'Pcs', 5000, 7000, 25)");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #f0fdf4 0%, #ecfeff 100%); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-sm p-4" style="max-width: 420px; width: 100%; border-radius: 1.2rem;">
        <div class="text-center text-success mb-3">
            <i class="fas fa-check-circle fa-3x"></i>
        </div>
        <h2 class="text-center mb-2">Setup Berhasil!</h2>
        <p class="text-center text-muted mb-4">Database, tabel, dan data awal untuk sistem kasir toko sembako telah dibuat.</p>
        <div class="alert alert-success p-3 mb-4">
            <p class="mb-1 text-muted small"><strong>Akun Login:</strong></p>
            <p class="mb-0">Email: <strong>admin@sembako.com</strong></p>
            <p class="mb-0">Password: <strong>admin123</strong></p>
        </div>
        <a href="login.php" class="btn btn-success w-100">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Login
        </a>
    </div>
</body>
</html>
