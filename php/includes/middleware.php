<?php
require_once __DIR__ . '/auth.php';

$currentPage = $_SERVER['REQUEST_URI'] ?? '';
$isLoginPage = str_contains($currentPage, '/admin/login');

if ($isLoginPage) {
    if (isLoggedIn()) {
        redirect(BASE_URL . '/admin/');
    }
} else {
    if (!isLoggedIn()) {
        redirect(BASE_URL . '/admin/login.php');
    }
}
