<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../index.php");
    exit;
}
require '../includes/db.php';

$kasir_id = (int) $_SESSION['user_id'];
$riwayat = $conn->query("
    SELECT transaksi.*, users.username 
    FROM transaksi 
    JOIN users ON transaksi.kasir_id = users.id 
    WHERE transaksi.kasir_id = $kasir_id 
    ORDER BY transaksi.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/typingEffect.css">
</head>
<body class="bg-gradient-to-br from-gray-200 via-white to-gray-200 flex min-h-screen">
    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4">
        <span onclick="toggleSidebar()" class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <h2 class="text-xl font-bold text-purple-600">Kasir Menu</h2>
        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="transaksi.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600">
                <span>💵</span> <span class="sidebar-text">Transaksi</span>
            </a>
            <a href="riwayat.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600">
                <span>⏲️</span> <span class="sidebar-text">Riwayat Transaksi</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main -->
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-4">Riwayat Transaksi</h1>

        <div class="overflow-x-auto bg-white shadow-xl rounded p-4">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-purple-100 text-left border">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Nama Produk</th>
                        <th class="border p-2">Tanggal</th>
                        <th class="border p-2">Jam</th>
                        <th class="border p-2">Total</th>
                        <th class="border p-2">Kasir</th>
                        <th class="border p-2">Stok yang Dibeli</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($t = $riwayat->fetch_assoc()): 
                        $transaksi_id = $t['id'];
                        $produk = $conn->query("SELECT nama, jumlah FROM detail_transaksi WHERE transaksi_id = $transaksi_id");
                        $nama_produk = [];
                        $stok_dibeli = [];
                        while ($p = $produk->fetch_assoc()) {
                            $nama_produk[] = $p['nama'];
                            $stok_dibeli[] = $p['jumlah'];
                        }
                    ?>
                    <tr>
                        <td class="border p-2"><?= $t['id'] ?></td>
                        <td class="border p-2"><?= implode(', ', $nama_produk) ?></td>
                        <td class="border p-2"><?= $t['tanggal'] ?></td>
                        <td class="border p-2"><?= $t['jam'] ?></td>
                        <td class="border p-2">Rp<?= number_format($t['total'], 0, ',', '.') ?></td>
                        <td class="border p-2"><?= $t['username'] ?></td>
                        <td class="border p-2"><?= implode(', ', $stok_dibeli) ?></td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if ($riwayat->num_rows == 0): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500">Belum ada transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/kasirTypingEffect.js"></script>
</body>
</html>
