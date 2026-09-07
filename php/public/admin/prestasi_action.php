<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/prestasi.php');
}

$action = $_POST['action'] ?? 'create';
$db = getDB();

if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) {
        $stmt = $db->prepare("DELETE FROM achievements WHERE id = ?");
        $stmt->execute([$id]);
    }
} else {
    $title = $_POST['title'] ?? '';
    if ($title) {
        $id = uuid();
        $stmt = $db->prepare("INSERT INTO achievements (id, title) VALUES (?, ?)");
        $stmt->execute([$id, $title]);
    }
}

redirect(BASE_URL . '/admin/prestasi.php');
