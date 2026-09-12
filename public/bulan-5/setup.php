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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="login-wrapper">
        <div class="glass-card login-card" style="text-align: center;">
            <div style="font-size: 3rem; color: var(--success); margin-bottom: 15px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="font-family: var(--font-heading); margin-bottom: 10px;">Setup Berhasil!</h2>
            <p style="color: var(--text-muted); margin-bottom: 20px;">
                Database, tabel, dan data awal telah berhasil dibuat.
            </p>
            <div style="background: rgba(0,0,0,0.2); border-radius: 10px; padding: 15px; margin-bottom: 20px; text-align: left;">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px;"><strong>Akun Admin:</strong></p>
                <p style="font-size: 0.9rem;">Email: <strong>admin@perpustakaan.com</strong></p>
                <p style="font-size: 0.9rem;">Password: <strong>admin123</strong></p>
                <hr style="border-color: var(--card-border); margin: 10px 0;">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 8px;"><strong>Akun Anggota:</strong></p>
                <p style="font-size: 0.9rem;">Email: <strong>budi@email.com</strong></p>
                <p style="font-size: 0.9rem;">Password: <strong>anggota123</strong></p>
            </div>
            <a href="login.php" class="btn btn-primary" style="width: 100%; justify-content: center;">
                <i class="fas fa-sign-in-alt"></i> Masuk ke Login
            </a>
        </div>
    </div>
</body>
</html>
