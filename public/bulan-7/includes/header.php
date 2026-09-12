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
    <title>Kasir Sembako | Bulan 7</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #16a34a;
            --primary-2: #15803d;
            --surface: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
        }

        body {
            background: linear-gradient(135deg, #f7fdf8 0%, #ecfdf5 100%);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            transition: background 0.3s ease, color 0.3s ease;
        }

        body.dark-mode {
            background: linear-gradient(135deg, #07110b 0%, #112017 100%);
            color: #e5f7eb;
        }

        body.dark-mode .page-shell,
        body.dark-mode .card,
        body.dark-mode .kpi-card,
        body.dark-mode .product-card,
        body.dark-mode .cart-panel,
        body.dark-mode .hero-panel,
        body.dark-mode .table-card {
            background: rgba(12, 25, 18, 0.95) !important;
            color: #e5f7eb;
            border-color: rgba(255,255,255,0.08) !important;
        }

        body.dark-mode .text-muted,
        body.dark-mode .text-secondary {
            color: #a7b8ac !important;
        }

        body.dark-mode .table-light {
            background-color: rgba(255,255,255,0.06);
            color: #e5f7eb;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.18);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        .navbar .nav-link {
            color: rgba(255,255,255,0.92) !important;
            font-weight: 500;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link.active {
            color: #ffffff !important;
        }

        .card {
            border-radius: 1.1rem;
        }

        .page-intro,
        .stat-card {
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
        }

        .page-intro {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.08), rgba(5, 150, 105, 0.12));
            border: 1px solid rgba(22, 163, 74, 0.12);
        }

        .stat-card--produk {
            background: linear-gradient(135deg, #15803d 0%, #22c55e 100%);
            color: #ffffff;
        }

        .stat-card--stok {
            background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);
            color: #ffffff;
        }

        .stat-card--transaksi {
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
            color: #ffffff;
        }

        .stat-card--pendapatan {
            background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);
            color: #ffffff;
        }

        .login-page {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfeff 100%);
        }

        .login-card {
            max-width: 430px;
            width: 100%;
            border-radius: 1.4rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
        }

        .login-card .form-control {
            border-radius: 0.9rem;
            padding: 0.8rem 1rem;
        }

        .login-card .btn {
            border-radius: 0.9rem;
            padding: 0.8rem 1rem;
            font-weight: 600;
        }

        .page-shell {
            background: rgba(255,255,255,0.72);
            border: 1px solid rgba(15,23,42,0.06);
            border-radius: 1.2rem;
            padding: 1.25rem;
            backdrop-filter: blur(10px);
        }

        .hero-panel {
            background: linear-gradient(135deg, rgba(22,163,74,0.12), rgba(6,95,70,0.14));
            border: 1px solid rgba(22,163,74,0.14);
            border-radius: 1.2rem;
        }

        .kpi-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15,23,42,0.08);
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .product-card {
            border: 0;
            border-radius: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 28px rgba(15,23,42,0.06);
        }

        .product-card:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 16px 40px rgba(15,23,42,0.14);
        }

        .theme-toggle {
            border: 0;
            background: rgba(255,255,255,0.18);
            color: white;
            border-radius: 999px;
            padding: 0.45rem 0.8rem;
        }

        .checkout-success {
            animation: popIn 0.35s ease;
        }

        @keyframes popIn {
            0% { transform: scale(0.96); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }

        .floating-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        .cart-panel {
            position: sticky;
            top: 1rem;
            border-radius: 1.1rem;
            box-shadow: 0 16px 40px rgba(15,23,42,0.08);
        }

        .table-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15,23,42,0.06);
        }

        body.dark-mode .form-control,
        body.dark-mode .form-select,
        body.dark-mode .input-group-text,
        body.dark-mode .dropdown-menu {
            background-color: rgba(8, 17, 13, 0.95);
            color: #e5f7eb;
            border-color: rgba(255,255,255,0.12);
        }

        body.dark-mode .dropdown-item {
            color: #e5f7eb;
        }

        body.dark-mode .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        body.dark-mode .form-control::placeholder {
            color: #9fb4a5;
        }

        body.dark-mode .list-group-item {
            background-color: transparent;
            border-color: rgba(255,255,255,0.08);
        }

        body.dark-mode .alert-light {
            background-color: rgba(255,255,255,0.06);
            color: #e5f7eb;
            border-color: rgba(255,255,255,0.08);
        }

        body.dark-mode .alert-success {
            background-color: rgba(22, 163, 74, 0.15);
            color: #4ade80;
            border-color: rgba(22, 163, 74, 0.25);
        }

        body.dark-mode .alert-danger {
            background-color: rgba(220, 38, 38, 0.15);
            color: #f87171;
            border-color: rgba(220, 38, 38, 0.25);
        }

        body.dark-mode .alert .btn-close {
            filter: invert(1) grayscale(1) brightness(2);
        }

        body.dark-mode .btn-outline-secondary {
            color: #e5f7eb;
            border-color: rgba(255,255,255,0.16);
        }

        body.dark-mode .btn-outline-secondary:hover {
            background-color: rgba(255,255,255,0.12);
            color: #ffffff;
        }

        @media (max-width: 991.98px) {
            .cart-panel {
                position: static;
            }
        }

        @media (max-width: 767.8px) {
            .page-shell {
                padding: 1rem;
            }

            .hero-panel .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .w-auto {
                width: 100% !important;
                max-width: 100% !important;
            }

            .navbar-brand {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .cart-panel .card-body {
                padding: 1rem !important;
            }

            .btn-group-sm .btn {
                padding: 0.35rem 0.5rem;
                font-size: 0.85rem;
            }
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: var(--primary);
            color: #ffffff;
        }
        .nav-pills .nav-link {
            color: var(--primary);
            border: 1px solid var(--primary);
            background-color: transparent;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link:hover {
            background-color: rgba(22, 163, 74, 0.05);
            color: var(--primary-2);
        }
        body.dark-mode .nav-pills .nav-link {
            color: #4ade80;
            border-color: rgba(74, 222, 128, 0.3);
        }
        body.dark-mode .nav-pills .nav-link:hover {
            background-color: rgba(74, 222, 128, 0.08);
            color: #4ade80;
        }
        body.dark-mode .nav-pills .nav-link.active {
            background-color: var(--primary);
            color: #ffffff;
            border-color: var(--primary);
        }
    </style>
</head>
<body class="page-body">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('bulan7-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.body.classList.add('dark-mode');
            }
        })();
    </script>
    <?php if (isset($_SESSION['user'])): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="fas fa-store me-2"></i>Toko Sembako</a>
            
            <div class="d-flex align-items-center gap-2 d-lg-none">
                <button class="theme-toggle" type="button"><i class="fas fa-moon"></i></button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'produk.php' ? 'active' : ''; ?>" href="produk.php"><i class="fas fa-boxes"></i> Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'transaksi.php' ? 'active' : ''; ?>" href="transaksi.php"><i class="fas fa-cash-register"></i> Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : ''; ?>" href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item me-2 d-none d-lg-block">
                        <button class="theme-toggle" type="button"><i class="fas fa-moon"></i></button>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user']['nama']); ?> 
                            <span class="badge bg-light text-primary ms-1"><?php echo $_SESSION['user']['role']; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="container pb-5">
    <?php
    // Tampilkan alert session jika ada
    if (isset($_SESSION['success'])) {
        if (isset($_SESSION['success_transaksi'])) {
            $transaksiId = $_SESSION['success_transaksi'];
            unset($_SESSION['success_transaksi']);
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div><i class="fas fa-check-circle me-2"></i>' . $_SESSION['success'] . '</div>
                        <a href="cetak_struk.php?id=' . $transaksiId . '" target="_blank" class="btn btn-sm btn-success text-white px-3"><i class="fas fa-print me-1"></i> Cetak Struk</a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        } else {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><i class="fas fa-check-circle me-2"></i>' . $_SESSION['success'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        }
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['error'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        unset($_SESSION['error']);
    }
    ?>
