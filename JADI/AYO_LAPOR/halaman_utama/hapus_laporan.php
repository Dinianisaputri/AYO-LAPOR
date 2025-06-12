<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.html');
    exit();
}
$username = $_SESSION['username'];

$conn = new mysqli('localhost', 'root', '', 'pengaduan');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID tidak valid.");
}
// Cek kepemilikan laporan
$result = $conn->query("SELECT username FROM pengaduan WHERE id = $id");
$data = $result ? $result->fetch_assoc() : null;
if (!$data || $data['username'] !== $username) {
    die("Akses ditolak. Anda tidak berhak menghapus laporan ini.");
}

// Hapus tanggapan terkait terlebih dahulu
$res_t = $conn->query("DELETE FROM tanggapan WHERE id_pengaduan = $id");
// Hapus laporan utama
$res_l = $conn->query("DELETE FROM pengaduan WHERE id = $id");

if ($res_l) {
    header("Location: halaman_utama.php");
    exit();
} else {
    echo "Gagal menghapus laporan. Error: " . $conn->error;
}
?>
