<?php
session_start(); // Ambil session login
require_once '../halaman_utama/koneksi.php'; // Pastikan file koneksi.php ada dan benar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_pengaduan = $_POST['id_pengaduan'];
    $tanggapan = $_POST['tanggapan'];
    $tanggal_tanggapan = date("Y-m-d H:i:s");
     // Ambil username dari session login

    // Query dengan tambahan kolom username
    $sql = "INSERT INTO tanggapan (id_pengaduan, tanggapan, tanggal ) VALUES (?, ?, ?)";
    $stmt = $koneksi->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iss", $id_pengaduan, $tanggapan, $tanggal_tanggapan );

        if ($stmt->execute()) {
            echo "Tanggapan berhasil disimpan!";
            // Redirect (jika perlu)
            // header("Location: admin.php");
            // exit();
        } else {
            echo "Error menyimpan tanggapan: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $koneksi->error;
    }

    $koneksi->close();
} else {
    echo "Akses tidak sah!";
}
?>
