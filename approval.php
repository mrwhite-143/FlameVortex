<?php
require "config.php";
require_admin();
$db = get_db();
$keys = $db->query("SELECT * FROM device_keys ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Approval Panel</title></head><body>
<h2>Approval Panel</h2>
Logged in as: <?= htmlspecialchars($_SESSION["admin_user"] ?? '') ?> |
<a href="logout.php">Logout</a><br><br>

<table border="1" cellpadding="6" cellspacing="0">
<tr><th>ID</th><th>Device Key</th><th>Approved</th><th>Note</th><th>Created</th><th>Approved At</th><th>Action</th></tr>
<?php foreach ($keys as $k): ?>
<tr>
<td><?= intval($k['id']) ?></td>
<td><?= htmlspecialchars($k['device_key']) ?></td>
<td><?= $k['approved'] ? 'YES' : 'NO' ?></td>
<td><?= htmlspecialchars($k['note']) ?></td>
<td><?= htmlspecialchars($k['created_at']) ?></td>
<td><?= htmlspecialchars($k['approved_at'] ?? '') ?></td>
<td>
<form method="post" action="approve_action.php" style="display:inline">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
<input type="hidden" name="id" value="<?= intval($k['id']) ?>">
<?php if ($k['approved']): ?>
<button name="action" value="unapprove">Unapprove</button>
<?php else: ?>
<button name="action" value="approve">Approve</button>
<?php endif; ?>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>

<h3>Add Key</h3>
<form method="post" action="approve_action.php">
<input type="hidden" name="csrf" value="<?= csrf_token() ?>">
Key: <input name="device_key" required>
Note: <input name="note">
<button name="action" value="add">Add & Approve</button>
</form>

</body></html>