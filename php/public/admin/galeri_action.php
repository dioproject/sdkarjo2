<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/galeri.php');
}

$action = $_POST['action'] ?? 'create';
$db = getDB();

if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) {
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
    }
} else {
    $title = $_POST['title'] ?? '';
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadFile($_FILES['image'], 'gallery');
        if ($uploaded) $image = $uploaded;
    }

    if ($title && $image) {
        $id = uuid();
        $stmt = $db->prepare("INSERT INTO gallery (id, title, image) VALUES (?, ?, ?)");
        $stmt->execute([$id, $title, $image]);
    }
}

redirect(BASE_URL . '/admin/galeri.php');
