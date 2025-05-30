<?php
session_start();
require '../config/koneksi.php'; // Sesuaikan path koneksi Anda

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $password_mentah = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validasi input
    if (empty($nama) || empty($email) || empty($password_mentah) || empty($confirm_password)) {
        $error = "Semua kolom harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif ($password_mentah !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($password_mentah) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Cek apakah email sudah terdaftar
        $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check_email->bind_param("s", $email);
        $stmt_check_email->execute();
        $stmt_check_email->store_result();

        if ($stmt_check_email->num_rows > 0) {
            $error = "Email ini sudah terdaftar. Gunakan email lain.";
        } else {
            // Enkripsi password sebelum disimpan
            $password_terenkripsi = password_hash($password_mentah, PASSWORD_DEFAULT);
            $role = 'member'; // Default role untuk registrasi adalah 'member'

            // Masukkan data user baru ke database
            $stmt_insert_user = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt_insert_user->bind_param("ssss", $nama, $email, $password_terenkripsi, $role);

            if ($stmt_insert_user->execute()) {
                $success = "Pendaftaran berhasil! Anda bisa login sekarang.";
                // Opsional: Redirect ke halaman login setelah registrasi berhasil
                // header("Location: login.php");
                // exit;
            } else {
                $error = "Terjadi kesalahan saat pendaftaran: " . $conn->error;
            }
        }
        $stmt_check_email->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar - Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex flex-col items-center">
        <h1 class="text-5xl font-bold text-center mb-10">Perpustakaan Online</h1>

        <form action="" method="POST" class="bg-white p-6 rounded shadow w-96 mt-10">
            <h2 class="text-2xl font-bold mb-4 text-center">Daftar Akun Baru</h2>

            <?php if (isset($error) && !empty($error)): ?>
                <p class='text-red-500 text-sm mb-3 text-center'><?= $error ?></p>
            <?php endif; ?>

            <?php if (isset($success) && !empty($success)): ?>
                <p class='text-green-500 text-sm mb-3 text-center'><?= $success ?></p>
            <?php endif; ?>

            <input type="text" name="nama" placeholder="Nama Lengkap" required class="w-full p-2 border rounded mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition duration-300">Daftar</button>

            <p class="mt-4 text-center text-sm text-gray-600">Sudah punya akun? <a href="login.php" class="text-blue-500 hover:underline">Login di sini</a></p>
        </form>
    </div>
</body>

</html>