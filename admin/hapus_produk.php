<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: ../index.php");
  exit;
}

require '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  $_SESSION['message'] = "ID produk tidak valid.";
  header("Location: produk.php");
  exit;
}

$id = (int) $_GET['id'];

// Cek apakah produk ada
$produk = $conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();
if (!$produk) {
  $_SESSION['message'] = "Produk tidak ditemukan.";
  header("Location: produk.php");
  exit;
}

// Hapus
$hapus = $conn->query("DELETE FROM produk WHERE id=$id");
if ($hapus) {
  $_SESSION['message'] = "Produk berhasil dihapus.";
} else {
  $_SESSION['message'] = "Gagal menghapus produk.";
}

header("Location: produk.php");
exit;
?>
