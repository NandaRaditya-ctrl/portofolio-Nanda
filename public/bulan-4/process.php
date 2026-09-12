<?php
require_once __DIR__.'/../shared/native.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// Validasi request method harus POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $pesan = trim($_POST["pesan"]);
    $errors = [];

    // Validasi Nama
    if (empty($nama)) {
        $errors[] = "Nama tidak boleh kosong.";
    }

    // Validasi Email
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Validasi Pesan
    if (empty($pesan)) {
        $errors[] = "Pesan tidak boleh kosong.";
    }

    // Jika ada error, kembali dengan pesan error
    if (count($errors) > 0) {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: index.php");
        exit;
    }

    // Jika tidak ada error, simpan data ke Session
    if (!isset($_SESSION['guestbook'])) {
        $_SESSION['guestbook'] = [];
    }

    // Tambahkan data baru ke awal array (terbaru di atas)
    array_unshift($_SESSION['guestbook'], [
        'nama' => htmlspecialchars($nama),
        'email' => htmlspecialchars($email),
        'pesan' => htmlspecialchars($pesan),
        'waktu' => date('d M Y, H:i')
    ]);

    // Sukses, redirect dengan pesan sukses
    $_SESSION['success'] = "Pesan Anda berhasil dikirim!";
    header("Location: index.php");
    exit;
} else {
    // Jika diakses langsung tanpa POST, kembalikan ke index
    header("Location: index.php");
    exit;
}
