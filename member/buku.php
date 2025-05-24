<?php
session_start();
require '../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header("Location: ../auth/login.php");
    exit;
}

$buku = $conn->query("SELECT * FROM buku ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<h2 class="text-xl font-bold mb-4">Daftar Buku</h2>

<table class="w-full border">
    <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">#</th>
            <th class="border p-2">Judul</th>
            <th class="border p-2">Penulis</th>
            <th class="border p-2">Stok</th>
            <th class="border p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($buku as $i => $row): ?>
            <tr>
                <td class="border p-2"><?= $i + 1 ?></td>
                <td class="border p-2"><?= $row['judul'] ?></td>
                <td class="border p-2"><?= $row['penulis'] ?></td>
                <td class="border p-2"><?= $row['stok'] ?></td>
                <td class="border p-2">
                    <?php if ($row['stok'] > 0): ?>
                        <form action="pinjam.php" method="post" style="display:inline;">
                            <input type="hidden" name="buku_id" value="<?= $row['id'] ?>">
                            <button class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Pinjam</button>
                        </form>

                    <?php else: ?>
                        <span class="text-red-500 text-sm">Stok Habis</span>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>