<?php
session_start();
require '../config/koneksi.php'; // Pastikan path ini benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password_input = $_POST["password"]; // Simpan password yang diinput user

    // Mengambil user berdasarkan email
    $stmt = $conn->prepare("SELECT id, nama, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // Memverifikasi password yang diinput dengan password terenkripsi di database
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "admin") {
                header("Location: ../../views/admin/components/admin-dashboard.php");
            } else {
                header("Location: ../../views/member/member-dashboard.php");
            }
            exit;
        } else {
            $error = "Email atau password salah!";
        }
    } else {
        $error = "Email atau password salah!"; // Email tidak ditemukan
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex flex-col items-center">
        <h1 class="text-5xl font-bold text-center mb-10">Perpustakaan Online</h1>

        <form action="" method="POST" class="bg-white p-6 rounded shadow w-80 mt-10">
            <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

            <?php if (isset($error)) echo "<p class='text-red-500 text-sm mb-3 text-center'>$error</p>"; ?>

            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition duration-300">Masuk</button>

            <p class="mt-4 text-center text-sm text-gray-600">Belum punya akun? <a href="register.php" class="text-blue-500 hover:underline">Register di sini</a></p>
        </form>
    </div>
</body>

</html>