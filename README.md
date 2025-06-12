Berikut versi penjelasan **lebih panjang dan lengkap** dari deskripsi proyek **💬 AYO\_LAPOR – Website Layanan Pengaduan Masyarakat**:

---

💬 AYO\_LAPOR – Website Layanan Pengaduan Masyarakat

**AYO\_LAPOR** merupakan sebuah aplikasi berbasis web yang dirancang untuk memberikan sarana pengaduan masyarakat secara digital. Aplikasi ini memungkinkan masyarakat menyampaikan keluhan, saran, atau laporan masalah yang mereka alami di lingkungan sekitar secara **cepat, mudah, dan transparan**, tanpa harus datang langsung ke kantor pemerintahan atau instansi terkait.

Website ini dibangun menggunakan bahasa pemrograman **PHP** secara native (tanpa framework), dan memanfaatkan **MySQL** sebagai sistem manajemen basis datanya. Dengan interface yang sederhana dan mudah digunakan, AYO\_LAPOR dirancang untuk dapat digunakan oleh semua kalangan, baik masyarakat umum maupun pihak admin/petugas instansi.

Tujuan utama dari aplikasi ini adalah untuk:

* Meningkatkan **keterbukaan informasi publik**.
* Mempercepat **penanganan pengaduan masyarakat**.
* Mendorong **partisipasi aktif masyarakat** dalam proses pengawasan dan pembangunan daerah.
* Mempermudah pihak instansi dalam **mengelola laporan secara terorganisir dan terdokumentasi**.

---

### 🎯 Fitur Utama

#### 📝 Formulir Pengaduan Online

Pengguna (masyarakat) dapat membuat pengaduan melalui form yang disediakan di website. Form ini biasanya mencakup informasi seperti nama pelapor, lokasi kejadian, deskripsi pengaduan, serta opsi untuk mengunggah bukti berupa gambar atau dokumen pendukung.

#### 📂 Manajemen Data Pengaduan

Admin atau petugas instansi memiliki akses ke panel manajemen yang memungkinkan mereka untuk melihat daftar pengaduan yang masuk, melakukan verifikasi, memberikan respon, serta mengubah status pengaduan sesuai dengan proses penanganan yang sedang berlangsung.

#### 🔍 Status Pelaporan

Setiap pengaduan yang dikirimkan akan diberikan status seperti:

* **Menunggu Verifikasi**
* **Diproses**
* **Selesai**

Pelapor dapat masuk ke sistem untuk memantau perkembangan laporan mereka secara real-time, sehingga meningkatkan rasa percaya terhadap sistem layanan publik.

#### 🔐 Login Multi-Role

Sistem mendukung autentikasi login dengan peran (role) berbeda, seperti:

* **User (Masyarakat Umum)** – hanya bisa mengirim pengaduan dan memantau status.
* **Admin/Petugas** – dapat memverifikasi, menanggapi, dan mengelola laporan.

---

### 🛠️ Teknologi yang Digunakan

* **Frontend**:
  Dibangun menggunakan kombinasi **HTML**, **CSS**, dan **PHP** untuk menampilkan halaman web yang dinamis dan responsif terhadap input pengguna.

* **Backend**:
  Menggunakan **PHP Native**, tanpa framework eksternal. Seluruh logika pengolahan data, autentikasi, dan interaksi database ditulis menggunakan kode PHP murni.

* **Database**:
  Basis data disimpan menggunakan **MySQL**, dengan struktur tabel yang mencakup data pengguna, pengaduan, tanggapan, dan status laporan. Tersedia juga file `.sql` yang dapat diimpor langsung melalui phpMyAdmin.

* **Web Server**:
  Website dijalankan secara lokal menggunakan **XAMPP** yang menyediakan Apache sebagai server dan MySQL sebagai DBMS.

---

### 📌 Manfaat Aplikasi

Dengan adanya sistem **AYO\_LAPOR**, proses pelaporan dan tindak lanjut terhadap masalah publik menjadi lebih efisien, terdokumentasi, dan mudah dipantau. Hal ini diharapkan dapat membangun keterlibatan masyarakat dalam pemerintahan yang lebih terbuka dan akuntabel, serta mendukung digitalisasi layanan publik di era modern.

---
📁 Struktur Direktori AYO_LAPOR
AYO_LAPOR/
├── admin/              ← Folder untuk halaman admin (dashboard, verifikasi pengaduan, dll)
├── assets/             ← Folder berisi file statis seperti CSS, JS, gambar/icon
├── daftar/             ← Folder untuk halaman pendaftaran akun atau pengguna baru
├── halaman_utama/      ← Folder halaman utama yang mungkin menampilkan daftar pengaduan atau informasi publik
├── login/              ← Folder untuk halaman login pengguna dan admin
├── berita.php          ← Halaman untuk menampilkan berita atau informasi terkini
├── index.php           ← Halaman utama yang diakses saat membuka website (landing page)
├── pengaduan.sql       ← File dump database MySQL yang berisi struktur dan data awal
├── pilihan.html        ← Halaman pilihan awal (misalnya memilih login sebagai user/admin)
└── tentang.html        ← Halaman statis tentang informasi aplikasi AYO_LAPOR

