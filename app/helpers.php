<?php
// app/helpers.php

/* =======================
   CONFIG
======================= */
function config(string $key, $default = null) {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/../config/config.php';
    }

    $parts = explode('.', $key);
    $value = $cfg;

    foreach ($parts as $p) {
        if (!is_array($value) || !array_key_exists($p, $value)) {
            return $default;
        }
        $value = $value[$p];
    }
    return $value;
}

function base_url(): string {
    return rtrim((string)config('app.base_url', ''), '/');
}

/* =======================
   HELPERS
======================= */
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string {
    $base = rtrim((string)config('app.base_url', ''), '/');
    $path = '/' . ltrim($path, '/');
    return $base . ($path === '/' ? '' : $path);
}

function redirect(string $path): void {
    header('Location: ' . url($path));
    exit;
}

/* =======================
   FLASH
======================= */
function flash_set(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array {
    if (empty($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

/* =======================
   AUTH
======================= */
function auth_check(): bool {
    return !empty($_SESSION['auth']);
}

function auth_user(): ?array {
    return $_SESSION['auth'] ?? null;
}

function auth_login(array $user): void {
    $_SESSION['auth'] = $user;
}

function auth_logout(): void {
    unset($_SESSION['auth']);
}

function require_auth(): void {
    if (!auth_check()) {
        flash_set('error', 'Debes iniciar sesión.');
        redirect('/login');
    }
}
