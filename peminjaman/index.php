<?php
require '../config/koneksi.php';
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';

$query = "
  SELECT p.*, u.nama AS member, b.judul AS buku 
  FROM peminjaman p
  JOIN users u ON p.user_id = u.id
  JOIN buku b ON p.buku_id = b.id
";

if ($cari !== '') {
    $cari = $conn->real_escape_string($cari);
    $query .= " WHERE u.nama LIKE '%$cari%' OR b.judul LIKE '%$cari%'";
}

$query .= " ORDER BY p.created_at DESC";

$data = $conn->query($query)->fetch_all(MYSQLI_ASSOC);

$query = "SELECT p.*, u.nama as nama_user, b.judul as judul_buku 
          FROM peminjaman p
          JOIN users u ON p.user_id = u.id
          JOIN buku b ON p.buku_id = b.id
          ORDER BY p.id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6 bg-gray-100">

    <?php include '../dashboard/admin-dashboard.php'; ?>

    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-bold">Data Peminjaman</h2>
            <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Peminjaman</a>
        </div>

        <div class="mb-4">
            <a href="../dashboard/admin-dashboard.php" class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                ← Kembali ke Dashboard
            </a>
        </div>

        <form method="get" class="mb-4">
            <input type="text" name="cari" placeholder="Cari nama member atau judul buku" class="border px-3 py-1 rounded w-64" value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">
            <button class="bg-blue-600 text-white px-3 py-1 rounded">Cari</button>
            <a href="index.php" class="ml-2 text-sm text-gray-600">Reset</a>
        </form>

        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">#</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Buku</th>
                    <th class="border p-2">Tanggal Pinjam</th>
                    <th class="border p-2">Tanggal Kembali</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($data) === 0): ?>
                    <tr>
                        <td colspan="6" class="text-center p-4">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data as $i => $row): ?>
                        <tr>
                            <td class="border p-2"><?= $i + 1 ?></td>
                            <td class="border p-2"><?= $row['member'] ?></td>
                            <td class="border p-2"><?= $row['buku'] ?></td>
                            <td class="border p-2"><?= $row['tanggal_pinjam'] ?></td>
                            <td class="border p-2"><?= $row['tanggal_kembali'] ?: '-' ?></td>
                            <td class="border p-2"><?= $row['status'] ?></td>
                            <td class="border p-2"></td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <!-- Berfungsi untuk melakukan pencarian tanpa refresh page dengan ajax -->
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const query = this.value;
            fetch('ajax_search.php?cari=' + encodeURIComponent(query))
                .then(res => res.text())
                .then(data => {
                    document.getElementById('result').innerHTML = data;
                });
        });

        // Jalankan pertama kali untuk tampilkan semua
        document.getElementById('search').dispatchEvent(new Event('input'));
    </script>

</body>

</html>