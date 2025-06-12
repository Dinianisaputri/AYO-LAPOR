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

$id = $_GET['id'];

// Ambil data laporan lama
$result = $conn->query("SELECT * FROM pengaduan WHERE id = $id");
$data = $result->fetch_assoc();
if (!$data || $data['username'] !== $username) {
    die("Akses ditolak. Anda tidak berhak mengedit laporan ini.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $lokasi = $_POST['lokasi'];
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];

    $conn->query("UPDATE pengaduan SET judul='$judul', isi='$isi', lokasi='$lokasi', tanggal='$tanggal', kategori='$kategori' WHERE id = $id");
    header("Location: halaman_utama.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Laporan</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        padding: 30px;
        color: #333;

        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        box-sizing: border-box;
        flex-direction: column;
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 20px;
    }

    form {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    label, input, textarea, select, button {
        display: block;
        width: 100%;
        margin-bottom: 15px;
    }

    input[type="text"],
    input[type="date"],
    textarea,
    select {
        padding: 10px;
        border: 1.5px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        resize: vertical;
        box-sizing: border-box;
    }

    textarea {
        min-height: 100px;
    }

    button {
        background-color: #446745; /* warna hijau tua */
        color: white;
        font-weight: bold;
        border: none;
        padding: 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #365636; /* versi lebih gelap */
    }

    input:focus, textarea:focus, select:focus {
        border-color: #446745;
        outline: none;
    }
</style>

    </style>
</head>
<body>

<h2>Edit Laporan</h2>
<form method="post">
    Judul:
    <input type="text" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>
    
    Isi:
    <textarea name="isi" required><?= htmlspecialchars($data['isi']) ?></textarea>
    
    Lokasi:
    <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
    
    Tanggal:
    <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>" required>
    
    Kategori:
    <select name="kategori" required>
        <option value="pengaduan" <?= $data['kategori'] == 'pengaduan' ? 'selected' : '' ?>>Pengaduan</option>
        <option value="aspirasi" <?= $data['kategori'] == 'aspirasi' ? 'selected' : '' ?>>Aspirasi</option>
        <option value="permintaan" <?= $data['kategori'] == 'permintaan' ? 'selected' : '' ?>>Permintaan Informasi</option>
    </select>
    
    <button type="submit">Simpan Perubahan</button>
</form>

</body>
</html>
