<?php
require '../../../config/koneksi.php';

$id = $_GET['id'];

if (isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit']; // BARIS INI YANG DIUBAH
    $stok = $_POST['stok'];
    $gambar_lama = $_POST['gambar_lama'];

    // Cek jika user upload gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_baru = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $path = '../../../uploads/' . $gambar_baru;

        // Hapus gambar lama jika ada dan bukan gambar default atau placeholder
        if (!empty($gambar_lama) && file_exists('../../../uploads/' . $gambar_lama)) {
            unlink('../../../uploads/' . $gambar_lama);
        }

        move_uploaded_file($tmp, $path);
    } else {
        $gambar_baru = $gambar_lama;
    }

    $query = "UPDATE buku SET judul=?, gambar=?, penulis=?, penerbit=?, tahun=?, stok=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $judul, $gambar_baru, $penulis, $penerbit, $tahun_terbit, $stok, $id);
    $stmt->execute();

    // Cek apakah update berhasil
    if ($stmt->affected_rows > 0) {
        header("Location: index.php");
        exit;
    } else {
        // Handle jika tidak ada baris yang terpengaruh (misal: ID tidak ditemukan atau data sama)
        // Atau tampilkan pesan error
        echo "Gagal memperbarui data atau tidak ada perubahan.";
    }
}

// Ambil data buku lama
$result = $conn->query("SELECT * FROM buku WHERE id=$id");
$buku = $result->fetch_assoc();

// Pastikan data buku ditemukan sebelum mencoba mengaksesnya
if (!$buku) {
    echo "Buku tidak ditemukan!";
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

    <?php include '../components/admin-dashboard.php'; ?>

    <center>
        <h1 class="text-2xl font-bold mb-4">Edit Buku</h1>
        <form method="POST" enctype="multipart/form-data" class="space-y-4 bg-white p-4 rounded shadow w-96">
            <input type="text" name="judul" value="<?= $buku['judul'] ?>" required class="w-full border p-2 rounded" placeholder="Judul Buku">
            <input type="text" name="penulis" value="<?= $buku['penulis'] ?>" required class="w-full border p-2 rounded" placeholder="Penulis">
            <input type="text" name="penerbit" value="<?= $buku['penerbit'] ?>" required class="w-full border p-2 rounded" placeholder="Penerbit">
            <input type="number" name="tahun_terbit" value="<?= $buku['tahun'] ?>" required class="w-full border p-2 rounded" placeholder="Tahun Terbit">
            <input type="number" name="stok" value="<?= $buku['stok'] ?>" required class="w-full border p-2 rounded" placeholder="Stok">

            <input type="hidden" name="gambar_lama" value="<?= $buku['gambar'] ?>">

            <label class="block text-left">Gambar Lama:</label>
            <img src="../../../uploads/<?= $buku['gambar'] ?>" width="80" alt="Gambar Buku" class="my-2 block">

            <label class="block text-left">Ganti Gambar (opsional):</label>
            <input type="file" name="gambar" accept="image/*" class="w-full border p-2 rounded">

            <div class="flex justify-between">
                <a href="index.php" class="text-gray-600 underline hover:text-gray-800 transition-colors duration-200">Batal</a>
                <button type="submit" name="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors duration-200">Update</button>
            </div>
        </form>
    </center>
</body>

</html>