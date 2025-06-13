<?php
session_start();
if ($_SESSION['role'] != 'kasir')
    header("Location: ../index.php");
require '../includes/db.php';
date_default_timezone_set('Asia/Jakarta');

$namas = $_POST['nama'];
$kasir_id = $_SESSION['user_id'];
$total = $_POST['total'];
$tanggal = date("Y-m-d");
$jam = date("H:i:s");

$conn->query("INSERT INTO transaksi (kasir_id, tanggal, jam, total) 
            VALUES ('$kasir_id', '$tanggal', '$jam', '$total')");
$transaksi_id = $conn->insert_id;

$produk_ids = $_POST['produk_id'];
$jumlahs = $_POST['jumlah'];
$hargas = $_POST['harga'];

for ($i = 0; $i < count($produk_ids); $i++) {
    $produk_id = $produk_ids[$i];
    $jumlah = $jumlahs[$i];
    $harga = $hargas[$i];
    $nama = $namas[$i]; // ambil nama dari form

    // Simpan detail transaksi
    $conn->query("INSERT INTO detail_transaksi (transaksi_id, produk_id, nama, jumlah, harga) 
                VALUES ('$transaksi_id', '$produk_id', '$nama', '$jumlah', '$harga')");

    // 🔽 Kurangi stok produk di tabel produk
    $conn->query("UPDATE produk SET stok = stok - $jumlah WHERE id = '$produk_id'");
}


header("Location: riwayat.php");
