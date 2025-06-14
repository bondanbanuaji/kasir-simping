<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';

// Ambil data produk dari database
$produk = $conn->query("SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Manajemen Produk</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          transitionProperty: {
            'width': 'width'
          }
        }
      }
    }
  </script>
</head>

<body class="bg-gradient-to-br from-gray-200 via-white to-gray-100 flex min-h-screen">

  <!-- Sidebar -->
  <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 bg-gradient-to-br from-gray-200 via-white to-gray-100">
    <span onclick="toggleSidebar()" class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
      <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
      <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
      <span class="block w-6 h-0.5 bg-gray-600"></span>
    </span>

    <h2 id="sidebar-title" class="text-xl font-bold text-blue-600">Admin Menu</h2>
    <nav class="flex flex-col space-y-3">
      <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600">
        <span>📊</span> <span class="sidebar-text">Dashboard</span>
      </a>
      <a href="users.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600">
        <span>👤</span> <span class="sidebar-text">Kelola Akun</span>
      </a>
      <a href="produk.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600 font-semibold">
        <span>📦</span> <span class="sidebar-text">Kelola Produk</span>
      </a>
      <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
        <span>🚪</span> <span class="sidebar-text">Logout</span>
      </a>
    </nav>
  </div>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-4">Manajemen Produk</h1>

    <!-- Flash Message -->
    <?php if (isset($_SESSION['message'])): ?>
      <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <a href="tambah_produk.php" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow transition inline-block mb-4">
      + Tambah Produk
    </a>

    <div class="overflow-x-auto">
      <table class="w-full table-auto border border-collapse bg-white shadow-sm rounded-lg">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="border p-2">No</th>
            <th class="border p-2">Nama Produk</th>
            <th class="border p-2">Harga</th>
            <th class="border p-2">Stok</th>
            <th class="border p-2">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $produk->fetch_assoc()): ?>
            <tr class="hover:bg-gray-50 transition">
              <td class="border p-2 text-center"><?= $no++ ?></td>
              <td class="border p-2"><?= htmlspecialchars($row['nama']) ?></td>
              <td class="border p-2">Rp<?= number_format($row['harga']) ?></td>
              <td class="border p-2 text-center"><?= $row['stok'] ?></td>
              <td class="border p-2 space-x-2 text-center">
                <a href="edit_produk.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>

  <script src="../assets/js/sidebar.js"></script>
</body>

</html>
