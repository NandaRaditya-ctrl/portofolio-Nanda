<?php
require_once __DIR__.'/../shared/native.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';
requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM buku WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $_SESSION['success'] = "Buku berhasil dihapus.";
}

header("Location: buku.php");
exit;
