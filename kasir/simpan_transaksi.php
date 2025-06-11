<?php
session_start();
if ($_SESSION['role'] != 'kasir') header("Location: ../index.php");
require '../includes/db.php';

$kasir_id = $_SESSION['id'];
$total = $_POST['total'];
$tanggal = date("Y-m-d H:i:s");

$conn->query("INSERT INTO transaksi (kasir_id, tanggal, total) VALUES ('$kasir_id', '$tanggal', '$total')");
$transaksi_id = $conn->insert_id;

$produk_id = $_POST['produk_id'];
$harga = $_POST['harga'];
$jumlah = $_POST['jumlah'];

for ($i = 0; $i < count($produk_id); $i++) {
  $pid = $produk_id[$i];
  $qty = $jumlah[$i];
  $hrg = $harga[$i];
  $subtotal = $qty * $hrg;

  $conn->query("INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga) 
                VALUES ('$transaksi_id', '$pid', '$qty', '$hrg')");

  $conn->query("UPDATE produk SET stok = stok - $qty WHERE id = $pid");
}

header("Location: transaksi.php");
