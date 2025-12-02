<?php
require __DIR__ . "/protect.php";
define('ALLOW_CONFIG', true);
require __DIR__ . "/Bokachudx/config.php";

$uid = trim($_POST['uid'] ?? '');
$action = $_POST['action'] ?? '';

if ($uid === '') {
    die("UID Missing");
}

$keys = file_exists($KEY_FILE) ? file($KEY_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

if ($action == "add") {

    if (!in_array($uid, $keys, true)) {
        file_put_contents($KEY_FILE, $uid . PHP_EOL, FILE_APPEND | LOCK_EX);
        echo "Key Approved Successfully!";
    } else {
        echo "Key Already Approved!";
    }

}

if ($action == "remove") {

    $new_list = array_values(array_diff($keys, [$uid]));
    file_put_contents($KEY_FILE, implode(PHP_EOL, $new_list) . (count($new_list)?PHP_EOL:''), LOCK_EX);

    echo "Key Removed Successfully!";
}
?>