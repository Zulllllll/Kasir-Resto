<?php
session_start();
include '../../config/db.php';

$idpesanan = $_POST['idpesanan'];
$idmenu = $_POST['idmenu'];
$jumlah = $_POST['jumlah'];

// Hapus dulu semua detail lama
mysqli_query($conn, "DELETE FROM detail_pesanan WHERE idpesanan = $idpesanan");

// Masukkan ulang data baru
mysqli_query($conn, "INSERT INTO detail_pesanan (idpesanan, idmenu, jumlah) VALUES ($idpesanan, $idmenu, $jumlah)");

header("Location: index.php");
