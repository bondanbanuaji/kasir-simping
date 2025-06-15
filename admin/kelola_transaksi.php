<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require '../includes/db.php';
date_default_timezone_set('Asia/Jakarta');

$status = $_GET['status'] ?? null;
$msg = $_GET['msg'] ?? '';

$query = "
    SELECT transaksi.*, users.username AS kasir, produk.nama AS nama_produk
    FROM transaksi
    JOIN users ON transaksi.kasir_id = users.id
    JOIN produk ON transaksi.id_produk = produk.id
    ORDER BY transaksi.tgl_transaksi DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function konfirmasiHapus(id) {
            if (confirm("Yakin ingin hapus transaksi ini?")) {
                window.location.href = 'hapus_transaksi.php?id=' + id;
            } else {
                alert("Transaksi tidak jadi dihapus.");
            }
        }
    </script>
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

<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <div id="sidebar"
        class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 bg-gradient-to-br from-gray-200 via-white to-gray-100">
        <span onclick="toggleSidebar()"
            class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <h2 id="sidebar-title" class="text-xl font-bold text-blue-600">Admin Menu</h2>
        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php"
                class="flex items-center space-x-2 text-gray-800 hover:text-blue-600 hover:bg-gray-200">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="users.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600 hover:bg-gray-200">
                <span>👤</span> <span class="sidebar-text">Kelola Akun</span>
            </a>
            <a href="produk.php"
                class="flex items-center space-x-2 text-gray-800 hover:text-blue-600 hover:bg-gray-200">
                <span>📦</span> <span class="sidebar-text">Kelola Produk</span>
            </a>
            <a href="kelola_transaksi.php"
                class="font-semibold flex items-center space-x-2 text-gray-800 hover:text-blue-600 hover:bg-gray-200">
                <span>📦</span> <span class="sidebar-text">Kelola Transaksi</span>
            </a>
            <a href="../proses/logout.php"
                class="flex items-center space-x-2 text-red-600 mt-4 hover:text-black hover:bg-red-200">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Konten -->
    <div class="flex-1 p-6">
        <?php if ($status): ?>
            <div class="mb-4 flex justify-between items-center mb-4">
                <div
                    class="<?= $status === 'success' ? 'bg-blue-100 border-blue-400 text-blue-700' : 'bg-red-100 border-red-400 text-red-700' ?> border px-4 py-3 rounded relative">
                    <strong class="font-bold"><?= $status === 'success' ? 'Berhasil!' : 'Gagal!' ?></strong>
                    <span class="block sm:inline">
                        <?= htmlspecialchars($msg) ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <h1 class="text-2xl font-bold mb-4">Kelola Transaksi</h1>
        <div class="overflow-auto bg-white rounded-xl shadow-md p-4">
            <table class="w-full table-auto border-xl">
                <thead class="bg-gray-200 text-gray-700">
                    <tr class="border-b border p-2">
                        <th class="px-4 py-2 text-left border p-2">Invoice</th>
                        <th class="px-4 py-2 text-left border p-2">Tanggal</th>
                        <th class="px-4 py-2 text-left border p-2">Kasir</th>
                        <th class="px-4 py-2 text-left border p-2">Produk</th>
                        <th class="px-4 py-2 text-left border p-2">Metode</th>
                        <th class="px-4 py-2 text-left border p-2">Total</th>
                        <th class="px-4 py-2 text-left border p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-b border p-2">
                            <td class="px-4 py-2 border p-2">#<?= htmlspecialchars($row['no_invoice']) ?></td>
                            <td class="px-4 py-2 border p-2"><?= htmlspecialchars($row['tgl_transaksi']) ?></td>
                            <td class="px-4 py-2 border p-2"><?= htmlspecialchars($row['kasir']) ?></td>
                            <td class="px-4 py-2 border p-2"><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td class="px-4 py-2 border p-2 capitalize"><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                            <td class="px-4 py-2 border p-2">Rp. <?= number_format($row['total'], 0, ',', '.') ?></td>
                            <td class="px-4 py-2 border p-2">
                                <button onclick="konfirmasiHapus(<?= $row['id_transaksi'] ?>)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Hapus Transaksi</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../assets/js/sidebar.js"></script>
</body>

</html>