SIMBS - Sistem Informasi Manajemen Buku Sederhana
------------------------------------------------
Petunjuk singkat menjalankan project ini di mesin lokal (XAMPP/MAMP/LAMP):

1. Ekstrak folder ini ke folder webserver (misal htdocs/simbs_project_simbs).
2. Buat database MySQL: simbs
3. Import file create_tables.sql ke database (phpMyAdmin atau mysql < create_tables.sql).
   File akan membuat tabel users, kategori, buku dan memasukkan beberapa contoh data.
4. Pastikan folder 'uploads/' memiliki permission write agar file sampul bisa diupload.
5. Sesuaikan koneksi di config.php bila username/password MySQL berbeda.
6. Buka: http://localhost/simbs_project_simbs/login.php
   Contoh akun: username: admin  password: admin12345
7. Fitur yang tersedia sesuai deskripsi uji kompetensi:
   - Halaman Buku: daftar buku (urutan tanggal_input DESC), tambah/edit/hapus, paginasi
   - Halaman Kategori: daftar kategori, tambah/edit/hapus, search, paginasi
   - Register & Login: validasi register (duplikat username/email, password >=8), login memberi pesan 'username salah' atau 'salah password'
