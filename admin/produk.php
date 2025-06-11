<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
  <h1 class="text-2xl font-bold mb-4">Manajemen Produk</h1>
  <a href="tambah_produk.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Produk</a>
  <table class="w-full table-auto border border-collapse">
    <thead class="bg-gray-200">
      <tr>
        <th class="border p-2">No</th>
        <th class="border p-2">Nama Produk</th>
        <th class="border p-2">Harga</th>
        <th class="border p-2">Stok</th>
        <th class="border p-2">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no = 1;
        $result = $conn->query("SELECT * FROM produk");
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td class="border p-2"><?= $no++ ?></td>
        <td class="border p-2"><?= $row['nama'] ?></td>
        <td class="border p-2">Rp<?= number_format($row['harga']) ?></td>
        <td class="border p-2"><?= $row['stok'] ?></td>
        <td class="border p-2">
          <a href="edit_produk.php?id=<?= $row['id'] ?>" class="text-blue-500">Edit</a> |
          <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="text-red-500" onclick="return confirm('Hapus produk ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
