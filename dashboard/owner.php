<?php
include '../session_check.php';
if ($_SESSION['role'] != 'owner') {
    header("Location: ../login/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #212121;
            color: #fff;
            overflow-x: hidden;
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
        <a class="navbar-brand" href="#">Kasir Restoran - Owner</a>
        <div class="ms-auto">
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
    <a href="owner.php"><i class="mdi mdi-home"></i> Dashboard </a>
    <a href="laporan/laporan.php"><i class="mdi mdi-chart-line"></i> Laporan Transaksi </a>
</div>

<!-- Konten -->
<div class="content">
    <h3 class="mb-4">Halo, <?= $_SESSION['namauser']; ?>! 👋</h3>
    <div class="d-flex justify-content-center mt-5">
        <div class="col-md-6 d-flex justify-content-center">
            <div class="card shadow-sm" style="width: 100%; max-width: 500px; height: 300px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                    <h5 class="card-title mb-3">Lihat Laporan Transaksi</h5>
                    <p class="card-text mb-4" style="font-size: 16px;">Kelola dan lihat laporan transaksi restoran Anda.</p>
                    <a href="laporan/laporan.php" class="btn btn-primary">Lihat</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
