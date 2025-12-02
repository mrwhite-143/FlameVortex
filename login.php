<?php
session_start();
define('ALLOW_CONFIG', true);
require "Bokachudx/config.php";

if (isset($_POST['password'])) {
    if (password_verify($_POST['password'], $ADMIN_PASS_HASH)) {
        $_SESSION['admin'] = true;
        header("Location: approval.php");
        exit();
    } else {
        $error = "Wrong Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FlameVortex Admin Login</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <h2>FlameVortex Admin</h2>
    <form method="POST">
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
    <p class="error"><?= isset($error) ? htmlspecialchars($error) : '' ?></p>
</div>

</body>
</html>
