<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ayo Lapor!</title> <link rel="stylesheet" href="assets/style.css">
  <style>
    /* CSS Tambahan yang Akan Ditempatkan di Sini atau assets/style.css */
    .berita-container {
      position: relative; /* Untuk posisi panah */
      padding: 20px 0; /* Padding agar panah tidak terlalu mepet */
    }

    .berita-slider-wrapper {
      display: flex; /* Membuat card sejajar */
      overflow-x: auto; /* Mengaktifkan scrolling horizontal */
      scroll-snap-type: x mandatory; /* Membuat geseran berhenti di setiap card */
      -webkit-overflow-scrolling: touch; /* Scrolling halus di iOS */
      padding-bottom: 20px; /* Ruang untuk scrollbar di beberapa browser */
      gap: 20px; /* Jarak antar kartu */
      scrollbar-width: none; /* Sembunyikan scrollbar Firefox */
    }

    /* Sembunyikan scrollbar Webkit (Chrome, Safari) */
    .berita-slider-wrapper::-webkit-scrollbar {
      display: none;
    }

    .berita-slider-wrapper .card {
      flex: 0 0 auto; /* Mencegah card mengecil */
      width: 300px; /* Lebar tetap untuk setiap card */
      scroll-snap-align: start; /* Mengatur titik snap */
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      padding: 15px;
      background-color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between; /* Menjaga konten terdistribusi */
    }

    .berita-slider-wrapper .card img {
      max-width: 100%;
      height: 180px; /* Tinggi gambar tetap */
      object-fit: cover; /* Memastikan gambar terisi penuh tanpa distorsi */
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .berita-slider-wrapper .card h3 {
      font-size: 1.2em;
      margin-bottom: 10px;
      color: #333;
    }

    .berita-slider-wrapper .card p {
      font-size: 0.9em;
      color: #666;
      line-height: 1.5;
      margin-bottom: 10px;
      flex-grow: 1; /* Agar paragraf mengisi ruang yang tersedia */
    }

    .berita-slider-wrapper .card .read-more,
    .berita-slider-wrapper .card .download-file,
    .berita-slider-wrapper .card .view-image {
        display: inline-block;
        margin-top: 5px;
        padding: 5px 10px;
        background-color: #007bff; /* Warna biru */
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 0.9em;
    }

    .berita-slider-wrapper .card .read-more:hover,
    .berita-slider-wrapper .card .download-file:hover,
    .berita-slider-wrapper .card .view-image:hover {
        background-color: #0056b3;
    }
    
    .berita-slider-wrapper .card .read-more { background-color: #28a745; } /* Hijau untuk baca selengkapnya */
    .berita-slider-wrapper .card .read-more:hover { background-color: #218838; }

    .berita-slider-wrapper .card span {
        font-size: 0.8em;
        color: #888;
        margin-top: 5px;
    }
    
    /* Tombol Navigasi Slider */
    .slider-button {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0,0,0,0.5);
      color: white;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      z-index: 10;
      font-size: 2em;
      line-height: 0.5;
      border-radius: 5px;
    }
    .slider-button.prev {
      left: 10px;
    }
    .slider-button.next {
      right: 10px;
    }
    .slider-button:hover {
      background-color: rgba(0,0,0,0.7);
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

  <!-- BERITA SLIDER -->
  <h2>Berita Desa Kamboja</h2>
  <div class="berita-container">
    <button class="slider-button prev" onclick="scrollSlider(-1)">&lt;</button>
    <div class="berita-slider-wrapper" id="beritaSlider">
      <?php
      // Tampilkan file berita upload admin
      $conn = new mysqli('localhost', 'root', '', 'pengaduan');
      if ($conn->connect_error) {
        echo '<div style="color:red">Gagal koneksi database: ' . htmlspecialchars($conn->connect_error) . '</div>';
      } else {
        $is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
        // Proses update judul/deskripsi
        if ($is_admin && isset($_POST['edit_berita_id'])) {
          $id = intval($_POST['edit_berita_id']);
          $judul = $_POST['edit_judul'];
          $deskripsi = $_POST['edit_deskripsi'];
          $stmt = $conn->prepare("UPDATE berita_file SET judul=?, deskripsi=? WHERE id=?");
          $stmt->bind_param("ssi", $judul, $deskripsi, $id);
          $stmt->execute();
          $stmt->close();
        }
        // Proses hapus berita
        if ($is_admin && isset($_GET['hapus_berita_id'])) {
          $id = intval($_GET['hapus_berita_id']);
          $res = $conn->query("SELECT path_file FROM berita_file WHERE id=$id");
          $row = $res ? $res->fetch_assoc() : null;
          if ($row && file_exists($row['path_file'])) unlink($row['path_file']);
          $conn->query("DELETE FROM berita_file WHERE id=$id");
          header('Location: index.php');
          exit();
        }
        // Tampilkan berita
        $res = $conn->query("SELECT * FROM berita_file ORDER BY tanggal_upload DESC");
        if (!$res) {
          echo '<div style="color:red">Query error: ' . htmlspecialchars($conn->error) . '</div>';
        } else if ($res->num_rows > 0) {
          while ($f = $res->fetch_assoc()) {
            $is_img = preg_match('/\.(jpg|jpeg|png|gif)$/i', $f['nama_file']);
            echo '<div class="card">';
            if ($is_img && file_exists($f['path_file'])) {
              echo '<img src="' . htmlspecialchars($f['path_file']) . '" alt="Berita">';
            } else {
              // Pastikan gambar gotong royong.jpg ada di assets/img/
              echo '<img src="assets/img/gotong royong.jpg" alt="File">';
            }
            $judul = $f['judul'] ?? $f['nama_file'];
            $deskripsi = $f['deskripsi'] ?? '';
            echo '<h3>' . htmlspecialchars($judul) . '</h3>';
            $max_char = 200; // Batas karakter untuk deskripsi singkat
            $full_deskripsi = $f['deskripsi'] ?? '';
            $short_deskripsi = $full_deskripsi;
            $needs_readmore = false;
            if (strlen($full_deskripsi) > $max_char) {
              $short_deskripsi = substr($full_deskripsi, 0, $max_char);
              $short_deskripsi = substr($short_deskripsi, 0, strrpos($short_deskripsi, ' ')) . '...';
              $needs_readmore = true;
            }
            echo '<p>' . nl2br(htmlspecialchars($short_deskripsi)) . '</p>';
            if ($needs_readmore) {
              echo '<p><a href="berita.php?id=' . $f['id'] . '" class="read-more">Baca Selengkapnya &raquo;</a></p>';
            }
            // Form edit/hapus hanya untuk admin
            if ($is_admin) {
              echo '<form method="post" style="margin-top:10px;">';
              echo '<input type="hidden" name="edit_berita_id" value="' . $f['id'] . '">';
              echo '<input type="text" name="edit_judul" value="' . htmlspecialchars($judul) . '" placeholder="Judul" style="width:90%;margin-bottom:4px;">';
              echo '<textarea name="edit_deskripsi" placeholder="Deskripsi" style="width:90%;margin-bottom:4px;">' . htmlspecialchars($deskripsi) . '</textarea>';
              echo '<button type="submit">Simpan</button>';
              echo '</form>';
              echo '<a href="?hapus_berita_id=' . $f['id'] . '" onclick="return confirm(\'Hapus berita ini?\')" style="color:red;display:inline-block;margin-top:4px;">Hapus</a>';
            }
            echo '</div>';
          }
        } else {
          echo '<p style="text-align: center; width: 100%; color: #888;">Belum ada berita yang diunggah atau data kosong.</p>';
        }
      }
      ?>
    </div>
    <button class="slider-button next" onclick="scrollSlider(1)">&gt;</button>
  </div>

  <!-- TOMBOL AYO LAPOR -->
  <button class="btn-lapor" onclick="bukaPopup()">Ayo Lapor</button>

  <!-- SAMBUTAN KEPALA DESA -->
  <h2>Sambutan Kepala Desa Kamboja</h2>
  <div class="sambutan">
    <p>Assalamu'alaikum Warahmatullahi Wabarakatuh,</p>
    <p>Puji syukur kita panjatkan ke hadirat Allah SWT, karena atas rahmat dan karunia-Nya, website <strong>Pengaduan Masyarakat Desa Kamboja</strong> ini dapat hadir sebagai sarana komunikasi yang efektif antara pemerintah desa dengan seluruh warga masyarakat.</p>
    <p>Website ini dirancang khusus untuk memudahkan masyarakat dalam menyampaikan aspirasi, pengaduan, kritik, maupun saran terkait pelayanan publik, pembangunan, dan kehidupan bermasyarakat di Desa Kamboja.</p>
    <p>Kami menyadari pentingnya partisipasi aktif dari seluruh warga demi terciptanya pemerintahan desa yang transparan, responsif, dan akuntabel. Melalui platform ini, kami berkomitmen untuk menanggapi setiap laporan secara cepat dan bijak demi kebaikan bersama.</p>
    <p>Harapan kami, website ini tidak hanya menjadi tempat menyampaikan keluhan, tetapi juga menjadi wadah kolaborasi antara warga dan pemerintah desa dalam membangun Desa Kamboja yang lebih baik.</p>
    <p>Akhir kata, kami mengucapkan terima kasih atas dukungan dan kepercayaan masyarakat. Mari bersama-sama menjaga komunikasi yang sehat dan membangun demi kemajuan desa kita tercinta.</p>
    <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
    <p>Hormat kami,<br><strong>(Nama Kepala Desa)</strong><br>Kepala Desa Kamboja</p>
  </div>

  <!-- POPUP LOGIN -->
  <div class="popup-overlay" id="popupLogin">
    <div class="popup">
      <h3>Login Sebagai</h3>
      <button onclick="location.href='login/login.html'">User</button>
      <button onclick="location.href='admin/login_admin.html'">Admin</button>
      <button class="close-btn" onclick="tutupPopup()">Tutup</button>
    </div>
  </div>

  <script>
    function bukaPopup() {
      document.getElementById('popupLogin').style.display = 'flex';
    }
    function tutupPopup() {
      document.getElementById('popupLogin').style.display = 'none';
    }
    // --- SCRIPT UNTUK SLIDER BERITA ---
    const beritaSlider = document.getElementById('beritaSlider');
    const scrollAmount = 320; // Lebar card + gap (300px + 20px)
    function scrollSlider(direction) {
      beritaSlider.scrollBy({
        left: direction * scrollAmount,
        behavior: 'smooth'
      });
    }
    // --- AKHIR SCRIPT UNTUK SLIDER BERITA ---
  </script>

</body>
</html>