<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}
require '../config/koneksi.php';

$buku = $conn->query("SELECT * FROM buku");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6 bg-gray-100">

    <?php include '../dashboard/admin-dashboard.php'; ?>

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">Data Buku</h1>
            <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Buku</a>
        </div>
        <div class="mb-4">
            <a href="../dashboard/admin-dashboard.php" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                ← Kembali ke Dashboard
            </a>
        </div>


        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Judul</th>
                    <th class="p-2 border">Penulis</th>
                    <th class="p-2 border">Penerbit</th>
                    <th class="p-2 border">Tahun</th>
                    <th class="p-2 border">Stok</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $buku->fetch_assoc()): ?>
                    <tr>
                        <td class="p-2 border"><?= $no++ ?></td>
                        <td class="p-2 border"><?= $row["judul"] ?></td>
                        <td class="p-2 border"><?= $row["penulis"] ?></td>
                        <td class="p-2 border"><?= $row["penerbit"] ?></td>
                        <td class="p-2 border"><?= $row["tahun"] ?></td>
                        <td class="p-2 border"><?= $row["stok"] ?></td>
                        <td class="p-2 border space-x-2">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500">Edit</a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>