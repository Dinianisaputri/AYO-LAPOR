<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'pengaduan');
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$berita = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM berita_file WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $berita = $res->fetch_assoc();
    }
    $stmt->close();
}
$conn->close();

if (!$berita) {
    header("Location: index.php"); // Kembali ke halaman utama jika berita tidak ditemukan
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($berita['judul'] ?? 'Detail Berita'); ?> - Ayo Lapor!</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .container-detail {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .container-detail img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .container-detail h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .container-detail p {
            line-height: 1.6;
            color: #555;
            margin-bottom: 15px;
        }
        .meta-info {
            font-size: 0.9em;
            color: #777;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <h1>Ayo Lapor!</h1>
            <nav>
                <a href="tentang.html">Tentang Ayo Lapor!</a>
                <a href="login/login.html">Login</a>
                <a href="daftar/daftar.html">Daftar</a>
            </nav>
        </div>
    </header>

    <div class="container-detail">
        <a href="index.php" class="back-link">&larr; Kembali ke Berita</a>
        <h1><?php echo htmlspecialchars($berita['judul'] ?? 'Judul Tidak Tersedia'); ?></h1>
        <div class="meta-info">
            Upload: <?php echo htmlspecialchars($berita['tanggal_upload'] ?? 'Tanggal tidak diketahui'); ?>
        </div>
        <?php
        $is_img = preg_match('/\.(jpg|jpeg|png|gif)$/i', $berita['nama_file'] ?? '');
        if ($is_img && !empty($berita['path_file'])) {
            echo '<img src="' . htmlspecialchars($berita['path_file']) . '" alt="Gambar Berita">';
        } elseif (!empty($berita['path_file'])) {
            echo '<p><a href="' . htmlspecialchars($berita['path_file']) . '" target="_blank">Download File Lampiran</a></p>';
        }
        ?>
        <p><?php echo nl2br(htmlspecialchars($berita['deskripsi'] ?? 'Deskripsi tidak tersedia.')); ?></p>
    </div>

</body>
</html>