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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <div class="login-wrapper">
        <div class="glass-card login-card">
            <div class="login-header">
                <h1><i class="fas fa-book-reader"></i> Perpustakaan</h1>
                <p>Masuk untuk mengakses sistem</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@perpustakaan.com" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="/" style="color: var(--text-muted); text-decoration: none; font-size: 0.85rem;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Roadmap
                </a>
            </div>
        </div>
    </div>
</body>
</html>
