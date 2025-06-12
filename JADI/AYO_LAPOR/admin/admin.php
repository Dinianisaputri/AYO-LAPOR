<?php
// Koneksi database WAJIB di baris pertama sebelum HTML
// Pastikan file koneksi.php mendefinisikan $conn secara global

define('ROOT_PATH', dirname(__DIR__));
require_once(ROOT_PATH . '/halaman_utama/koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pengaduan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/admin.css">
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <h1 style="margin:0;"><i class="fas fa-shield-alt"></i> Dashboard Admin</h1>
                    <p style="margin:0;">Sistem Manajemen Pengaduan & Aspirasi Masyarakat</p>
                </div>
                <a href="../index.php" class="btn btn-primary" style="margin-left:20px;display:inline-flex;align-items:center;gap:6px;font-size:16px;padding:8px 16px;text-decoration:none;background:#2563eb;color:#fff;border-radius:6px;box-shadow:0 2px 8px rgba(30,64,175,0.15);transition:background 0.2s;">
                    <i class="fas fa-home"></i> Home
                </a>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                Laporan Masyarakat
            </h3>
            <?php
            // Proses tambah/edit/hapus tanggapan admin
            $tanggapan_message = '';
            if (isset($_POST['kirim_tanggapan'])) {
                $id_pengaduan = intval($_POST['id_pengaduan']);
                $tanggapan = trim($_POST['tanggapan']);
                $tanggal = date('Y-m-d H:i:s');
                if ($tanggapan !== '') {
                    $stmt = $conn->prepare('INSERT INTO tanggapan (id_pengaduan, tanggapan, tanggal) VALUES (?, ?, ?)');
                    $stmt->bind_param('iss', $id_pengaduan, $tanggapan, $tanggal);
                    if ($stmt->execute()) {
                        $tanggapan_message = '<div class="alert alert-success">Tanggapan berhasil dikirim.</div>';
                    } else {
                        $tanggapan_message = '<div class="alert alert-error">Gagal mengirim tanggapan.</div>';
                    }
                    $stmt->close();
                }
            }
            if (isset($_POST['edit_tanggapan_id'])) {
                $id = intval($_POST['edit_tanggapan_id']);
                $tanggapan = trim($_POST['edit_tanggapan']);
                $stmt = $conn->prepare('UPDATE tanggapan SET tanggapan=? WHERE id=?');
                $stmt->bind_param('si', $tanggapan, $id);
                if ($stmt->execute()) {
                    $tanggapan_message = '<div class="alert alert-success">Tanggapan berhasil diupdate.</div>';
                } else {
                    $tanggapan_message = '<div class="alert alert-error">Gagal update tanggapan.</div>';
                }
                $stmt->close();
            }
            if (isset($_GET['hapus_tanggapan_id'])) {
                $id = intval($_GET['hapus_tanggapan_id']);
                $conn->query('DELETE FROM tanggapan WHERE id=' . $id);
                 header("Location: ".$_SERVER['PHP_SELF']);
                 exit();
                $tanggapan_message = '<div class="alert alert-success">Tanggapan berhasil dihapus.</div>';
            }
            if ($tanggapan_message) echo $tanggapan_message;
            // Tampilkan laporan dan tanggapan
            $sql = "SELECT * FROM pengaduan ORDER BY tanggal DESC";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $id_pengaduan = $row['id'];
                    echo '<div class="report-card">';
                    // ...tampilkan info laporan seperti sebelumnya...
                    echo '<h4 class="report-title">'.htmlspecialchars($row['judul']).'</h4>';
                    echo '<div class="report-content">'.nl2br(htmlspecialchars($row['isi'])).'</div>';
                    echo '<div class="report-meta">';
                    echo '<div class="meta-item"><i class="fas fa-calendar-alt"></i> <span>'.htmlspecialchars($row['tanggal']).'</span></div>';
                    echo '<div class="meta-item"><i class="fas fa-map-marker-alt"></i> <span>'.htmlspecialchars($row['lokasi']).'</span></div>';
                    echo '<div class="meta-item"><i class="fas fa-user"></i> <span>'.htmlspecialchars($row['nama_pelapor'] ?? '-').'</span></div>';
                    echo '</div>';
                 // ... (Bagian kode di atas ini tetap sama) ...

                                // Tanggapan admin (Loop ini sudah benar)
                                $res_t = $conn->query("SELECT * FROM tanggapan WHERE id_pengaduan = $id_pengaduan ORDER BY tanggal ASC");
                                if ($res_t && $res_t->num_rows > 0) {
                                    while ($t = $res_t->fetch_assoc()) {
                                        echo '<div class="response-section">'; // Ini adalah div pembungkus satu tanggapan admin
                                        echo '<div class="response-header"><div class="admin-avatar">A</div><strong>Admin</strong></div>';
                                        // ====================================================================
                                        // KODE BARU UNTUK TOMBOL DI POJOK KANAN ATAS
                                        echo '<div class="tanggapan-admin-buttons-top-right">'; // <--- DIV BARU UNTUK POSISI ABSOLUTE
                                            // Form edit tanggapan
                                            echo '<form method="post" style="display:inline-block; margin-right: 5px;">'; // Jaga inline-block untuk tombol edit
                                            echo '<input type="hidden" name="edit_tanggapan_id" value="'.$t['id'].'">';
                                            // Textarea ini tidak perlu di sini lagi, karena ini form button-nya saja
                                            echo '<button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</button>';
                                            echo '</form>';
                                            // Link hapus tanggapan
                                            echo '<a href="?hapus_tanggapan_id='.$t['id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus tanggapan ini?\')"><i class="fas fa-trash"></i> Hapus</a>';
                                        echo '</div>'; // <--- Tutup DIV BARU

                                        echo '<div class="report-content">'.nl2br(htmlspecialchars($t['tanggapan'])).'</div>'; // Ini adalah isi tanggapan
                                        echo '<div class="news-date"><i class="fas fa-clock"></i> Dikirim: '.$t['tanggal'].'</div>';

                                        // Form untuk edit tanggapan (textarea dan tombol)
                                        // Pindahkan textarea edit dan tombolnya ke sini, di bawah konten tanggapan
                                         // <--- DIV BARU UNTUK FORM EDIT DI BAWAH
                                        
                                        echo '</div>'; // <--- Tutup DIV BARU

                                        // ====================================================================

                                        echo '</div>'; // Tutup div .response-section (ini yang membungkus satu tanggapan)
                                    }
                                }
// ... (lanjutkan dengan form kirim tanggapan baru) ...

// **Penting: Revisi form kirim tanggapan baru di bagian bawah**
// Pastikan textarea pengiriman tanggapan admin (yang belum dikirim) juga dilebarkan

// ... (lanjutkan dengan form kirim tanggapan baru dan action-buttons laporan) ...
                    // Form kirim tanggapan baru SELALU tampil di bawah daftar tanggapan
                    echo '<div class="response-form">';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="id_pengaduan" value="'.$id_pengaduan.'">';
                    echo '<div class="form-group">';
                    echo '<label class="form-label">Tanggapan Admin</label>';
                    echo '<textarea class="form-input form-textarea" name="tanggapan" placeholder="Tulis tanggapan admin..." required></textarea>';
                    echo '</div>';
                    echo '<button type="submit" name="kirim_tanggapan" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Tanggapan</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '<div class="action-buttons">';
                    // Hilangkan tombol Edit di sini
                    // echo '<a href="edit_laporan_admin.php?id='.$row['id'].'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>';
                    echo '<a href="hapus_laporan_admin.php?id='.$row['id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus laporan ini?\')"><i class="fas fa-trash"></i> Hapus</a>';
                    echo '</div>';
                    echo '</div>';
                endwhile;
            else:
                echo '<p>Tidak ada laporan.</p>';
            endif;
            ?>
        </div>

        <!-- Upload Berita Section -->
        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-newspaper"></i>
                Upload Berita/Dokumen Desa
            </h3>
            
            <div class="alert alert-success" style="display: none;" id="uploadAlert">
                Upload berhasil!
            </div>
            
            <form id="beritaForm" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" class="form-input" name="judul_berita" required placeholder="Masukkan judul berita">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-input form-textarea" name="deskripsi_berita" placeholder="Masukkan deskripsi berita"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Pilih File</label>
                    <input type="file" class="form-input" name="berita_file" required accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip,.rar">
                    <small style="color: #6b7280; font-size: 0.9rem;">File gambar, PDF, DOC, ZIP, max 10MB.</small>
                </div>
                
                <button type="submit" name="upload_berita" class="btn btn-primary">
                    <i class="fas fa-upload"></i>
                    Upload Berita
                </button>
            </form>
        </div>
        <!-- END Upload Berita Section -->
        
        <!-- Daftar Berita Section -->
        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-list"></i>
                Daftar Berita/Dokumen Desa
            </h3>
            <?php
            // Proses edit berita
            if (isset($_POST['edit_berita_id_admin'])) {
                $id = intval($_POST['edit_berita_id_admin']);
                $judul = $_POST['edit_judul_admin'];
                $deskripsi = $_POST['edit_deskripsi_admin'];
                $stmt = $conn->prepare("UPDATE berita_file SET judul=?, deskripsi=? WHERE id=?");
                $stmt->bind_param("ssi", $judul, $deskripsi, $id);
                $stmt->execute();
                $stmt->close();
                echo '<div class="alert alert-success">Berhasil update berita.</div>';
            }
            // Proses hapus berita
            if (isset($_GET['hapus_berita_id_admin'])) {
                $id = intval($_GET['hapus_berita_id_admin']);
                $res = $conn->query("SELECT path_file FROM berita_file WHERE id=$id");
                $row = $res ? $res->fetch_assoc() : null;
                if ($row && file_exists('../'.$row['path_file'])) unlink('../'.$row['path_file']);
                $conn->query("DELETE FROM berita_file WHERE id=$id");
                echo '<div class="alert alert-success">Berita berhasil dihapus.</div>';
            }
            // Proses upload berita/dokumen (PASTIKAN INI DI ATAS QUERY SELECT DAN FORM)
            if (isset($_POST['upload_berita'])) {
                $judul = trim($_POST['judul_berita']);
                $deskripsi = trim($_POST['deskripsi_berita']);
                $tanggal_upload = date('Y-m-d H:i:s');
                $file = $_FILES['berita_file'];
                $allowed_ext = ['jpg','jpeg','png','gif','pdf','doc','docx','zip','rar'];
                $max_size = 10 * 1024 * 1024; // 10MB
                $upload_dir = '../assets/berita_uploads/';
                $nama_file = basename($file['name']);
                $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
                $path_file = 'assets/berita_uploads/' . uniqid('berita_') . '.' . $ext;
                $target_file = '../' . $path_file;
                if (!in_array($ext, $allowed_ext)) {
                    echo '<div class="alert alert-error">Ekstensi file tidak didukung.</div>';
                } elseif ($file['size'] > $max_size) {
                    echo '<div class="alert alert-error">Ukuran file terlalu besar (max 10MB).</div>';
                } elseif (move_uploaded_file($file['tmp_name'], $target_file)) {
                    $stmt = $conn->prepare("INSERT INTO berita_file (judul, deskripsi, nama_file, path_file, tanggal_upload) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $judul, $deskripsi, $nama_file, $path_file, $tanggal_upload);
                    if ($stmt->execute()) {
                        echo '<div class="alert alert-success">Berita berhasil diupload.</div>';
                    } else {
                        echo '<div class="alert alert-error">Gagal upload ke database.</div>';
                    }
                    $stmt->close();
                } else {
                    echo '<div class="alert alert-error">Gagal upload file.</div>';
                }
            }
            $res_berita = $conn->query("SELECT * FROM berita_file ORDER BY tanggal_upload DESC");
            if ($res_berita && $res_berita->num_rows > 0) {
                echo '<div class="news-grid">';
                while ($b = $res_berita->fetch_assoc()) {
                    $is_img = preg_match('/\.(jpg|jpeg|png|gif)$/i', $b['nama_file']);
                    echo '<div class="news-card">';
                    if ($is_img) {
                        echo '<img src="../'.htmlspecialchars($b['path_file']).'" alt="Berita" class="news-image">';
                    } else {
                        echo '<img src="../assets/img/gotong royong.jpg" alt="File" class="news-image">';
                    }
                    echo '<div class="news-content">';
                    echo '<form class="berita-edit-form" method="post">';
                    echo '<input type="hidden" name="edit_berita_id_admin" value="' . $b['id'] . '">';
                    echo '<div class="form-group">';
                    echo '<input type="text" class="form-input" name="edit_judul_admin" value="' . htmlspecialchars($b['judul'] ?? $b['nama_file']) . '" placeholder="Judul" readonly>';
                    echo '</div>';
                    echo '<div class="form-group">';
                    echo '<textarea class="form-input form-textarea" name="edit_deskripsi_admin" placeholder="Deskripsi" readonly>' . htmlspecialchars($b['deskripsi'] ?? '') . '</textarea>';
                    echo '</div>';
                    echo '<div class="action-buttons">';
                    // Hapus tombol Simpan/Edit
                    echo ($is_img)
                        ? '<a href="../'.htmlspecialchars($b['path_file']).'" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-eye"></i> Lihat</a>'
                        : '<a href="../'.htmlspecialchars($b['path_file']).'" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-eye"></i> Download</a>';
                    echo '<a href="?hapus_berita_id_admin=' . $b['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus berita ini?\')"><i class="fas fa-trash"></i> Hapus</a>';
                    echo '</div>';
                    echo '</form>';
                    echo '<div class="news-date">Upload: '.$b['tanggal_upload'].'</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div style="color:#888;">Belum ada berita/dokumen yang diupload.</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>