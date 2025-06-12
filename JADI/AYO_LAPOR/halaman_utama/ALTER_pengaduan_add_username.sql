-- Tambahkan kolom username pada tabel pengaduan jika belum ada
ALTER TABLE pengaduan ADD COLUMN username VARCHAR(50) NOT NULL AFTER id;
