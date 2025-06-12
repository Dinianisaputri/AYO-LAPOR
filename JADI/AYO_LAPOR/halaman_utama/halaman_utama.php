<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.html');
    exit();
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayo Lapor!</title>
    <link rel="stylesheet" href="../assets/halaman_uatama.css">

<body>
<!-- HEADER -->
<header>
    <h1>Ayo Lapor!</h1>
    <nav>
        <a href="tentang.html">Tentang Ayo Lapor!</a>
        <a href="../index.php" class="home-btn" title="Kembali ke Beranda">
            <svg viewBox="0 0 24 24"><path d="M10.19 2.44a2.25 2.25 0 0 1 3.62 0l7.25 10.5A2.25 2.25 0 0 1 19.25 16H18v4.25A2.75 2.75 0 0 1 15.25 23h-6.5A2.75 2.75 0 0 1 6 20.25V16H4.75a2.25 2.25 0 0 1-1.81-3.06l7.25-10.5ZM8 20.25c0 .41.34.75.75.75h6.5a.75.75 0 0 0 .75-.75V15a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5.25ZM12 4.5 4.75 15h2.5V15a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.01h2.5L12 4.5Z"/></svg>
            Beranda
        </a>
    </nav>
</header>

<!-- KONTEN -->
<main>
    <section class="welcome">
        <h2>Selamat datang di Layanan Aspirasi dan Pengaduan Online Masyarakat Desa Kamboja!</h2>
        <p>Sampaikan Laporan Anda Langsung</p>
    </section>

    <section class="report-form">
        <h3>Sampaikan Laporan Anda</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="nama_pelapor">Nama Pelapor *</label>
                <input type="text" id="nama_pelapor" name="nama_pelapor" required placeholder="Masukkan nama lengkap Anda">
            </div>
            <!-- HIDDEN USERNAME -->
            <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">

            <div class="form-group">
                <label for="judul">Judul Laporan *</label>
                <input type="text" id="judul" name="judul" required placeholder="Masukkan judul laporan yang jelas">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kategori">Kategori *</label>
                    <select id="kategori" name="kategori" required>
                        <option value="">Pilih Kategori</option>
                        <option value="pengaduan">Pengaduan</option>
                        <option value="aspirasi">Aspirasi</option>
                        <option value="permintaan">Permintaan Informasi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal Kejadian *</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi Kejadian *</label>
                <input type="text" id="lokasi" name="lokasi" required placeholder="Masukkan lokasi kejadian">
            </div>

            <div class="form-group">
                <label for="isi">Isi Laporan *</label>
                <textarea id="isi" name="isi" rows="6" required placeholder="Jelaskan secara detail kronologi kejadian, dampak yang ditimbulkan, dan harapan penyelesaian..."></textarea>
            </div>

            <div class="form-submit">
                <button type="submit">KIRIM LAPORAN</button>
            </div>
        </form>
        <?php
        // Tampilkan hasil inputan user jika ada data POST (setelah submit)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo '<div class="report-form" style="margin-top:30px; background:#e0f7fa;">';
            echo '<h3>Hasil Laporan Anda</h3>';
            echo '<ul style="list-style:none; padding:0;">';
            echo '<li><strong>Nama Pelapor:</strong> ' . htmlspecialchars($_POST['nama_pelapor'] ?? '') . '</li>';
            echo '<li><strong>Judul:</strong> ' . htmlspecialchars($_POST['judul'] ?? '') . '</li>';
            echo '<li><strong>Kategori:</strong> ' . htmlspecialchars($_POST['kategori'] ?? '') . '</li>';
            echo '<li><strong>Tanggal Kejadian:</strong> ' . htmlspecialchars($_POST['tanggal'] ?? '') . '</li>';
            echo '<li><strong>Lokasi:</strong> ' . htmlspecialchars($_POST['lokasi'] ?? '') . '</li>';
            echo '<li><strong>Isi Laporan:</strong> ' . nl2br(htmlspecialchars($_POST['isi'] ?? '')) . '</li>';
            echo '</ul>';
            echo '</div>';
        }
        ?>
    </section>

    <!-- TAMPILKAN DAFTAR LAPORAN USER DARI DATABASE -->
    <section class="report-form" style="margin-top:40px;">
        <h3>Daftar Laporan Anda</h3>
        <?php
        include 'koneksi.php';
        // Proses simpan laporan ke database
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_pelapor = $_POST['nama_pelapor'];
            $judul = $_POST['judul'];
            $kategori = $_POST['kategori'];
            $tanggal = $_POST['tanggal'];
            $lokasi = $_POST['lokasi'];
            $isi = $_POST['isi'];
            // Ambil username dari session
            $username = $_SESSION['username'];
            $sql = "INSERT INTO pengaduan (username, judul, isi, tanggal, lokasi, kategori, nama_pelapor) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssssss", $username, $judul, $isi, $tanggal, $lokasi, $kategori, $nama_pelapor);
            $stmt->execute();
            $stmt->close();
        }
        // Filter laporan hanya milik user login
        $sql = "SELECT * FROM pengaduan WHERE username = ? ORDER BY tanggal DESC";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="report" style="background:#f8fafc; border:1.5px solid #e5e7eb; border-radius:10px; margin-bottom:18px; padding:18px;">
            <div style="font-weight:bold; color:#1e3a8a; margin-bottom:6px;">Judul: <?= htmlspecialchars($row['judul']) ?></div>
            <div><strong>Nama Pelapor:</strong> <?= htmlspecialchars($row['nama_pelapor']) ?></div>
            <div><strong>Kategori:</strong> <?= htmlspecialchars($row['kategori']) ?></div>
            <div><strong>Tanggal:</strong> <?= htmlspecialchars($row['tanggal']) ?></div>
            <div><strong>Lokasi:</strong> <?= htmlspecialchars($row['lokasi']) ?></div>
            <div><strong>Isi:</strong> <?= nl2br(htmlspecialchars($row['isi'])) ?></div>
            <div style="margin-top:12px;">
            <?php if ($row['username'] == $_SESSION['username']): ?>
                <a href="edit_laporan.php?id=<?= $row['id'] ?>" style="background:#2563eb; color:white; padding:7px 18px; border-radius:6px; text-decoration:none; margin-right:8px;">Edit</a>
                <a href="hapus_laporan.php?id=<?= $row['id'] ?>" style="background:#ef4444; color:white; padding:7px 18px; border-radius:6px; text-decoration:none;" onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
            <?php endif; ?>
            </div>

            <!-- TAMPILKAN TANGGAPAN ADMIN JIKA ADA -->
            <?php
            // Query untuk mengambil tanggapan admin terhadap laporan ini
            $tanggapan_sql = "SELECT * FROM tanggapan WHERE id_pengaduan = ? ORDER BY tanggal ASC";
            $tanggapan_stmt = $koneksi->prepare($tanggapan_sql);
            $tanggapan_stmt->bind_param("i", $row['id']);
            $tanggapan_stmt->execute();
            $tanggapan_result = $tanggapan_stmt->get_result();
            if ($tanggapan_result && $tanggapan_result->num_rows > 0):
            ?>
            <div class="tanggapan-admin" style="margin-top:16px; padding:12px; background:#eef9ff; border-left:4px solid #3b82f6; border-radius:8px;">
                <strong>Tanggapan Admin:</strong>
                <div style="margin-top:8px;">
                <?php while ($tanggapan = $tanggapan_result->fetch_assoc()): ?>
                    <div class="tanggapan-item" style="margin-bottom:10px;">
                        <div class="tanggapan-meta" style="font-size:0.9em; color:#555;">
                            <?= htmlspecialchars($tanggapan['tanggal']) ?>
                        </div>
                        <div class="tanggapan-content" style="margin-top:4px;"><?= nl2br(htmlspecialchars($tanggapan['tanggapan'])) ?></div>
                    </div>
                <?php endwhile; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="tanggapan-admin" style="margin-top:16px; color:#888; font-style:italic;">Belum ada tanggapan admin.</div>
            <?php endif; ?>
        </div>
        <?php endwhile; else: ?>
            <p style="color:#888;">Belum ada laporan yang masuk.</p>
        <?php endif; ?>
    </section>
</main>

<!-- FOOTER -->
<footer>
    <p>© 2025 Ayo Lapor! </p>
</footer>

</body>
</html>