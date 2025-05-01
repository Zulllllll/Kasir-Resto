<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Tambah meja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $nomormeja = $_POST['nomormeja'];
    $namameja = $_POST['namameja'];
    mysqli_query($conn, "INSERT INTO meja (nomormeja, namameja, status) VALUES ('$nomormeja', '$namameja', 'kosong')");
    header("Location: index.php");
    exit;
}

// Update meja
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $idmeja = $_POST['idmeja'];
    $nomormeja = $_POST['nomormeja'];
    $namameja = $_POST['namameja'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE meja SET nomormeja='$nomormeja', namameja='$namameja', status='$status' WHERE idmeja=$idmeja");
    header("Location: index.php");
    exit;
}

// Ambil data untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($conn, "SELECT * FROM meja WHERE idmeja=$id");
    $editData = mysqli_fetch_assoc($res);
}

// Hapus meja
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus transaksi yang terkait dengan pesanan ini terlebih dahulu
    mysqli_query($conn, "DELETE FROM transaksi WHERE idpesanan = $id");

    // Hapus pesanan setelah transaksi dihapus
    mysqli_query($conn, "DELETE FROM pesanan WHERE idmeja = $id");

    // Hapus meja setelah pesanan dihapus
    mysqli_query($conn, "DELETE FROM meja WHERE idmeja = $id");

    header("Location: index.php");
    exit;
}



$mejaList = mysqli_query($conn, "SELECT * FROM meja");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Entri Meja</title>
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

        /* Posisi tombol di bawah kanan */
        .btn-back-dashboard {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #6c757d;
            color: #fff;
            border-radius: 50px;
            padding: 10px 25px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-back-dashboard:hover {
            background-color: #495057;
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
        <h3 class="mb-4">Kelola Meja</h3>

        <!-- Form Entri / Edit -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?= $editData ? 'Edit Meja' : 'Tambah Meja Baru' ?></h5>
                <form method="POST">
                    <?php if ($editData): ?>
                        <input type="hidden" name="idmeja" value="<?= $editData['idmeja'] ?>">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Nomor Meja</label>
                        <input type="number" class="form-control" name="nomormeja"
                            value="<?= $editData['nomormeja'] ?? '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Meja</label>
                        <input type="text" class="form-control" name="namameja"
                            value="<?= $editData['namameja'] ?? '' ?>" required>
                    </div>
                    <?php if ($editData): ?>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="kosong" <?= $editData['status'] === 'kosong' ? 'selected' : '' ?>>Kosong</option>
                                <option value="terisi" <?= $editData['status'] === 'terisi' ? 'selected' : '' ?>>Terisi</option>
                            </select>
                        </div>
                    <?php endif; ?>
                    <button type="submit" name="<?= $editData ? 'update' : 'tambah' ?>" class="btn btn-primary"><?= $editData ? 'Update' : 'Simpan' ?></button>
                    <?php if ($editData): ?>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Daftar Meja -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-white">Daftar Meja</h5>
                <table class="table table-bordered text-white">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($meja = mysqli_fetch_assoc($mejaList)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($meja['nomormeja']) ?></td>
                                <td><?= htmlspecialchars($meja['namameja']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($meja['status'])) ?></td>
                                <td>
                                    <a href="?edit=<?= $meja['idmeja'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="?hapus=<?= $meja['idmeja'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($mejaList) == 0): ?>
                            <tr>
                                <td colspan="5" class="text-center">Belum ada meja</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
