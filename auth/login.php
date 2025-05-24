<?php
session_start();
require '../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        $_SESSION['user'] = $user;
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["nama"] = $user["nama"];
        $_SESSION["role"] = $user["role"];

        if ($user["role"] == "admin") {
            header("Location: ../dashboard/admin.php");
        } else {
            header("Location: ../dashboard/member.php");
        }
        exit;
    } else {
        $error = "Email atau password salah!";
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
    <form action="" method="POST" class="bg-white p-6 rounded shadow w-80">
        <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

        <?php if (isset($error)) echo "<p class='text-red-500 text-sm mb-3'>$error</p>"; ?>

        <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded mb-3">
        <input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded mb-4">
        <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Masuk</button>
    </form>
</body>

</html>