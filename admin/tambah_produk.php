<?php
session_start();
if ($_SESSION['role'] != 'admin')
  header("Location: ../index.php");
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = $conn->real_escape_string($_POST['nama']);
  $harga = (int) $_POST['harga'];
  $stok = (int) $_POST['stok'];

  $simpan = $conn->query("INSERT INTO produk (nama, harga, stok) VALUES ('$nama', $harga, $stok)");

  if ($simpan) {
    $_SESSION['message'] = "Produk berhasil ditambahkan!";
    header("Location: produk.php");
    exit;
  } else {
    $_SESSION['message'] = "Gagal menambahkan produk.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tambah Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-md rounded p-8 w-full max-w-md">
    <h1 class="text-2xl font-bold mb-6 text-center text-blue-600">Tambah Produk</h1>

    <?php if (isset($_SESSION['message'])): ?>
      <div class="mb-4 p-3 rounded bg-red-100 text-red-700">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block font-medium mb-1">Nama Produk</label>
        <input name="nama" required placeholder="Contoh: Simping"
          class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
      </div>

      <div>
        <label class="block font-medium mb-1">Harga (Rp)</label>
        <input name="harga" required type="number" min="0" placeholder="Contoh: 10000"
          class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
      </div>

      <div>
        <label class="block font-medium mb-1">Stok</label>
        <input name="stok" required type="number" min="0" placeholder="Contoh: 100"
          class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
      </div>

      <div class="flex justify-between items-center pt-4">
        <a href="produk.php" class="text-gray-600 hover:underline">← Kembali</a>
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">
          Simpan
        </button>
      </div>
    </form>
  </div>

</body>

</html>