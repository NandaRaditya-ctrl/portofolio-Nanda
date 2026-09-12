<?php
require_once __DIR__.'/../shared/native.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
}

session_destroy();

header('Location: login.php');
exit;
