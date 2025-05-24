<?php
session_start();
if (isset($_SESSION["role"])) {
    if ($_SESSION["role"] == "admin") {
        header("Location: dashboard/admin-dashboard.php");
    } else {
        header("Location: dashboard/member-dashboard.php");
    }
    exit;
} else {
    header("Location: auth/login.php");
    exit;
}
