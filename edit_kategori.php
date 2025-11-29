<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user'])) header('Location: login.php');
$id = isset($_GET['id'])? (int)$_GET['id']:0;
$stmt = $conn->prepare('SELECT * FROM kategori WHERE id=? LIMIT 1');
$stmt->bind_param('i',$id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if(!$row) { header('Location: kategori.php'); exit; }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST') {
    $nama = trim($_POST['nama_kategori'] ?? '');
    if($nama==='') $error='Nama kategori tidak boleh kosong.';
    else {
        $stmt2 = $conn->prepare('UPDATE kategori SET nama_kategori=? WHERE id=?');
        $stmt2->bind_param('si',$nama,$id);
        if($stmt2->execute()) header('Location: kategori.php');
        else $error='Gagal mengubah kategori.';
    }
}
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Edit Kategori</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="container">
    <h2>Edit Kategori</h2>
    <?php if($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
        <label>Nama Kategori</label>
        <input name="nama_kategori" value="<?=htmlspecialchars($row['nama_kategori'])?>" required>
        <button class="btn" type="submit">Simpan</button>
        <a class="btn" href="kategori.php">Batal</a>
    </form>
</div>
</body></html>
