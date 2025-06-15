<?php
session_start();

// Hanya admin yang bisa akses
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_transaksi.php?status=error&msg=ID tidak valid");
    exit;
}

$id = (int) $_GET['id'];

// Cek apakah transaksi ada
$cek = $conn->prepare("SELECT * FROM transaksi WHERE id_transaksi = ?");
$cek->bind_param("i", $id);
$cek->execute();
$result = $cek->get_result();

if ($result->num_rows === 0) {
    $cek->close();
    header("Location: kelola_transaksi.php?status=error&msg=Transaksi tidak ditemukan");
    exit;
}
$cek->close();

// Hapus terlebih dahulu dari detail_transaksi (karena foreign key constraint)
$hapusDetail = $conn->prepare("DELETE FROM detail_transaksi WHERE transaksi_id = ?");
$hapusDetail->bind_param("i", $id);
$hapusDetail->execute();
$hapusDetail->close();

// Hapus dari tabel transaksi
$hapusTransaksi = $conn->prepare("DELETE FROM transaksi WHERE id_transaksi = ?");
$hapusTransaksi->bind_param("i", $id);

if ($hapusTransaksi->execute()) {
    $hapusTransaksi->close();
    header("Location: kelola_transaksi.php?status=success&msg=Transaksi berhasil dihapus");
    exit;
} else {
    $hapusTransaksi->close();
    header("Location: kelola_transaksi.php?status=error&msg=Gagal menghapus transaksi");
    exit;
}
?>
