<?php
session_start();
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin") {
        header("Location: ../../views/admin/admin-dashboard.php");
    } else {
        header("Location: ../../views/member/member-dashboard.php");
    }
    exit;
} else {
    header("Location: auth/login.php");
    exit;
}
