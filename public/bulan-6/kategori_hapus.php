<?php
require_once __DIR__.'/../shared/native.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Cek apakah ada buku yang menggunakan kategori ini
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM buku WHERE kategori_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['total'];

    if ($count > 0) {
        $_SESSION['error'] = "Tidak bisa menghapus kategori karena masih ada $count buku yang terkait.";
    } else {
        $stmt2 = $conn->prepare("DELETE FROM kategori WHERE id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $_SESSION['success'] = "Kategori berhasil dihapus.";
    }
}

header("Location: kategori.php");
exit;
