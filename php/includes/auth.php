<?php
require_once __DIR__ . '/../config.php';

function base64url_encode($data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data): string {
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_encode(array $payload): string {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload['iat'] = time();
    $payload['exp'] = time() + JWT_EXPIRY;
    $payload = json_encode($payload);

    $base64Header = base64url_encode($header);
    $base64Payload = base64url_encode($payload);
    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", JWT_SECRET, true);
    $base64Signature = base64url_encode($signature);

    return "$base64Header.$base64Payload.$base64Signature";
}

function jwt_decode(string $token): ?array {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;

    [$base64Header, $base64Payload, $base64Signature] = $parts;

    $validSig = hash_hmac('sha256', "$base64Header.$base64Payload", JWT_SECRET, true);
    $expectedSig = base64url_decode($base64Signature);

    if (!hash_equals($validSig, $expectedSig)) return null;

    $payload = json_decode(base64url_decode($base64Payload), true);
    if (!$payload || !isset($payload['exp'])) return null;
    if ($payload['exp'] < time()) return null;

    return $payload;
}

function setAuthCookie(string $token): void {
    setcookie(COOKIE_NAME, $token, [
        'expires' => time() + JWT_EXPIRY,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => isset($_SERVER['HTTPS']),
    ]);
}

function removeAuthCookie(): void {
    setcookie(COOKIE_NAME, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function getSession(): ?array {
    $token = $_COOKIE[COOKIE_NAME] ?? '';
    if (!$token) return null;
    return jwt_decode($token);
}

function isLoggedIn(): bool {
    return getSession() !== null;
}
