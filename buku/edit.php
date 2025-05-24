<?php
session_start();
if ($_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}
require '../config/koneksi.php';

$id = $_GET["id"];
$data = $conn->query("SELECT * FROM buku WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST["judul"];
    $penulis = $_POST["penulis"];
    $penerbit = $_POST["penerbit"];
    $tahun = $_POST["tahun"];
    $stok = $_POST["stok"];

    $stmt = $conn->prepare("UPDATE buku SET judul=?, penulis=?, penerbit=?, tahun=?, stok=? WHERE id=?");
    $stmt->bind_param("ssssii", $judul, $penulis, $penerbit, $tahun, $stok, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Buku</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6 bg-gray-100">
    <h1 class="text-2xl font-bold mb-4">Edit Buku</h1>
    <form method="POST" class="space-y-4 bg-white p-4 rounded shadow w-96">
        <input type="text" name="judul" value="<?= $data['judul'] ?>" required class="w-full border p-2 rounded">
        <input type="text" name="penulis" value="<?= $data['penulis'] ?>" required class="w-full border p-2 rounded">
        <input type="text" name="penerbit" value="<?= $data['penerbit'] ?>" required class="w-full border p-2 rounded">
        <input type="number" name="tahun" value="<?= $data['tahun'] ?>" required class="w-full border p-2 rounded">
        <input type="number" name="stok" value="<?= $data['stok'] ?>" required class="w-full border p-2 rounded">
        <div class="flex justify-between">
            <a href="index.php" class="text-gray-600 underline">Batal</a>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</body>

</html>