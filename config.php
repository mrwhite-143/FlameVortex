<?php
declare(strict_types=1);
session_start();
define("DB_FILE", __DIR__ . "/approval.sqlite");

function get_db(): PDO {
    static $db = null;
    if ($db) return $db;
    $new = !file_exists(DB_FILE);
    $db = new PDO("sqlite:" . DB_FILE);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($new) init_db($db);
    return $db;
}

function init_db(PDO $db) {
    $db->exec("
        CREATE TABLE admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE,
            password_hash TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE device_keys (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            device_key TEXT UNIQUE,
            approved INTEGER DEFAULT 0,
            note TEXT DEFAULT '',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            approved_at TEXT
        );
        CREATE TABLE api_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            device_key TEXT,
            ip TEXT,
            path TEXT,
            response_code INTEGER,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        );
    ");
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['csrf'];
}
function csrf_check($t) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t);
}
function json_exit($arr, $code = 200) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($arr, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    exit;
}
?>