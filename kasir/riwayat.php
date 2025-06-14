<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kasir') {
    header("Location: ../index.php");
    exit;
}

require '../includes/db.php';

$kasir_id = $_SESSION['user_id'];

// Ambil data transaksi oleh kasir
$query = "
    SELECT 
        transaksi.id_transaksi AS transaksi_id,
        transaksi.tgl_transaksi,
        transaksi.no_invoice,
        transaksi.total,
        transaksi.metode_pembayaran,
        users.username
    FROM transaksi
    JOIN users ON transaksi.kasir_id = users.id
    WHERE transaksi.kasir_id = $kasir_id
    ORDER BY transaksi.tgl_transaksi DESC
";

$result = $conn->query($query);
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
            <a href="riwayat.php" class="flex items-center space-x-2 text-purple-600 font-bold">
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
                        <th class="border p-2">No. Invoice</th>
                        <th class="border p-2">Produk</th>
                        <th class="border p-2">Tanggal</th>
                        <th class="border p-2">Total</th>
                        <th class="border p-2">Kasir</th>
                        <th class="border p-2">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($t = $result->fetch_assoc()): 
                            $transaksi_id = $t['transaksi_id'];
                            $produk = $conn->query("
                                SELECT produk.nama, detail_transaksi.jumlah 
                                FROM detail_transaksi 
                                JOIN produk ON detail_transaksi.produk_id = produk.id 
                                WHERE detail_transaksi.transaksi_id = $transaksi_id
                            ");

                            $nama_produk = [];
                            $stok_dibeli = [];
                            while ($p = $produk->fetch_assoc()) {
                                $nama_produk[] = $p['nama'];
                                $stok_dibeli[] = $p['jumlah'];
                            }
                        ?>
                        <tr>
                            <td class="border p-2"><?= $t['transaksi_id'] ?></td>
                            <td class="border p-2"><?= $t['no_invoice'] ?></td>
                            <td class="border p-2"><?= implode(', ', $nama_produk) ?></td>
                            <td class="border p-2"><?= $t['tgl_transaksi'] ?></td>
                            <td class="border p-2">Rp<?= number_format($t['total'], 0, ',', '.') ?></td>
                            <td class="border p-2"><?= $t['username'] ?></td>
                            <td class="border p-2"><?= implode(', ', $stok_dibeli) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
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
