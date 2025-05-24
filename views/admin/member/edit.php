<?php
require '../../../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $member['nama'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET nama=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $nama, $email, $id);

    $stmt->execute();

    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM users WHERE id = $id");
$member = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Edit Member</h2>
        <form method="POST" class="space-y-3">
            <input type="text" name="nama" value="<?= $member['nama'] ?>" required class="w-full border p-2 rounded">
            <input type="email" name="email" value="<?= $member['email'] ?>" required class="w-full border p-2 rounded">
            <div class="flex justify-between">
                <a href="index.php" class="text-gray-500">Kembali</a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</body>

</html>