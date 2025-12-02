<?php
define('ALLOW_CONFIG', true);
require "Bokachudx/config.php";

header("Content-Type: application/json");

if (!isset($_GET['key'])) {
    echo json_encode(["status" => false, "message" => "no key"]);
    exit();
}

$key = trim($_GET['key']);

$keys = file_exists($KEY_FILE) ? file($KEY_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

if (in_array($key, $keys, true)) {
    echo json_encode(["status" => true, "message" => "approved"]);
} else {
    echo json_encode(["status" => false, "message" => "not approved"]);
}
?>