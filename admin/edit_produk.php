<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM produk WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $conn->query("UPDATE produk SET nama='$nama', harga='$harga', stok='$stok' WHERE id=$id");
  header("Location: produk.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
  <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>
  <form method="POST" class="space-y-4 max-w-sm">
    <input name="nama" required value="<?= $data['nama'] ?>" class="w-full border p-2 rounded">
    <input name="harga" required type="number" value="<?= $data['harga'] ?>" class="w-full border p-2 rounded">
    <input name="stok" required type="number" value="<?= $data['stok'] ?>" class="w-full border p-2 rounded">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
  </form>
</body>
</html>
