<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'waiter') {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "ID tidak ditemukan.";
    exit;
}

// Ambil data pesanan
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pesanan WHERE idpesanan = $id"));
$detail = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE idpesanan = $id");
$menu = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Edit Pesanan #<?= $id ?></h3>

    <form action="proses_edit_pesanan.php" method="POST">
        <input type="hidden" name="idpesanan" value="<?= $id ?>">

        <div class="mb-3">
            <label class="form-label">Pilih Menu</label>
            <select name="idmenu" class="form-select">
                <?php while ($m = mysqli_fetch_assoc($menu)): ?>
                    <option value="<?= $m['idmenu'] ?>"><?= $m['namamenu'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
