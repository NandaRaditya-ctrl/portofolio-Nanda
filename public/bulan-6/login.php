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
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
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
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Perpustakaan Digital</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .login-card {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm p-4 login-card">
        <div class="text-center mb-4">
            <h1 class="h3 mb-2 text-primary"><i class="fas fa-book-reader"></i> Perpustakaan</h1>
            <p class="text-muted">Masuk untuk mengakses sistem</p>
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
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="/" class="text-decoration-none text-muted small">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Roadmap
            </a>
        </div>
    </div>
</body>
</html>
