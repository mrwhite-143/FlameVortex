<?php
require __DIR__ . "/protect.php";
define('ALLOW_CONFIG', true);
require __DIR__ . "/Bokachudx/config.php";

$keys = file_exists($KEY_FILE) ? file($KEY_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

$msg = '';
if(isset($_POST['uid'], $_POST['action'])) {
    $uid = trim($_POST['uid']);
    $action = $_POST['action'];

    if($action == 'add') {
        if($uid === '') {
            $msg = "Key is empty!";
        } elseif(!in_array($uid, $keys, true)) {
            file_put_contents($KEY_FILE, $uid . PHP_EOL, FILE_APPEND | LOCK_EX);
            $keys[] = $uid;
            $msg = "Key Approved Successfully!";
        } else {
            $msg = "Key Already Approved!";
        }
    } elseif($action == 'remove') {
        if(in_array($uid, $keys, true)) {
            $keys = array_values(array_diff($keys, [$uid]));
            file_put_contents($KEY_FILE, implode(PHP_EOL, $keys) . (count($keys)?PHP_EOL:''), LOCK_EX);
            $msg = "Key Removed Successfully!";
        } else {
            $msg = "Key Not Found!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FlameVortex Approval Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
.key-list { margin-top:30px; color:#fff; list-style:none; padding:0; max-height:200px; overflow-y:auto;}
.key-list li { padding:10px; background:#222; margin-bottom:10px; border-radius:15px; box-shadow: inset 0 0 5px rgba(255,0,79,0.2); }
</style>
</head>
<body>

<div class="container">
<h1>FlameVortex Approval Panel</h1>

<form action="" method="POST">
    <input type="text" name="uid" placeholder="Enter Key" required>
    <div style="display:flex; gap:10px;">
        <button type="submit" name="action" value="add">Approve Key</button>
        <button type="submit" name="action" value="remove">Remove Key</button>
    </div>
</form>

<ul class="key-list">
<?php foreach($keys as $k): ?>
    <li><?= htmlspecialchars($k) ?></li>
<?php endforeach; ?>
</ul>

<p class="message"><?= htmlspecialchars($msg) ?></p>
</div>

</body>
</html>
