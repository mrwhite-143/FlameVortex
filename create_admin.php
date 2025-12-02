<?php
require "config.php";

/*
 * This script creates a default admin user.
 * It will insert username 'admin' with the password set below.
 * The password is stored hashed in the SQLite DB (password_hash).
 *
 * SECURITY: You can change the $username and $password variables
 * or run this script with CLI args: php create_admin.php username password
 */

$username = $argv[1] ?? 'admin';
$password = $argv[2] ?? 'flamevortex@#153134'; // default password provided

$db = get_db();
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT OR REPLACE INTO admins (id, username, password_hash) VALUES (
    COALESCE((SELECT id FROM admins WHERE username = :u), NULL), :u, :p
)");
try {
    $stmt->execute([':u'=>$username, ':p'=>$hash]);
    echo "Admin created/updated: $username\n";
} catch (Exception $e) {
    echo "Error: ".$e->getMessage()."\n";
}
?>