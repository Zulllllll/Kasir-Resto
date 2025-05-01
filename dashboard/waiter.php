<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'waiter') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Waiter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <a class="navbar-brand" href="#"> Kasir Restoran - Waiter</a>
            <div class="ms-auto">
                <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="waiter.php"><i class="mdi mdi-home"></i> Dashboard</a>
        <a href="pesanan/tambah.php"><i class="mdi mdi-plus-circle"></i> Tambah Pesanan</a>
        <a href="pesanan/index.php"><i class="mdi mdi-clipboard-text"></i> Lihat Pesanan</a>
        <a href="laporan/laporan.php"><i class="mdi mdi-chart-line"></i> Laporan</a>
    </div>


    <!-- Konten -->
    <div class="content">
        <h3 class="mb-4 fw-semibold">Halo, <?= $_SESSION['namauser']; ?>!! </h3>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="mdi mdi-plus-circle"></i> Tambah Pesanan</h5>
                        <p class="card-text text-secondary">Catat pesanan pelanggan dengan mudah dan cepat.</p>
                        <a href="pesanan/tambah.php" class="btn btn-primary">Tambah Sekarang</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="mdi mdi-clipboard-text"></i> Lihat Pesanan</h5>
                        <p class="card-text text-secondary">Kelola pesanan pelanggan yang sedang berjalan.</p>
                        <a href="pesanan/index.php" class="btn btn-primary">Lihat Daftar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="mdi mdi-chart-line"></i> Laporan Penjualan</h5>
                        <p class="card-text text-secondary">Analisa data penjualan harian dan mingguan.</p>
                        <a href="laporan/laporan.php" class="btn btn-primary">Lihat Laporan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
