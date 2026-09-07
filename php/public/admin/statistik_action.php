<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/statistik.php');
}

$action = $_POST['action'] ?? 'upsert';
$db = getDB();

if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) {
        $stmt = $db->prepare("DELETE FROM stats WHERE id = ?");
        $stmt->execute([$id]);
    }
} else {
    $id = $_POST['id'] ?? '';
    $label = $_POST['label'] ?? '';
    $value = $_POST['value'] ?? '';
    $icon = $_POST['icon'] ?? 'UsersRound';

    if ($id && $label && $value) {
        $stmt = $db->prepare("INSERT OR REPLACE INTO stats (id, label, value, icon) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $label, $value, $icon]);
    }
}

redirect(BASE_URL . '/admin/statistik.php');
