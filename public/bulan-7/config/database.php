<?php
mysqli_report(MYSQLI_REPORT_OFF);

// Konfigurasi Database MySQL untuk bulan-7
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'db_bulan7';
$port = 3306;

// Koneksi ke MySQL (tanpa pilih database dulu)
$conn = @new mysqli($host, $user, $pass, '', $port);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal. Pastikan MySQL di Laragon sedang aktif. Error: " . $conn->connect_error);
}

// Buat database jika belum ada (hanya pada lokal / localhost)
if ($host === '127.0.0.1' || $host === 'localhost') {
    $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
}

// Pilih database
$conn->select_db($dbname);

// Set charset
$conn->set_charset("utf8mb4");

function tableExists($conn, $tableName) {
    $tableName = $conn->real_escape_string($tableName);
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result && $result->num_rows > 0;
}

// Jika tidak di setup.php, cek tabel penting sebagai indikator setup
if (basename($_SERVER['PHP_SELF']) !== 'setup.php') {
    $requiredTables = ['users', 'kategori', 'produk', 'transaksi', 'detail_transaksi'];
    $missingTables = [];

    foreach ($requiredTables as $table) {
        if (!tableExists($conn, $table)) {
            $missingTables[] = $table;
        }
    }

    if (!empty($missingTables)) {
        header("Location: setup.php");
        exit;
    }
}
