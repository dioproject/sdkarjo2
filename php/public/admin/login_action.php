<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/login.php');
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$db = getDB();
$stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || hashPassword($password) !== $user['password']) {
    redirect(BASE_URL . '/admin/login.php?error=1');
}

$token = jwt_encode(['userId' => $user['id'], 'email' => $user['email']]);
setAuthCookie($token);

redirect(BASE_URL . '/admin/');
