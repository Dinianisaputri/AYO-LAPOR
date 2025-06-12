<?php
require_once 'koneksi.php';

$error_message = "";
$success_message = "";

if (!$koneksi) {
    die("Gagal terhubung ke database. Periksa koneksi.php.");
}

// Proses Form Pengaduan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $judul     = $_POST['judul'];
    $isi       = $_POST['isi'];
    $tanggal   = $_POST['tanggal'];
    $lokasi    = $_POST['lokasi'];
    $kategori  = $_POST['kategori'];
    $nama_pelapor = $_POST['nama_pelapor'];

    $sql = "INSERT INTO pengaduan (judul, isi, tanggal, lokasi, kategori, nama_pelapor) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssss", $judul, $isi, $tanggal, $lokasi, $kategori, $nama_pelapor);

        if ($stmt->execute()) {
            $success_message = "Laporan Berhasil Dikirim"; // Teks keberhasilan diubah
        } else {
            $error_message = "Error saat menyimpan: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Error prepare statement (saat submit): " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            text-align: center; /* Agar teks di tengah */
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 80%; /* Atur lebar container */
            max-width: 600px; /* Lebar maksimum container */
        }

        h2 {
            color: green;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px; /* Berikan jarak dari pesan atau laporan */
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success_message): ?>
            <h2><?php echo $success_message; ?></h2>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <button onclick="window.location.href='../halaman_utama/halaman_utama.php'">Kembali ke Halaman Awal</button>
    </div>
</body>
</html>