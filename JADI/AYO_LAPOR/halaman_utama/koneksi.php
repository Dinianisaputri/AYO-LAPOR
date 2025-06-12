<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pengaduan";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Alias agar kompatibel dengan file lain
$conn = $koneksi;

// Proses data POST jika ada


    // Anda bisa menambahkan kode untuk menyimpan data ke database di sini
    // Menggunakan variabel $koneksi untuk menjalankan query
    // Contoh:
    // $sql = "INSERT INTO pengaduan (judul, isi, tanggal, lokasi, kategori) VALUES (?, ?, ?, ?, ?)";
    // $stmt = $koneksi->prepare($sql);
    // ... dan seterusnya ...
// Anda bisa menambahkan kode lain di sini yang memerlukan koneksi database
// Menggunakan variabel $koneksi
// Contoh:
// $result = $koneksi->query("SELECT * FROM pengaduan");

?>