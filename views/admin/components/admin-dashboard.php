<?php
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION["role"] !== "admin") {
    header('Location: ../../../auth/login.php');
    exit;
}
require('../../../config/koneksi.php');

// Hitung statistik
$total_buku = $conn->query("SELECT COUNT(*) AS total FROM buku")->fetch_assoc()['total'];
$total_member = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='member'")->fetch_assoc()['total'];
$total_pinjam = $conn->query("SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Dashboard Admin</h1>
        <a href="../../../auth/logout.php" class="text-red-500 hover:underline">Logout</a>
    </div>

    <p class="mb-4">Halo, <strong><?= $_SESSION["nama"] ?></strong>!</p>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <h2 class="text-xl font-semibold"><?= $total_buku ?></h2>
            <p>Buku</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h2 class="text-xl font-semibold"><?= $total_member ?></h2>
            <p>Member</p>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <h2 class="text-xl font-semibold"><?= $total_pinjam ?></h2>
            <p>Sedang Dipinjam</p>
        </div>
    </div>

    <div class="space-x-4 mb-6">
        <a href="/views/admin/buku/index.php" class="bg-blue-500 text-white px-4 py-2 rounded">Manajemen Buku</a>
        <a href="/views/admin/member/index.php" class="bg-green-500 text-white px-4 py-2 rounded">Data Member</a>
        <a href="/views/admin/peminjaman/index.php" class="bg-purple-500 text-white px-4 py-2 rounded">Riwayat Peminjaman</a>
    </div>

</body>

</html>