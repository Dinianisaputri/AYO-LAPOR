<?php
$conn = new mysqli('localhost', 'root', '', 'pengaduan');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID tidak valid.");
}

// Hapus hanya tanggapan tertentu
$res_t = $conn->query("DELETE FROM tanggapan WHERE id = $id");

if ($res_t) {
    header("Location: admin.php");
    exit();
} else {
    echo "Gagal menghapus tanggapan. Error: " . $conn->error;
}
?>
