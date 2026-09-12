<?php
// Konfigurasi Database MySQL
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'perpustakaan_db';

// Koneksi ke MySQL (tanpa pilih database dulu)
$conn = new mysqli($host, $user, $pass);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Buat database jika belum ada
$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");

// Pilih database
$conn->select_db($dbname);

// Set charset
$conn->set_charset("utf8mb4");

// Cek apakah tabel users sudah ada. Jika belum dan kita tidak berada di setup.php, redirect ke setup.php
if (basename($_SERVER['PHP_SELF']) !== 'setup.php') {
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows == 0) {
        header("Location: setup.php");
        exit;
    }
}
