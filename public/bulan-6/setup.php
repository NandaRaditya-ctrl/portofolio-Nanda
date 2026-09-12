<?php
require_once __DIR__.'/../shared/native.php';
/**
 * Setup Database
 * Jalankan file ini sekali untuk membuat database, tabel, dan data awal.
 */

$host = 'localhost';
$user = 'root';
$pass = '';

// Koneksi tanpa database dulu
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 1. Buat Database
$conn->query("CREATE DATABASE IF NOT EXISTS perpustakaan_db");
$conn->select_db("perpustakaan_db");
$conn->set_charset("utf8mb4");

// 2. Buat Tabel users
$conn->query("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'anggota') DEFAULT 'anggota',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// 3. Buat Tabel kategori
$conn->query("
    CREATE TABLE IF NOT EXISTS kategori (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_kategori VARCHAR(100) NOT NULL
    )
");

// 4. Buat Tabel buku (FK ke kategori - one-to-many)
$conn->query("
    CREATE TABLE IF NOT EXISTS buku (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kategori_id INT NOT NULL,
        judul VARCHAR(200) NOT NULL,
        pengarang VARCHAR(100) NOT NULL,
        penerbit VARCHAR(100) NOT NULL,
        tahun INT NOT NULL,
        stok INT DEFAULT 1,
        FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE CASCADE
    )
");

// 5. Buat Tabel peminjaman (FK ke users - one-to-many)
$conn->query("
    CREATE TABLE IF NOT EXISTS peminjaman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        tgl_pinjam DATE NOT NULL,
        tgl_kembali DATE DEFAULT NULL,
        status ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// 6. Buat Tabel Penghubung: detail_peminjaman (FK ke peminjaman & buku)
$conn->query("
    CREATE TABLE IF NOT EXISTS detail_peminjaman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        peminjaman_id INT NOT NULL,
        buku_id INT NOT NULL,
        FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
        FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
    )
");

// 7. Seed Data Admin
$cekAdmin = $conn->query("SELECT id FROM users WHERE email = 'admin@perpustakaan.com'");
if ($cekAdmin->num_rows == 0) {
    $hashPass = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (nama, email, password, role) VALUES ('Administrator', 'admin@perpustakaan.com', '$hashPass', 'admin')");
}

// 8. Seed Kategori
$cekKategori = $conn->query("SELECT id FROM kategori LIMIT 1");
if ($cekKategori->num_rows == 0) {
    $conn->query("INSERT INTO kategori (nama_kategori) VALUES ('Novel'), ('Sains'), ('Sejarah'), ('Teknologi'), ('Fiksi')");
}

// 9. Seed Buku
$cekBuku = $conn->query("SELECT id FROM buku LIMIT 1");
if ($cekBuku->num_rows == 0) {
    $conn->query("INSERT INTO buku (kategori_id, judul, pengarang, penerbit, tahun, stok) VALUES
        (1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 5),
        (1, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 3),
        (2, 'A Brief History of Time', 'Stephen Hawking', 'Bantam Dell', 1988, 2),
        (3, 'Sejarah Indonesia Modern', 'M.C. Ricklefs', 'Serambi', 2008, 4),
        (4, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 3),
        (5, 'Harry Potter and the Philosopher Stone', 'J.K. Rowling', 'Bloomsbury', 1997, 6)
    ");
}

// 10. Seed Anggota
$cekAnggota = $conn->query("SELECT id FROM users WHERE role = 'anggota' LIMIT 1");
if ($cekAnggota->num_rows == 0) {
    $hashPass = password_hash('anggota123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO users (nama, email, password, role) VALUES
        ('Budi Santoso', 'budi@email.com', '$hashPass', 'anggota'),
        ('Siti Aminah', 'siti@email.com', '$hashPass', 'anggota')
    ");
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center text-success mb-3">
            <i class="fas fa-check-circle fa-3x"></i>
        </div>
        <h2 class="text-center mb-2">Setup Berhasil!</h2>
        <p class="text-center text-muted mb-4">
            Database, tabel, dan data awal telah berhasil dibuat.
        </p>
        <div class="alert alert-secondary p-3 mb-4">
            <p class="mb-1 text-muted small"><strong>Akun Admin:</strong></p>
            <p class="mb-0">Email: <strong>admin@perpustakaan.com</strong></p>
            <p class="mb-2">Password: <strong>admin123</strong></p>
            <hr>
            <p class="mb-1 text-muted small"><strong>Akun Anggota:</strong></p>
            <p class="mb-0">Email: <strong>budi@email.com</strong></p>
            <p class="mb-0">Password: <strong>anggota123</strong></p>
        </div>
        <a href="login.php" class="btn btn-primary w-100">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Login
        </a>
    </div>
</body>
</html>
