<?php
require_once "config.php";
function require_admin() {
    if (empty($_SESSION["admin_id"])) {
        header("Location: login.php"); exit;
    }
}
?>