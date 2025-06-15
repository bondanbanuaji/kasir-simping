<?php
session_start();
if ($_SESSION['role'] != 'pemilik') {
    header("Location: ../login.php");
    exit();
}

include '../proses/connect.php';

$query = "
SELECT 
    t.no_invoice,
    t.tgl_transaksi,
    GROUP_CONCAT(CONCAT(p.nama, ' (', dt.jumlah, 'x)') SEPARATOR ', ') AS daftar_produk,
    t.total,
    t.metode_pembayaran,
    u.username AS nama_kasir
FROM transaksi t
JOIN detail_transaksi dt ON dt.transaksi_id = t.id_transaksi
JOIN produk p ON dt.produk_id = p.id
JOIN users u ON t.kasir_id = u.id
GROUP BY t.id_transaksi, t.no_invoice, t.tgl_transaksi, t.total, t.metode_pembayaran, u.username
ORDER BY t.tgl_transaksi DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-200 via-white to-gray-200 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 
        bg-gradient-to-br from-gray-200 via-white to-gray-200">
        <!-- Toggle Button -->
        <span onclick="toggleSidebar()" class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-300 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <!-- Judul Sidebar -->
        <h2 id="sidebar-title" class="text-xl font-bold text-green-600">Pemilik Panel</h2>

        <!-- Menu -->
        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-green-600 hover:bg-gray-200">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="laporan.php" class="font-semibold flex items-center space-x-2 text-gray-800 hover:text-green-600 hover:bg-gray-200">
                <span>📄</span> <span class="sidebar-text">Laporan</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4 hover:text-black hover:bg-red-200">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Konten -->
    <div class="flex-1 p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Laporan Transaksi</h1>
            <a href="cetak_laporan.php" target="_blank"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">📄 Cetak PDF</a>
        </div>

        <div class="overflow-auto bg-white rounded-xl shadow-md p-4">
            <table class="w-full table-auto">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Invoice</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Kasir</th>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-left">Metode</th>
                        <th class="px-4 py-2 text-left">Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                                $tanggal_waktu = date_create($row['tgl_transaksi']);
                                $tanggal = date_format($tanggal_waktu, 'Y-m-d');
                                $jam = date_format($tanggal_waktu, 'H:i:s');
                            ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= $row['no_invoice'] ?></td>
                                <td class="px-4 py-2"><?= $tanggal ?></td>
                                <td class="px-4 py-2"><?= $row['nama_kasir'] ?></td>
                                <td class="px-4 py-2"><?= $row['daftar_produk'] ?></td>
                                <td class="px-4 py-2"><?= $row['metode_pembayaran'] ?></td>
                                <td class="px-4 py-2">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

        <script src="../assets/js/sidebar.js"></script>
</body>
</html>
