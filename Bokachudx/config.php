<?php
if (!defined('ALLOW_CONFIG')) {
    header("Location: ../login.php");
    exit();
}

// Admin password hash (bcrypt)
$ADMIN_PASS_HASH = '$2y$10$ty/RDyQ4T6DSnxhkLYJfLehdtld.wlk0VccPYYgT0ZpfSOZB7Nx.6';

// Path to keys file
$KEY_FILE = __DIR__ . "/../keys/approved.txt";
?>
