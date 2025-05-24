<?php
require '../config/koneksi.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$data = $conn->query("
  SELECT p.*, u.nama AS member, b.judul AS buku
  FROM peminjaman p
  JOIN users u ON p.user_id = u.id
  JOIN buku b ON p.buku_id = b.id
  ORDER BY p.created_at DESC
")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Laporan Peminjaman</h1>
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">#</th>
                    <th class="border p-2">Member</th>
                    <th class="border p-2">Buku</th>
                    <th class="border p-2">Tanggal Pinjam</th>
                    <th class="border p-2">Tanggal Kembali</th>
                    <th class="border p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $i => $row): ?>
                    <tr>
                        <td class="border p-2"><?= $i + 1 ?></td>
                        <td class="border p-2"><?= $row['member'] ?></td>
                        <td class="border p-2"><?= $row['buku'] ?></td>
                        <td class="border p-2"><?= $row['tanggal_pinjam'] ?></td>
                        <td class="border p-2"><?= $row['tanggal_kembali'] ?: '-' ?></td>
                        <td class="border p-2"><?= $row['status'] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <a href="index.php" class="text-blue-600 block mt-4">← Kembali</a>
    </div>
</body>

</html>