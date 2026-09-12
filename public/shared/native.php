<?php
// Shared request protection for the PHP learning projects (months 4–7).
if (PHP_SAPI === 'cli') { return; }
if (!preg_match('~/bulan-([4-7])/~', $_SERVER['SCRIPT_NAME'] ?? '', $match)) { return; }
$nativeMonth = (int) $match[1];
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('journey_month_'.$nativeMonth);
    session_start(['cookie_httponly'=>true,'cookie_samesite'=>'Lax','cookie_path'=>'/bulan-'.$nativeMonth.'/','use_strict_mode'=>true]);
}
date_default_timezone_set('Asia/Jakarta');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store');
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));
function native_csrf_field(): string { return '<input type="hidden" name="_native_token" value="'.htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8').'">'; }
function native_page(string $title, string $message, bool $confirm = false): never {
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.htmlspecialchars($title).'</title><link rel="stylesheet" href="/shared/projects.css"></head><body><main class="native-message"><h1>'.htmlspecialchars($title).'</h1><p>'.htmlspecialchars($message).'</p>';
    if ($confirm) { echo '<form method="post">'.native_csrf_field().'<button type="submit">Lanjutkan tindakan</button></form>'; }
    echo '<a href="index.php">Kembali ke proyek</a></main></body></html>'; exit;
}
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $token = $_POST['_native_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        http_response_code(419); native_page('Form kedaluwarsa', 'Muat ulang halaman formulir, lalu kirim kembali.');
    }
}
$file = basename($_SERVER['SCRIPT_NAME']);
$mutation = preg_match('/(_hapus|_kembali)\.php$/', $file) || in_array($file,['logout.php','setup.php']) || isset($_GET['delete']) || isset($_GET['action']);
if ($file === 'setup.php' && !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1','::1'])) {
    http_response_code(403); native_page('Setup lokal saja', 'Jalankan inisialisasi database dari komputer pengelola.');
}
if ($mutation && ($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    native_page('Konfirmasi tindakan', 'Tindakan ini akan mengubah data atau sesi proyek. Tekan lanjutkan untuk memprosesnya.', true);
}
// Keep server-rendered forms functional without relying on JavaScript for CSRF.
ob_start(function (string $html): string {
    $html = preg_replace_callback('/<form\b[^>]*\bmethod\s*=\s*["\x27]?post\b[^>]*>/i', fn($m) => $m[0].native_csrf_field(), $html);
    if (stripos($html,'</head>') !== false) $html = str_ireplace('</head>', '<link rel="stylesheet" href="/shared/projects.css"></head>', $html);
    if (stripos($html,'</body>') !== false) $html = str_ireplace('</body>', '<script src="/shared/projects.js" defer></script></body>', $html);
    return $html;
});
