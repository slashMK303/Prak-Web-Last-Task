<?php
session_start();
require '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header("Location: ../../auth/login.php");
    exit;
}

$buku = $conn->query("SELECT * FROM buku ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <?php include 'member-dashboard.php'; ?>

    <div class="max-w-7xl mx-auto p-6 bg-white rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Daftar Buku</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($buku as $row): ?>
                <div class="bg-gray-100 rounded-2xl shadow p-4 flex flex-col justify-between">
                    <img src="../../../uploads/<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>" class="h-48 w-full object-cover rounded-lg mb-4">

                    <div>
                        <h3 class="text-lg font-semibold mb-1"><?= $row['judul'] ?></h3>
                        <p class="text-sm text-gray-600 mb-1"><strong>Penerbit:</strong> <?= $row['penerbit'] ?></p>
                        <p class="text-sm mb-3">
                            <strong>Stok:</strong>
                            <span class="<?= $row['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $row['stok'] > 0 ? $row['stok'] . ' tersedia' : 'Stok Habis' ?>
                            </span>
                        </p>
                    </div>

                    <?php if ($row['stok'] > 0): ?>
                        <form action="pinjam.php" method="post" class="mt-auto">
                            <input type="hidden" name="buku_id" value="<?= $row['id'] ?>">
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md text-sm">Pinjam</button>
                        </form>
                    <?php else: ?>
                        <button class="w-full bg-gray-400 text-white py-2 rounded-md text-sm cursor-not-allowed" disabled>Stok Habis</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>