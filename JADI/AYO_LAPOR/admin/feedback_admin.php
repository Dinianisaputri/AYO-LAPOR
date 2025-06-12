<?php
// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'pengaduan');
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Proses tambah feedback
$feedback_message = '';
if (isset($_POST['kirim_feedback'])) {
    $id_pengaduan = intval($_POST['id_pengaduan']);
    $feedback = trim($_POST['feedback']);
    $tanggal = date('Y-m-d H:i:s');
    if ($feedback !== '') {
        $stmt = $conn->prepare('INSERT INTO feedback_admin (id_pengaduan, feedback, tanggal) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $id_pengaduan, $feedback, $tanggal);
        if ($stmt->execute()) {
            $feedback_message = '<div class="alert alert-success">Feedback berhasil dikirim.</div>';
        } else {
            $feedback_message = '<div class="alert alert-error">Gagal mengirim feedback.</div>';
        }
        $stmt->close();
    }
}
// Proses edit feedback
if (isset($_POST['edit_feedback_id'])) {
    $id = intval($_POST['edit_feedback_id']);
    $feedback = trim($_POST['edit_feedback']);
    $stmt = $conn->prepare('UPDATE feedback_admin SET feedback=? WHERE id=?');
    $stmt->bind_param('si', $feedback, $id);
    if ($stmt->execute()) {
        $feedback_message = '<div class="alert alert-success">Feedback berhasil diupdate.</div>';
    } else {
        $feedback_message = '<div class="alert alert-error">Gagal update feedback.</div>';
    }
    $stmt->close();
}
// Proses hapus feedback
if (isset($_GET['hapus_feedback_id'])) {
    $id = intval($_GET['hapus_feedback_id']);
    $conn->query('DELETE FROM feedback_admin WHERE id=' . $id);
    $feedback_message = '<div class="alert alert-success">Feedback berhasil dihapus.</div>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Feedback Admin</title>
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .feedback-list {margin-top:30px;}
        .feedback-card {background:#fff;border-radius:12px;box-shadow:0 2px 8px #e0e7ef;padding:18px 20px;margin-bottom:18px;}
        .feedback-meta {font-size:13px;color:#888;margin-bottom:8px;}
        .feedback-actions {margin-top:10px;}
        .feedback-actions .btn {margin-right:8px;}
        .feedback-form {margin-bottom:30px;}
    </style>
</head>
<body>
<div class="container">
    <h2>Feedback Admin ke User</h2>
    <?php if ($feedback_message) echo $feedback_message; ?>
    <!-- Form kirim feedback -->
    <form class="feedback-form" method="post">
        <label>Pilih Laporan:</label>
        <select name="id_pengaduan" required style="margin-bottom:10px;">
            <option value="">-- Pilih Laporan --</option>
            <?php
            $res = $conn->query('SELECT id, judul FROM pengaduan ORDER BY tanggal DESC');
            while ($row = $res->fetch_assoc()) {
                echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['judul']).'</option>';
            }
            ?>
        </select><br>
        <textarea name="feedback" rows="3" placeholder="Tulis feedback untuk user..." required style="width:100%;margin-bottom:10px;"></textarea>
        <button type="submit" name="kirim_feedback" class="btn btn-success">Kirim Feedback</button>
    </form>
    <!-- Daftar feedback -->
    <div class="feedback-list">
        <h3>Daftar Feedback</h3>
        <?php
        $res = $conn->query('SELECT f.*, p.judul FROM feedback_admin f LEFT JOIN pengaduan p ON f.id_pengaduan=p.id ORDER BY f.tanggal DESC');
        if ($res && $res->num_rows > 0) {
            while ($f = $res->fetch_assoc()) {
                echo '<div class="feedback-card">';
                echo '<div class="feedback-meta">Laporan: <strong>'.htmlspecialchars($f['judul']).'</strong> | Tanggal: '.$f['tanggal'].'</div>';
                // Form edit feedback
                echo '<form method="post" style="margin-bottom:8px;">';
                echo '<input type="hidden" name="edit_feedback_id" value="'.$f['id'].'">';
                echo '<textarea name="edit_feedback" rows="2" style="width:100%;margin-bottom:6px;">'.htmlspecialchars($f['feedback']).'</textarea>';
                echo '<button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Simpan</button>';
                echo '<a href="?hapus_feedback_id='.$f['id'].'" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus feedback ini?\')"><i class="fas fa-trash"></i> Hapus</a>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<div style="color:#888;">Belum ada feedback.</div>';
        }
        ?>
    </div>
</div>
</body>
</html>
