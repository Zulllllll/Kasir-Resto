<?php
session_start();
include '../../config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'waiter') {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Hapus transaksi yang terkait dengan idpesanan
    mysqli_query($conn, "DELETE FROM transaksi WHERE idpesanan = $id");

    // Hapus detail pesanan yang terkait dengan idpesanan
    mysqli_query($conn, "DELETE FROM detail_pesanan WHERE idpesanan = $id");

    // Terakhir, hapus pesanan utama
    mysqli_query($conn, "DELETE FROM pesanan WHERE idpesanan = $id");
}

header("Location: lihat_pesanan.php");
exit;
