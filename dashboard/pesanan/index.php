<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['waiter', 'kasir'])) {
    header("Location: ../../login.php");
    exit;
}

$query = "
    SELECT 
        p.idpesanan, p.tanggal, m.nomormeja, m.namameja, 
        pel.namapelanggan, pel.nohp, 
        d.jumlah, mn.namamenu 
    FROM pesanan p
    JOIN meja m ON p.idmeja = m.idmeja
    JOIN pelanggan pel ON p.idpelanggan = pel.idpelanggan
    JOIN detail_pesanan d ON p.idpesanan = d.idpesanan
    JOIN menu mn ON d.idmenu = mn.idmenu
    ORDER BY p.idpesanan DESC
";

$result = mysqli_query($conn, $query);
$pesanan = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pesanan[$row['idpesanan']]['tanggal'] = $row['tanggal'];
    $pesanan[$row['idpesanan']]['meja'] = $row['nomormeja'] . " - " . $row['namameja'];
    $pesanan[$row['idpesanan']]['pelanggan'] = $row['namapelanggan'] . " (" . $row['nohp'] . ")";
    $pesanan[$row['idpesanan']]['menu'][] = [
        'namamenu' => $row['namamenu'],
        'jumlah' => $row['jumlah']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
            background-color: #212121;
            color: #fff;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(135deg, #1e1e1e, #343a40);
            padding-top: 60px;
            box-shadow: 2px 0px 10px rgba(0, 0, 0, 0.3);
            transition: width 0.3s, background-color 0.3s;
        }

        .sidebar a {
            color: #fff;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 16px;
            border-bottom: 1px solid #444;
            transition: background-color 0.3s, padding-left 0.3s, transform 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.bg-dark {
            background-color: #495057;
            padding-left: 30px;
            transform: translateX(5px);
            color: #f8f9fa;
        }

        .sidebar a .mdi, 
        .sidebar a .material-icons {
            margin-right: 10px;
        }

        .content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 30px;
            transition: margin-left 0.3s;
        }

        .custom-navbar {
            background: linear-gradient(to right, #ffbb00, #ff8800);
            color: #1e1e1e;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .custom-navbar .navbar-brand, 
        .custom-navbar .btn-outline-light {
            color: #1e1e1e;
            font-weight: bold;
        }

        .custom-navbar .btn-outline-light {
            border: 1px solid #1e1e1e;
        }

        .custom-navbar .btn-outline-light:hover {
            background-color: #1e1e1e;
            color: #ffbb00;
            border: 1px solid #ffbb00;
        }

        .custom-navbar .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            margin-left: 20px;  
            font-size: 24px;   
            font-weight: bold; 
        }

        .ms-auto {
            margin-right: 20px;
        }

        .card {
            background-color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
            border-bottom: 1px solid #444;
            font-weight: bold;
        }

        .card-body {
            text-align: left; /* Ubah dari center ke kiri */
            color: #fff;
            padding: 25px;
        }

        .card-title {
            font-size: 22px;
            font-weight: bold;
            color: #ffbb00;
            margin-bottom: 15px;
            text-align: left; /* Pastikan title rata kiri juga */
        }

        .card-text {
            font-size: 16px;
            margin-bottom: 20px;
            color: #ccc;
            text-align: left; /* Card text rata kiri */
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 25px;
            padding: 10px 25px;
            font-size: 16px;
            letter-spacing: 1px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #1e1e1e;
            font-weight: bold;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.3);
        }

        h3 {
            font-size: 26px;
            font-weight: bold;
            color: #ffbb00;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
            text-align: left; /* Heading juga rata kiri */
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #ccc;
            text-align: left;
        }

        .container-fluid {
            padding: 0;
        }
    </style>

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top custom-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"> Kasir Restoran - Waiter</a>
            <div class="ms-auto">
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../waiter.php"><i class="mdi mdi-home"></i> Dashboard</a>
        <a href="../pesanan/tambah.php"><i class="mdi mdi-plus-circle"></i> Tambah Pesanan</a>
        <a href="../pesanan/index.php" class="bg-dark"><i class="mdi mdi-clipboard-text"></i> Lihat Pesanan</a>
        <a href="../laporan/laporan.php"><i class="mdi mdi-chart-line"></i> Laporan</a>
    </div>

    <!-- Konten -->
    <div class="content">
        <h3 class="mb-4"><i class="mdi mdi-clipboard-text"></i> Daftar Pesanan</h3>

        <?php if (!empty($pesanan)): ?>
            <?php foreach ($pesanan as $id => $data): ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <strong>ID Pesanan:</strong> <?= $id ?> | <strong>Tanggal:</strong> <?= $data['tanggal'] ?>
                    </div>
                    <div class="card-body">
                        <p><strong>Meja:</strong> <?= $data['meja'] ?></p>
                        <p><strong>Pelanggan:</strong> <?= $data['pelanggan'] ?></p>
                        <p><strong>Menu Dipesan:</strong></p>
                        <ul>
                            <?php foreach ($data['menu'] as $item): ?>
                                <li><?= $item['namamenu'] ?> - <?= $item['jumlah'] ?> porsi</li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="d-flex gap-2">
                            <a href="edit_pesanan.php?id=<?= $id ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="hapus_pesanan.php?id=<?= $id ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Belum ada pesanan.</p>
        <?php endif; ?>

        <a href="../waiter.php" class="btn btn-secondary mt-3">⬅ Kembali ke Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>