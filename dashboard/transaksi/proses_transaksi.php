<?php
session_start();
include '../../config/db.php';

$idpesanan = $_POST['idpesanan'];
$bayar = $_POST['bayar'];

// Ambil total pesanan
$query = "
    SELECT SUM(mn.harga * d.jumlah) AS total
    FROM detail_pesanan d
    JOIN menu mn ON d.idmenu = mn.idmenu
    WHERE d.idpesanan = $idpesanan
";
$result = mysqli_query($conn, $query);
$total = mysqli_fetch_assoc($result)['total'];

// Hitung kembalian
$kembalian = $bayar - $total;

// Simpan transaksi
$query = "
    INSERT INTO transaksi (idpesanan, total, bayar, kembalian, iduser)
    VALUES ($idpesanan, $total, $bayar, $kembalian, {$_SESSION['iduser']})
";
mysqli_query($conn, $query);

// Tandai pesanan sudah dibayar

header("Location: transaksi.php");
exit;
