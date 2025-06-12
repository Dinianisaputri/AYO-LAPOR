<?php
$conn = new mysqli('localhost', 'root', '', 'pengaduan');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data tanggapan lama
$result = $conn->query("SELECT * FROM tanggapan WHERE id = $id");
$data = $result ? $result->fetch_assoc() : null;

// Proses update tanggapan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $tanggapan = $_POST['tanggapan'];
    $tanggal = $_POST['tanggal'];
    $conn->query("UPDATE tanggapan SET tanggapan='$tanggapan', tanggal='$tanggal' WHERE id = $id");
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Tanggapan Admin</title>
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

<h2>Edit Tanggapan Admin</h2>
<?php if (!$data): ?>
    <div style="color:red; font-weight:bold;">Tanggapan tidak ditemukan.</div>
<?php else: ?>
<form method="post">
    Tanggapan:
    <textarea name="tanggapan" required><?= htmlspecialchars($data['tanggapan']) ?></textarea>
    Tanggal:
    <input type="datetime-local" name="tanggal" value="<?= date('Y-m-d\TH:i', strtotime($data['tanggal'])) ?>" required>
    <button type="submit" name="update">Simpan Perubahan</button>
    
</form>
<?php endif; ?>

</body>
</html>
