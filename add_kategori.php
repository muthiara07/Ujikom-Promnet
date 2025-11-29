<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user'])) header('Location: login.php');

$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kategori'] ?? '');
    if($nama === '') $error = 'Nama kategori tidak boleh kosong.';
    else {
        $stmt = $conn->prepare('INSERT INTO kategori (nama_kategori) VALUES (?)');
        $stmt->bind_param('s',$nama);
        if($stmt->execute()) header('Location: kategori.php');
        else $error = 'Gagal menyimpan kategori (mungkin duplikat).';
    }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Tambah Kategori</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h2>Tambah Kategori</h2>
    <?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
        <label>Nama Kategori</label>
        <input name="nama_kategori" required>
        <button class="btn" type="submit">Simpan</button>
        <a class="btn" href="kategori.php">Batal</a>
    </form>
</div>
</body></html>
