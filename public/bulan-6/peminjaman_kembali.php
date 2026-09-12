<?php
require_once __DIR__.'/../shared/native.php';
require_once 'config/database.php';
require_once 'includes/auth.php';
require_once __DIR__.'/../shared/transactions.php';
requireAdmin();
try {
    $returned = journey_return($conn, (int)($_GET['id'] ?? 0));
    $_SESSION[$returned ? 'success' : 'error'] = $returned ? 'Buku berhasil dikembalikan; stok diperbarui sekali.' : 'Peminjaman tidak ditemukan atau sudah dikembalikan.';
} catch (Throwable $e) { $_SESSION['error'] = 'Pengembalian gagal. Data dan stok tidak berubah.'; }
header('Location: peminjaman.php'); exit;
