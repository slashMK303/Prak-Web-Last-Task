<?php
require '../../../config/koneksi.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../../auth/login.php');
    exit;
}

$id = $_GET['id'];

// Ambil data peminjaman
$peminjaman = $conn->query("SELECT * FROM peminjaman WHERE id = $id")->fetch_assoc();
if (!$peminjaman || $peminjaman['status'] !== 'dipinjam') {
    echo "<script>alert('Data tidak valid!'); window.location='index.php';</script>";
    exit;
}

// Update status dan tanggal kembali
$conn->query("UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURDATE() WHERE id = $id");

// Kembalikan stok
$conn->query("UPDATE buku SET stok = stok + 1 WHERE id = {$peminjaman['buku_id']}");

echo "<script>alert('Buku berhasil dikembalikan!'); window.location='index.php';</script>";
