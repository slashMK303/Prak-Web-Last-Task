<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../../../auth/login.php");
    exit;
}
require '../../../config/koneksi.php';

$id = $_GET["id"];
$conn->query("DELETE FROM buku WHERE id = $id");
header("Location: index.php");
