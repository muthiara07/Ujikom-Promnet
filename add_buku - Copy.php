<?php
session_start();
require 'config.php';

$error = "";

// Ambil kategori
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $kategori_id = $_POST['kategori_id'];

    // Upload foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
    }

    $stmt = $conn->prepare("INSERT INTO buku (judul, penulis, kategori_id, sampul) VALUES (?,?,?,?)");
    $stmt->bind_param("ssis", $judul, $penulis, $kategori_id, $foto);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Gagal menyimpan data.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2>Tambah Buku</h2>

<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Judul Buku</label>
    <input type="text" name="judul" required>

    <label>Penulis</label>
    <input type="text" name="penulis" required>

    <label>Kategori</label>
    <select name="kategori_id" required>
        <option value="">--Pilih Kategori--</option>
        <?php while($k = $kategori->fetch_assoc()): ?>
            <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Foto Sampul</label>
    <input type="file" name="foto" accept="image/*">

    <button class="btn" type="submit">Simpan</button>
    <a href="index.php" class="btn red">Batal</a>
</form>

</div>
</body>
</html>
