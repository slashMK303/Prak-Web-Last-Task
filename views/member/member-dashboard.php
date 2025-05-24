<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION["role"] !== "member") {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Dashboard Member</h1>
            <a href="../auth/logout.php" class="text-red-500 hover:underline">Logout</a>
        </div>

        <p class="mb-4">Halo, <strong><?= $_SESSION["nama"] ?></strong>!</p>

        <div class="space-x-4">
            <a href="../member/buku.php" class="bg-blue-500 text-white px-4 py-2 rounded">Lihat Buku</a>
            <a href="../member/riwayat_peminjaman.php" class="bg-blue-500 text-white px-4 py-2 rounded">Riwayat Peminjaman</a>
        </div>
    </div>
</body>

</html>