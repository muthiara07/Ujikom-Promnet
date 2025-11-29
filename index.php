<?php
session_start();
require 'config.php';

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// PAGINATION
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// SEARCH (opsional)
$search = isset($_GET['q']) ? trim($_GET['q']) : "";
if ($search !== "") {
    $like = "%".$search."%";
    $stmt = $conn->prepare("
        SELECT SQL_CALC_FOUND_ROWS b.*, k.nama_kategori 
        FROM buku b 
        LEFT JOIN kategori k ON b.kategori_id = k.id
        WHERE b.judul LIKE ? OR b.penulis LIKE ?
        ORDER BY b.tanggal_input DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ssii", $like, $like, $limit, $offset);
} else {
    $stmt = $conn->prepare("
        SELECT SQL_CALC_FOUND_ROWS b.*, k.nama_kategori 
        FROM buku b 
        LEFT JOIN kategori k ON b.kategori_id = k.id
        ORDER BY b.tanggal_input DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

$total = $conn->query("SELECT FOUND_ROWS() AS total")->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SIMBS - Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    SIMBS | <a href="index.php">Buku</a> | <a href="kategori.php">Kategori</a>
    <span class="nav-right">Hai, <?= htmlspecialchars($_SESSION['user']) ?> | <a href="logout.php">Logout</a></span>
</div>

<div class="container">

<h1>Data Buku</h1>

<!-- TOMBOL + SEARCH -->
<div class="controls">
    <a class="btn" href="add_buku.php">Tambah Buku</a>

    <form class="search" method="get" action="">
        <input type="text" name="q" placeholder="Cari buku..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>
</div>

<!-- TABEL -->
<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Sampul</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    <?php 
    $no = $offset + 1; 
    while ($row = $result->fetch_assoc()): 
    ?>
        <tr class="<?= ($no % 2 == 0) ? 'alt' : '' ?>">
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['judul']) ?></td>
            <td><?= htmlspecialchars($row['penulis']) ?></td>
            <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
            <td>
                <?php if ($row['sampul'] && file_exists("uploads/".$row['sampul'])): ?>
                    <img src="uploads/<?= $row['sampul'] ?>" width="60">
                <?php else: ?>
                    Tidak ada foto
                <?php endif; ?>
            </td>
            <td>
                <a class="btn small green" href="edit_buku.php?id=<?= $row['id'] ?>">Edit</a>
                <a class="btn small red" href="delete_buku.php?id=<?= $row['id'] ?>" 
                   onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

<!-- PAGINATION -->
<div class="pagination">
    <?php for ($p = 1; $p <= $total_pages; $p++): ?>
        <a class="page <?= ($p == $page) ? 'active' : '' ?>" 
           href="?page=<?= $p ?>&q=<?= urlencode($search) ?>"><?= $p ?></a>
    <?php endfor; ?>
</div>

</div>

</body>
</html>
