<?php
/**
 * Auth Guard Helper
 * Fungsi-fungsi untuk memproteksi halaman berdasarkan status login dan role.
 */

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['user']['role'] !== 'admin') {
        header("Location: dashboard.php");
        exit;
    }
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}
