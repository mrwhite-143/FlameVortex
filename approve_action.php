<?php
require "config.php";
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: approval.php'); exit; }
if (!csrf_check($_POST['csrf'] ?? '')) { echo 'Invalid CSRF'; exit; }
$db = get_db();
$action = $_POST['action'] ?? '';
if ($action === 'approve') {
    $stmt = $db->prepare("UPDATE device_keys SET approved=1, approved_at=CURRENT_TIMESTAMP WHERE id=?");
    $stmt->execute([$_POST['id'] ?? 0]);
} elseif ($action === 'unapprove') {
    $stmt = $db->prepare("UPDATE device_keys SET approved=0, approved_at=NULL WHERE id=?");
    $stmt->execute([$_POST['id'] ?? 0]);
} elseif ($action === 'add') {
    $dk = trim($_POST['device_key'] ?? '');
    $note = trim($_POST['note'] ?? '');
    if ($dk === '') { header('Location: approval.php'); exit; }
    $stmt = $db->prepare("INSERT OR IGNORE INTO device_keys(device_key, approved, note, approved_at) VALUES (?,1,?,CURRENT_TIMESTAMP)");
    $stmt->execute([$dk, $note]);
}
header('Location: approval.php');
exit;
?>