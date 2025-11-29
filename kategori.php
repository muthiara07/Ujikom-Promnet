<?php
session_start();
require 'config.php';
if(!isset($_SESSION['user'])) header('Location: login.php');

// search & pagination for kategori
$q = isset($_GET['q'])? trim($_GET['q']):'';
$limit = 5;
$page = isset($_GET['page'])? max(1,(int)$_GET['page']):1;
$offset = ($page-1)*$limit;

if($q!=='') {
    $like = "%{$q}%";
    $stmt = $conn->prepare('SELECT SQL_CALC_FOUND_ROWS * FROM kategori WHERE nama_kategori LIKE ? ORDER BY tanggal_input DESC LIMIT ? OFFSET ?');
    $stmt->bind_param('sii',$like,$limit,$offset);
} else {
    $stmt = $conn->prepare('SELECT SQL_CALC_FOUND_ROWS * FROM kategori ORDER BY tanggal_input DESC LIMIT ? OFFSET ?');
    $stmt->bind_param('ii',$limit,$offset);
}
$stmt->execute();
$res = $stmt->get_result();
$total = $conn->query('SELECT FOUND_ROWS() as total')->fetch_assoc()['total'];
$pages = (int)ceil($total/$limit);
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>SIMBS - Kategori</title><link rel="stylesheet" href="style.css"></head>
<body>
    <div class="navbar">SIMBS | <a href="index.php">Buku</a> | <a href="kategori.php">Kategori</a> <span class="nav-right">Hai, <?=htmlspecialchars($_SESSION['user'])?> | <a href="logout.php">Logout</a></span></div>
    <div class="container">
        <h1>Daftar Kategori</h1>
        <div class="controls">
            <a class="btn" href="add_kategori.php">Tambah Kategori</a>
            <form class="search" method="get" action="kategori.php">
                <input type="text" name="q" placeholder="Cari kategori..." value="<?=htmlspecialchars($q)?>">
                <button type="submit">Cari</button>
            </form>
        </div>
        <table>
            <thead><tr><th>No.</th><th>Nama Kategori</th><th>Tanggal Input</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php $no=$offset+1; while($r=$res->fetch_assoc()): ?>
                <tr class="<?=($no%2==0)?'alt':''?>">
                    <td><?=$no++?></td>
                    <td><?=htmlspecialchars($r['nama_kategori'])?></td>
                    <td><?=htmlspecialchars($r['tanggal_input'])?></td>
                    <td>
                        <a class="btn small" href="edit_kategori.php?id=<?=$r['id']?>">Edit</a>
                        <a class="btn small red" href="delete_kategori.php?id=<?=$r['id']?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php for($p=1;$p<=$pages;$p++): ?>
                <a class="page <?=($p==$page)?'active':''?>" href="?page=<?=$p?>&q=<?=urlencode($q)?>"><?=$p?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
