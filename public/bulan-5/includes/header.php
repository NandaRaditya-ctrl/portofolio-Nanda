<?php
ob_start();
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital | Bulan 5</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <?php if (isset($_SESSION['user'])): ?>
    <nav>
        <div class="logo">Perpustakaan.</div>
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a>
            <?php if (isAdmin()): ?>
            <a href="kategori.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kategori.php' ? 'active' : ''; ?>"><i class="fas fa-tags"></i> Kategori</a>
            <?php endif; ?>
            <a href="buku.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'buku.php' ? 'active' : ''; ?>"><i class="fas fa-book"></i> Buku</a>
            <?php if (isAdmin()): ?>
            <a href="anggota.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'anggota.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Anggota</a>
            <?php endif; ?>
            <a href="peminjaman.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'peminjaman.php' ? 'active' : ''; ?>"><i class="fas fa-exchange-alt"></i> Peminjaman</a>
            <a href="logout.php" class="nav-link logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="user-badge">
            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user']['nama']); ?> <span class="role-tag"><?php echo $_SESSION['user']['role']; ?></span>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container">
    <?php
    // Tampilkan alert session jika ada
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> ' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
