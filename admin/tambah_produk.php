<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $conn->query("INSERT INTO produk (nama, harga, stok) VALUES ('$nama', '$harga', '$stok')");
  header("Location: produk.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
  <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>
  <form method="POST" class="space-y-4 max-w-sm">
    <input name="nama" required placeholder="Nama Produk" class="w-full border p-2 rounded">
    <input name="harga" required type="number" placeholder="Harga" class="w-full border p-2 rounded">
    <input name="stok" required type="number" placeholder="Stok" class="w-full border p-2 rounded">
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</body>
</html>
