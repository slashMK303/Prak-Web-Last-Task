<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$members = $conn->query("SELECT * FROM users WHERE role = 'member'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <?php include '../dashboard/admin-dashboard.php'; ?>

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">Data Member</h1>
            <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Member</a>
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
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1;
                while ($row = $members->fetch_assoc()): ?>
                    <tr class="border-t">
                        <td class="p-2 border"><?= $no++ ?></td>
                        <td class="p-2 border"><?= $row['nama'] ?></td>
                        <td class="p-2 border"><?= $row['email'] ?></td>
                        <td class="p-2 border space-x-2">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-500">Edit</a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus member?')" class="text-red-500">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>