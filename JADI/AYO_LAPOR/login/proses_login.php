<?php
session_start();
include 'koneksi_login.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    // Ambil user berdasarkan username saja (jangan password!)
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

   if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: ../halaman_utama/halaman_utama.php");
            exit;
        } else {
            header("Location: login.html?error=Password%20salah!");
            exit;
        }
    } else {
        header("Location: login.html?error=Username%20tidak%20ditemukan!");
        exit;
    }
}
?>
