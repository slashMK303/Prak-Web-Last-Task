<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}
require '../../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST["judul"];
    $penulis = $_POST["penulis"];
    $penerbit = $_POST["penerbit"];
    $tahun = $_POST["tahun"];
    $stok = $_POST["stok"];

    // Proses upload gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $target = '../../../uploads/' . $gambar;

    // Pindahkan file ke folder uploads
    move_uploaded_file($tmp, $target);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, penerbit, tahun, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $judul, $penulis, $penerbit, $tahun, $stok, $gambar);

    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Tambah Buku</h1>
    <form method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-4 rounded shadow w-96">
        <input type="text" name="judul" placeholder="Judul" required class="w-full border p-2 rounded">
        <input type="text" name="penulis" placeholder="Penulis" required class="w-full border p-2 rounded">
        <input type="text" name="penerbit" placeholder="Penerbit" required class="w-full border p-2 rounded">
        <input type="number" name="tahun" placeholder="Tahun" required class="w-full border p-2 rounded">
        <input type="number" name="stok" placeholder="Stok" required class="w-full border p-2 rounded">
        <div>
            <label class="block mb-1">Gambar:</label>
            <input type="file" name="gambar" accept="image/*" required class="block">
        </div>
        <div class="flex justify-between">
            <a href="index.php" class="text-gray-600 underline">Kembali</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>
    </form>
</body>

</html>