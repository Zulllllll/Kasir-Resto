<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'waiter') {
    header("Location: ../../login.php");
    exit;
}

$mejaList = mysqli_query($conn, "SELECT * FROM meja");
$menuList = mysqli_query($conn, "SELECT * FROM menu");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['namapelanggan'];
    $jk = $_POST['jeniskelamin'];
    $nohp = $_POST['nohp'];
    $alamat = $_POST['alamat'];
    mysqli_query($conn, "INSERT INTO pelanggan (namapelanggan, jeniskelamin, nohp, alamat) VALUES ('$nama', '$jk', '$nohp', '$alamat')");
    $idpelanggan = mysqli_insert_id($conn);

    $idmeja = $_POST['idmeja'];
    $iduser = $_SESSION['iduser'];
    mysqli_query($conn, "INSERT INTO pesanan (idmeja, idpelanggan, iduser) VALUES ('$idmeja', '$idpelanggan', '$iduser')");
    $idpesanan = mysqli_insert_id($conn);

    mysqli_query($conn, "UPDATE meja SET status='terisi' WHERE idmeja='$idmeja'");

    foreach ($_POST['idmenu'] as $key => $idmenu) {
        $jumlah = $_POST['jumlah'][$key];
        if ($jumlah > 0) {
            mysqli_query($conn, "INSERT INTO detail_pesanan (idpesanan, idmenu, jumlah) VALUES ('$idpesanan', '$idmenu', '$jumlah')");
        }
    }

    echo "<script>alert('Pesanan berhasil dibuat'); window.location='index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pesanan</title>
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

        .sidebar a:hover {
            background-color: #495057;
            padding-left: 30px;
            transform: translateX(5px);
        }

        .sidebar a .mdi, .sidebar a .material-icons {
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
        }

        .card-body {
            text-align: center;
            color: #fff;
            padding: 25px;
        }

        .card-title {
            font-size: 22px;
            font-weight: bold;
            color: #fff;
        }

        .card-text {
            font-size: 16px;
            margin-bottom: 20px;
            color: #ccc;
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

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 20px 40px rgba(0, 0, 0, 0.3);
        }

        h3 {
            font-size: 26px;
            font-weight: bold;
            color: #ffbb00;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        }

        p {
            font-size: 16px;
            color: #ccc;
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
            <a class="navbar-brand fw-bold" href="#"> Kasir Restoran - Waiter</a>
            <div class="ms-auto">
                <a href="../../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../waiter.php"><i class="mdi mdi-home"></i> Dashboard</a>
        <a href="tambah.php"><i class="mdi mdi-plus-circle"></i> Tambah Pesanan</a>
        <a href="index.php"><i class="mdi mdi-clipboard-text"></i> Lihat Pesanan</a>
        <a href="../laporan/laporan.php"><i class="mdi mdi-chart-line"></i> Laporan</a>
    </div>

    <!-- Konten -->
    <div class="content">
        <h3 class="mb-4">Tambah Pesanan</h3>

        <form method="POST">
            <!-- Informasi Pelanggan -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">Informasi Pelanggan</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" name="namapelanggan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jeniskelamin" class="form-select" required>
                                <option value="0">Pria</option>
                                <option value="1">Wanita</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" name="nohp" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Meja</label>
                        <select name="idmeja" class="form-select" required>
                            <?php while ($meja = mysqli_fetch_assoc($mejaList)): ?>
                                <?php if ($meja['status'] === 'kosong'): ?>
                                    <option value="<?= $meja['idmeja'] ?>">Meja <?= $meja['nomormeja'] ?> (<?= $meja['namameja'] ?>)</option>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pilih Menu -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">Pilih Menu</div>
                <div class="card-body">
                    <?php while ($menu = mysqli_fetch_assoc($menuList)): ?>
                        <div class="row align-items-center mb-2">
                            <div class="col-md-6">
                                <?= $menu['namamenu'] ?> <span style="color: #ccc;">(Rp<?= number_format($menu['harga']) ?>)</span>
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" name="idmenu[]" value="<?= $menu['idmenu'] ?>">
                                <input type="number" name="jumlah[]" class="form-control" placeholder="Jumlah" min="0" value="0">
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <div class="mb-5">
                <button type="submit" class="btn btn-primary">Simpan Pesanan</button>
                <a href="../waiter.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>