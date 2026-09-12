<?php
require_once __DIR__.'/../shared/native.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

// Proses login (POST)
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'config/database.php';

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Login berhasil
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'nama' => $user['nama'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ];
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $error = "Email atau kata sandi tidak cocok.";
                }
            } else {
                $error = "Email atau kata sandi tidak cocok.";
            }
            $stmt->close();
        } else {
            $error = "Tidak bisa memproses login saat ini.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Kasir Sembako</title>
    <!-- Bootstrap 5 CSS -->
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
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #f7fdf8 0%, #ecfdf5 100%);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-page {
            padding: 2rem 1rem;
        }

        .login-card {
            max-width: 460px;
            width: 100%;
            border-radius: 1.4rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(22,163,74,0.12);
        }

        .login-card .form-control {
            border-radius: 0.95rem;
            padding: 0.9rem 1rem;
            border: 1px solid rgba(15,23,42,0.12);
            background: #fdfefe;
            transition: all 0.2s ease;
            box-shadow: inset 0 1px 2px rgba(15,23,42,0.04);
        }

        .login-card .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(22,163,74,0.16), inset 0 1px 2px rgba(15,23,42,0.05);
            background: #ffffff;
        }

        .login-card .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
        }

        .login-card .btn {
            border-radius: 0.9rem;
            padding: 0.8rem 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-2) 100%);
            border: 0;
        }

        .login-card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        .brand-badge {
            background: linear-gradient(135deg, rgba(22,163,74,0.12), rgba(5,150,105,0.18));
            color: var(--primary);
        }

        .mini-pill {
            background: rgba(22, 163, 74, 0.08);
            color: var(--primary);
            border: 1px solid rgba(22,163,74,0.12);
        }
    </style>
</head>
<body class="login-page d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4 p-md-5 login-card border-0">
        <div class="text-center mb-4">
            <div class="rounded-circle brand-badge p-3 d-inline-flex mb-3">
                <i class="fas fa-store fa-lg"></i>
            </div>
            <h1 class="h3 mb-2" style="color: var(--primary);">Kasir Sembako</h1>
            <p class="text-muted mb-2">Masuk untuk mengelola stok, penjualan, dan transaksi harian</p>
            <span class="badge mini-pill px-3 py-2"><i class="fas fa-bolt me-1"></i> Mode penjualan aktif</span>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div><?php echo $error; ?></div>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@perpustakaan.com" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Sistem
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="/" class="text-decoration-none text-muted small">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke halaman utama
            </a>
        </div>
    </div>
</body>
</html>
