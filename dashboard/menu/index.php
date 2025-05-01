<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Tambah menu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $namamenu = $_POST['namamenu'];
    $harga = $_POST['harga'];
    mysqli_query($conn, "INSERT INTO menu (namamenu, harga) VALUES ('$namamenu', '$harga')");
    header("Location: index.php");
    exit;
}

// Edit menu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $idmenu = $_POST['idmenu'];
    $namamenu = $_POST['namamenu'];
    $harga = $_POST['harga'];
    mysqli_query($conn, "UPDATE menu SET namamenu='$namamenu', harga='$harga' WHERE idmenu=$idmenu");
    header("Location: index.php");
    exit;
}

// Ambil data untuk edit jika ada
$editData = null;
if (isset($_GET['edit'])) {
    $idedit = $_GET['edit'];
    $editResult = mysqli_query($conn, "SELECT * FROM menu WHERE idmenu=$idedit");
    $editData = mysqli_fetch_assoc($editResult);
}

// Hapus menu
if (isset($_GET['hapus'])) {
    $idmenu = $_GET['hapus'];

    // Hapus dulu dari detail_pesanan
    mysqli_query($conn, "DELETE FROM detail_pesanan WHERE idmenu = $idmenu");

    // Lanjut hapus dari menu
    mysqli_query($conn, "DELETE FROM menu WHERE idmenu = $idmenu");

    echo "<script>
        alert('Menu berhasil dihapus beserta data terkait di detail pesanan!');
        window.location.href = 'index.php';
    </script>";
}


$menus = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Menu</title>
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
            <a class="navbar-brand" href="#">Kasir Restoran - Admin</a>
            <div class="ms-auto">
                <a href="../../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="../admin.php"><i class="mdi mdi-home"></i> Dashboard</a>
        <a href="../meja/index.php"><i class="mdi mdi-chair"></i> Kelola Meja</a>
        <a href="../menu/index.php"><i class="mdi mdi-food"></i> Kelola Menu</a>
    </div>

    <!-- Konten -->
    <div class="content">
        <h3 class="mb-4"><?= $editData ? 'Edit Menu' : 'Tambah Menu Baru' ?></h3>

        <!-- Form Tambah/Edit -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST">
                    <?php if ($editData): ?>
                        <input type="hidden" name="idmenu" value="<?= $editData['idmenu'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" name="namamenu"
                            value="<?= $editData['namamenu'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" value="<?= $editData['harga'] ?? '' ?>"
                            required>
                    </div>
                    <button type="submit" name="<?= $editData ? 'update' : 'tambah' ?>"
                        class="btn btn-primary"><?= $editData ? 'Update' : 'Simpan' ?></button>
                    <?php if ($editData): ?>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Daftar Menu -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-white">Daftar Menu</h5>
                <table class="table table-bordered text-white">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($menu = mysqli_fetch_assoc($menus)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($menu['namamenu']) ?></td>
                                <td>Rp<?= number_format($menu['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="?edit=<?= $menu['idmenu'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="?hapus=<?= $menu['idmenu'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($menus) == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada menu</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
