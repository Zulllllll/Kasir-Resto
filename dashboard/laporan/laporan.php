<?php
include '../../session_check.php';
include '../../config/db.php';

if (
    !isset($_SESSION['role']) ||
    !in_array($_SESSION['role'], ['owner', 'admin', 'waiter', 'kasir'])
) {
    header("Location: ../../login.php");
    exit;
}

// Filter tanggal dan nama pelanggan
$tanggal_awal = $_GET['awal'] ?? date('Y-m-01');
$tanggal_akhir = $_GET['akhir'] ?? date('Y-m-d');
$filter_pelanggan = $_GET['pelanggan'] ?? '';

$query = "
    SELECT t.idtransaksi, p.idpesanan, u.namauser AS kasir, t.total, t.bayar, t.kembalian, t.tanggal, m.nomormeja, pel.namapelanggan
    FROM transaksi t
    JOIN pesanan p ON t.idpesanan = p.idpesanan
    JOIN user u ON t.iduser = u.iduser
    JOIN meja m ON p.idmeja = m.idmeja
    JOIN pelanggan pel ON p.idpelanggan = pel.idpelanggan
    WHERE DATE(t.tanggal) BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
";

if (!empty($filter_pelanggan)) {
    $query .= " AND pel.namapelanggan LIKE '%$filter_pelanggan%'";
}

$query .= " ORDER BY t.tanggal DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #FFD700;
        }

        .container {
            background-color: #1f1f1f;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .table thead {
            background-color: #333333;
            color: #FFD700;
        }

        .table tfoot {
            background-color: #2c2c2c;
            font-weight: bold;
            color: #FFD700;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        .btn-success {
            background-color: #198754;
            border: none;
        }

        .btn-success:hover {
            background-color: #157347;
        }

        h3 {
            color: #ffbb00;
            font-weight: bold;
        }

        .form-label {
            font-weight: 500;
            color: #ffbb00;
        }

        .table-striped tbody tr:hover {
            background-color: #444444;
        }

        .table-responsive {
            margin-top: 20px;
        }

        .form-inline .form-control {
            width: auto;
            display: inline-block;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h3 class="mb-4 fw-semibold text-center"> Laporan Transaksi</h3>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="awal" class="form-label">Dari Tanggal</label>
                <input type="date" name="awal" id="awal" class="form-control" value="<?= $tanggal_awal ?>">
            </div>
            <div class="col-md-4">
                <label for="akhir" class="form-label">Sampai Tanggal</label>
                <input type="date" name="akhir" id="akhir" class="form-control" value="<?= $tanggal_akhir ?>">
            </div>
            <div class="col-md-4">
                <label for="pelanggan" class="form-label">Nama Pelanggan</label>
                <input type="text" name="pelanggan" id="pelanggan" class="form-control" placeholder="Contoh: Asep" value="<?= htmlspecialchars($filter_pelanggan) ?>">
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary me-2">🔍 Tampilkan</button>
                <a href="../laporan/cetak_laporan.php?tanggal_awal=<?= $tanggal_awal ?>&tanggal_akhir=<?= $tanggal_akhir ?>&pelanggan=<?= urlencode($filter_pelanggan) ?>" target="_blank"
                    class="btn btn-success">🖨️ Cetak PDF</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
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
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>$no</td>
                                <td>{$row['tanggal']}</td>
                                <td>{$row['nomormeja']}</td>
                                <td>{$row['namapelanggan']}</td>
                                <td>{$row['kasir']}</td>
                                <td>Rp" . number_format($row['total'], 0, ',', '.') . "</td>
                                <td>Rp" . number_format($row['bayar'], 0, ',', '.') . "</td>
                                <td>Rp" . number_format($row['kembalian'], 0, ',', '.') . "</td>
                              </tr>";
                        $grandTotal += $row['total'];
                        $no++;
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Grand Total</th>
                        <th colspan="3">Rp<?= number_format($grandTotal, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4 text-end">
            <?php
            $dashboard = "../owner.php";
            if ($_SESSION['role'] == 'waiter') {
                $dashboard = "../waiter.php";
            } elseif ($_SESSION['role'] == 'kasir') {
                $dashboard = "../kasir.php";
            } elseif ($_SESSION['role'] == 'admin') {
                $dashboard = "../admin.php";
            }
            ?>
            <a href="<?= $dashboard ?>" class="btn btn-secondary">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</body>

</html>
