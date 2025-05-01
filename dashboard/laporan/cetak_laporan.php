<?php
include '../../config/db.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$pelanggan = $_GET['pelanggan'] ?? '';

$query = "
    SELECT t.idtransaksi, p.idpesanan, u.namauser AS kasir, t.total, t.bayar, t.kembalian, t.tanggal, m.nomormeja, pel.namapelanggan
    FROM transaksi t
    JOIN pesanan p ON t.idpesanan = p.idpesanan
    JOIN user u ON t.iduser = u.iduser
    JOIN meja m ON p.idmeja = m.idmeja
    JOIN pelanggan pel ON p.idpelanggan = pel.idpelanggan
    WHERE DATE(t.tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
";

// Tambahkan filter pelanggan jika ada
if (!empty($pelanggan)) {
    $query .= " AND pel.namapelanggan LIKE '%$pelanggan%'";
}

$query .= " ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body { background-color: #f9f9f9; } 
        h2, h4 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #ddd; }
        @media print {
            .no-print { display: none; }
        }
        .container {
            position: relative;
            margin-bottom: 60px; /* Memberikan sedikit ruang di bawah tabel untuk tombol */
        }
        .print-btn {
            position: absolute;
            right: 20px;
            top: 100%; /* Menempatkan tombol tepat di bawah tabel */
            margin-top: 10px; /* Menambah jarak antara tombol dan tabel */
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>LAPORAN TRANSAKSI RESTORAN</h2>
        <h4>Periode: <?= htmlspecialchars($tanggal_awal) ?> s.d. <?= htmlspecialchars($tanggal_akhir) ?></h4>
        <?php if (!empty($pelanggan)) : ?>
            <h4>Filter Pelanggan: <?= htmlspecialchars($pelanggan) ?></h4>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>No</th>
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
                <?php
                $no = 1;
                $grandTotal = 0;
                while ($row = mysqli_fetch_assoc($result)) :
                    $grandTotal += $row['total'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['nomormeja'] ?></td>
                    <td><?= $row['namapelanggan'] ?></td>
                    <td><?= $row['kasir'] ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($row['bayar'], 0, ',', '.') ?></td>
                    <td>Rp<?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="5" style="text-align:right;"><strong>Grand Total:</strong></td>
                    <td colspan="3"><strong>Rp<?= number_format($grandTotal, 0, ',', '.') ?></strong></td>
                </tr>
            </tbody>
        </table>

        <button onclick="window.print()" class="print-btn no-print">Cetak Laporan</button>
    </div>
</body>
</html>
