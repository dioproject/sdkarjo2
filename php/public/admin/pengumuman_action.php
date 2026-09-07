<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/pengumuman.php');
}

$action = $_POST['action'] ?? 'create';
$db = getDB();

if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id) {
        $stmt = $db->prepare("DELETE FROM announcements WHERE id = ?");
        $stmt->execute([$id]);
    }
} else {
    $title = $_POST['title'] ?? '';
    $excerpt = $_POST['excerpt'] ?? '';
    $category = $_POST['category'] ?? 'Informasi';
    $content = $_POST['content'] ?? '{}';
    $isPublished = isset($_POST['isPublished']) ? 1 : 0;

    if ($title) {
        $id = uuid();
        $slug = slugify($title);

        // Check for duplicate slug
        $stmt = $db->prepare("SELECT id FROM announcements WHERE slug = ?");
        $stmt->execute([$slug]);
        if ($stmt->fetch()) {
            $slug .= '-' . substr($id, 0, 8);
        }

        // Store content as TipTap-compatible JSON
        $contentJson = json_encode(['type' => 'doc', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => $content]]]]]);

        $stmt = $db->prepare("INSERT INTO announcements (id, title, slug, excerpt, content, category, is_published, author_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id, $title, $slug, $excerpt, $contentJson, $category, $isPublished, 'Admin']);
    }
}

redirect(BASE_URL . '/admin/pengumuman.php');
