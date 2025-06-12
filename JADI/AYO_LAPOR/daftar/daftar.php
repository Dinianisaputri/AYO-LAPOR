<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $whatsapp = $_POST['whatsapp'];
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Validasi kata sandi
    if ($password !== $konfirmasi_password) {
        die("Kata sandi dan konfirmasi kata sandi tidak cocok!");
    }

    // Hash kata sandi untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Konfigurasi database
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "pengaduan";

    // Membuat koneksi
    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cek apakah username atau email sudah ada
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        die("Username atau email sudah terdaftar. Silakan gunakan yang lain.");
    }

    // Query untuk menyimpan data
    $sql = "INSERT INTO users (nama_lengkap, username, email, whatsapp, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error pada prepare statement: " . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param("sssss", $nama_lengkap, $username, $email, $whatsapp, $hashed_password);

    // Eksekusi query
    if ($stmt->execute()) {
        header("Location: ../index.php?message=Pengaduan berhasil dikirim!");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>