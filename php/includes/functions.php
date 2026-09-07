<?php
function slugify(string $value): string {
    $value = strtolower($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value);
    $value = trim($value, '-');
    return $value;
}

function hashPassword(string $password): string {
    return hash('sha256', $password);
}

function formatDateID(string $dateStr): string {
    $ts = strtotime($dateStr);
    if (!$ts) return $dateStr;
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
    $d = date('j', $ts);
    $m = $months[(int)date('n', $ts)];
    $y = date('Y', $ts);
    return "$d $m $y";
}

function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function setFlash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function uploadFile(array $file, string $folder): ?string {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    $destDir = UPLOAD_DIR . '/' . $folder;
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    $filename = time() . '.' . $ext;
    $dest = $destDir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
    return '/uploads/' . $folder . '/' . $filename;
}

function renderTipTapContent(string $jsonContent): string {
    $data = json_decode($jsonContent, true);
    if (!$data || !isset($data['content']) || !is_array($data['content'])) {
        return '<p class="text-gray-500">Konten tidak tersedia.</p>';
    }
    return renderNodes($data['content']);
}

function renderNodes(array $nodes): string {
    $html = '';
    foreach ($nodes as $node) {
        $html .= renderNode($node);
    }
    return $html;
}

function renderNode(array $node): string {
    $type = $node['type'] ?? '';
    $content = $node['content'] ?? [];
    $attrs = $node['attrs'] ?? [];
    $text = $node['text'] ?? '';
    $marks = $node['marks'] ?? [];

    switch ($type) {
        case 'paragraph':
            return '<p>' . renderNodes($content) . '</p>';
        case 'heading':
            $level = max(1, min(6, (int)($attrs['level'] ?? 2)));
            return '<h' . $level . '>' . renderNodes($content) . '</h' . $level . '>';
        case 'bulletList':
            return '<ul>' . renderNodes($content) . '</ul>';
        case 'orderedList':
            return '<ol>' . renderNodes($content) . '</ol>';
        case 'listItem':
            return '<li>' . renderNodes($content) . '</li>';
        case 'blockquote':
            return '<blockquote>' . renderNodes($content) . '</blockquote>';
        case 'horizontalRule':
            return '<hr>';
        case 'hardBreak':
            return '<br>';
        case 'text':
            $output = esc($text);
            foreach ($marks as $mark) {
                $mt = $mark['type'] ?? '';
                if ($mt === 'bold') $output = '<strong>' . $output . '</strong>';
                elseif ($mt === 'italic') $output = '<em>' . $output . '</em>';
                elseif ($mt === 'strike') $output = '<s>' . $output . '</s>';
                elseif ($mt === 'code') $output = '<code class="bg-gray-100 px-1 rounded">' . $output . '</code>';
            }
            return $output;
        default:
            return '';
    }
}

function getCategoryColor(string $category): string {
    $colors = [
        'Akademik' => 'bg-blue-100 text-blue-800',
        'Kegiatan' => 'bg-emerald-100 text-emerald-800',
        'Prestasi' => 'bg-amber-100 text-amber-900',
        'Informasi' => 'bg-gray-100 text-gray-600',
    ];
    return $colors[$category] ?? 'bg-gray-100 text-gray-600';
}
