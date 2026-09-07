<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/admin/visi-misi.php');
}

$visi = $_POST['visi'] ?? '';
$misiRaw = $_POST['misi'] ?? '';
$misi = array_filter(array_map('trim', explode("\n", $misiRaw)));

$db = getDB();
$stmt = $db->prepare("INSERT OR REPLACE INTO site_config (id, visi, misi) VALUES ('main', ?, ?)");
$stmt->execute([$visi, json_encode(array_values($misi))]);

redirect(BASE_URL . '/admin/visi-misi.php');
