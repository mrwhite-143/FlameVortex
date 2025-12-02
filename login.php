<?php
require "config.php";
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $u = $_POST["username"] ?? "";
    $p = $_POST["password"] ?? "";
    $db = get_db();
    $stmt = $db->prepare("SELECT id, password_hash FROM admins WHERE username=? LIMIT 1");
    $stmt->execute([$u]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin && password_verify($p, $admin["password_hash"])) {
        session_regenerate_id(true);
        $_SESSION["admin_id"] = $admin["id"];
        $_SESSION["admin_user"] = $u;
        header("Location: approval.php"); exit;
    } else $error = "Invalid login!";
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title></head><body>
<h2>Admin Login</h2>
<div style="color:red"><?= htmlspecialchars($error) ?></div>
<form method="post">
Username: <input name="username" required><br><br>
Password: <input type="password" name="password" required><br><br>
<button>Login</button>
</form>
</body></html>