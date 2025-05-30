<?php
require '../../../config/koneksi.php';
session_start();

// Cek hanya admin yang bisa
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../../../auth/login.php');
    exit;
}

// Ambil data buku
$buku = $conn->query("SELECT * FROM buku")->fetch_all(MYSQLI_ASSOC);

// Ambil data member
$member = $conn->query("SELECT * FROM users WHERE role = 'member'")->fetch_all(MYSQLI_ASSOC);

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $buku_id = $_POST['buku_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $status = 'dipinjam';

    // Cek stok buku
    $cekStok = $conn->query("SELECT stok FROM buku WHERE id = $buku_id")->fetch_assoc();
    if ($cekStok['stok'] <= 0) {
        echo "<script>alert('Stok buku habis, tidak bisa dipinjam!'); window.location='index.php';</script>";
        exit;
    }

    // Simpan peminjaman
    $stmt = $conn->prepare("INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $user_id, $buku_id, $tanggal_pinjam, $status);
    $stmt->execute();

    // Kurangi stok buku
    $conn->query("UPDATE buku SET stok = stok - 1 WHERE id = $buku_id");

    echo "<script>alert('Peminjaman berhasil'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <?php include '../components/admin-dashboard.php'; ?>

    <center>
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
            <h1 class="text-xl font-bold mb-4">Tambah Peminjaman</h1>
            <form method="post">
                <div class="mb-3">
                    <label class="block font-medium">Member</label>
                    <select name="user_id" class="w-full border p-2 rounded">
                        <?php foreach ($member as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= $m['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block font-medium">Buku</label>
                    <select name="buku_id" class="w-full border p-2 rounded">
                        <?php foreach ($buku as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= $b['judul'] ?> (Stok: <?= $b['stok'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block font-medium">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="w-full border p-2 rounded" required>
                </div>
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                <a href="index.php" class="ml-2 text-gray-600">← Kembali</a>
            </form>
        </div>
    </center>
</body>

</html>