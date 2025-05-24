<?php
require '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM peminjaman WHERE id = $id");
header('Location: index.php');
exit;
