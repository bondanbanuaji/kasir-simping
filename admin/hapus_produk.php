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
$stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$produk) {
  $_SESSION['message'] = "Produk tidak ditemukan.";
  header("Location: produk.php");
  exit;
}

// Hapus menggunakan prepared statement
$stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
$stmt->bind_param("i", $id);
$hapus = $stmt->execute();
$stmt->close();

if ($hapus) {
  $_SESSION['message'] = "Produk berhasil dihapus.";
} else {
  $_SESSION['message'] = "Gagal menghapus produk.";
}

header("Location: produk.php");
exit;
?>
