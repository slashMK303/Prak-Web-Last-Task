<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_POST['buku_id'])) {
    die("Buku tidak dipilih.");
}

$user_id = $_SESSION['user']['id'];
$buku_id = (int) $_POST['buku_id']; // Pastikan integer
$tanggal_pinjam = date('Y-m-d');

// Ambil stok buku
$stmt = $conn->prepare("SELECT stok FROM buku WHERE id = ?");
$stmt->bind_param("i", $buku_id);
$stmt->execute();
$result = $stmt->get_result();
$buku = $result->fetch_assoc();

if ($buku && $buku['stok'] > 0) {
    // Insert peminjaman
    $stmt = $conn->prepare("INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, status, created_at) VALUES (?, ?, ?, 'Dipinjam', NOW())");
    $stmt->bind_param("iis", $user_id, $buku_id, $tanggal_pinjam);
    $stmt->execute();

    // Update stok buku
    $conn->query("UPDATE buku SET stok = stok - 1 WHERE id = $buku_id");
}

header("Location: buku.php");
exit;
