<?php
session_start();
require '../../../config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password_mentah = $_POST['password'];
    $role = 'member';

    // Validasi Input
    if (empty($nama) || empty($email) || empty($password_mentah)) {
        $error = "Semua kolom wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password_mentah) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek Email Duplikat
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Email ini sudah terdaftar. Gunakan email lain.";
        } else {

            $password_terenkripsi = password_hash($password_mentah, PASSWORD_DEFAULT);

            $stmt_insert = $conn->prepare("INSERT INTO users (nama, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt_insert->bind_param("ssss", $nama, $email, $password_terenkripsi, $role);

            if ($stmt_insert->execute()) {

                $_SESSION['success_message'] = "Member '$nama' berhasil ditambahkan!";
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal menambahkan member: " . $stmt_insert->error;
            }
        }
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

    <?php include '../components/admin-dashboard.php'; ?>

    <center>
        <div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Tambah Member</h2>

            <?php if (isset($error) && !empty($error)): ?>
                <p class='text-red-500 text-sm mb-3 text-center'><?= $error ?></p>
            <?php endif; ?>
            <?php if (isset($success) && !empty($success)): ?>
                <p class='text-green-500 text-sm mb-3 text-center'><?= $success ?></p>
            <?php endif; ?>

            <form method="POST" class="space-y-3">
                <input type="text" name="nama" placeholder="Nama" required class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="flex justify-between items-center mt-4">
                    <a href="index.php" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Kembali</a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Simpan</button>
                </div>
            </form>
        </div>
    </center>
</body>

</html>