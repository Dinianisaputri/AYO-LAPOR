<?php
require_once(dirname(__DIR__) . '/halaman_utama/koneksi.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin.php?msg=error");
    exit();
}

$id = intval($_GET['id']);

// Hapus tanggapan terkait laporan (jika ada)
$conn->query("DELETE FROM tanggapan WHERE id_pengaduan = $id");

// Hapus laporan
$result = $conn->query("DELETE FROM pengaduan WHERE id = $id");

if ($result) {
    header("Location: admin.php?msg=deleted");
} else {
    header("Location: admin.php?msg=error");
}
exit();
?>
