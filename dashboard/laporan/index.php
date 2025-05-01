<?php
include '../../session_check.php';
include '../../config/db.php';

if ($_SESSION['role'] != 'owner') {
    header("Location: ../../login/index.php");
    exit();
}

// Ambil data transaksi dari database
$query = "
    SELECT t.idtransaksi, p.idpesanan, u.nama AS kasir, t.total, t.bayar, t.kembalian, t.created_at, m.nomormeja, pel.namapelanggan
    FROM transaksi t
    JOIN pesanan p ON t.idpesanan = p.idpesanan
    JOIN user u ON t.iduser = u.iduser
    JOIN meja m ON p.idmeja = m.idmeja
    JOIN pelanggan pel ON p.idpelanggan = pel.idpelanggan
    ORDER BY t.created_at DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Laporan Transaksi</h2>
    <a href="../dashboard/owner.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Meja</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['idtransaksi'] ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                    <td><?= $row['nomormeja'] ?></td>
                    <td><?= $row['namapelanggan'] ?></td>
                    <td><?= $row['kasir'] ?></td>
                    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['bayar'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
