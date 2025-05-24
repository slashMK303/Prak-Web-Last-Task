<?php
require '../../config/koneksi.php';
session_start();

// Cek apakah yang login adalah member
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header('Location: ../../auth/login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

$query = "SELECT p.*, b.judul as judul_buku 
          FROM peminjaman p
          JOIN buku b ON p.buku_id = b.id
          WHERE p.user_id = $user_id
          ORDER BY p.id DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <?php include 'member-dashboard.php'; ?>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h1 class="text-xl font-bold">Riwayat Peminjaman</h1>
            <a href="/views/member/member-dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded">← Kembali</a>
        </div>

        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Judul Buku</th>
                    <th class="border p-2">Tanggal Pinjam</th>
                    <th class="border p-2">Tanggal Kembali</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="border p-2"><?= $no++ ?></td>
                        <td class="border p-2"><?= $row['judul_buku'] ?></td>
                        <td class="border p-2"><?= $row['tanggal_pinjam'] ?></td>
                        <td class="border p-2"><?= $row['tanggal_kembali'] ?? '-' ?></td>
                        <td class="border p-2"><?= $row['status'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>