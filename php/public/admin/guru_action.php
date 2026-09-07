<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/guru.php');
}

$action = $_POST['action'] ?? 'create';
$db = getDB();

if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) {
        $stmt = $db->prepare("DELETE FROM teachers WHERE id = ?");
        $stmt->execute([$id]);
    }
} else {
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $photo = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadFile($_FILES['photo'], 'teachers');
        if ($uploaded) $photo = $uploaded;
    }

    if ($name && $role) {
        $id = uuid();
        $stmt = $db->prepare("INSERT INTO teachers (id, name, role, photo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $name, $role, $photo]);
    }
}

redirect(BASE_URL . '/admin/guru.php');
