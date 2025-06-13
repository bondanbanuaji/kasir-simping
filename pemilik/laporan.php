<?php
session_start();
if ($_SESSION['role'] != 'pemilik') {
    header("Location: ../login.php");
    exit();
}

include '../proses/connect.php';

// Ambil data transaksi lengkap dari tabel relasi
$query = "
    SELECT 
        t.id AS transaksi_id,
        t.tanggal,
        t.jam,
        t.nama AS nama_pembeli,
        u.username AS kasir,
        SUM(dt.jumlah * dt.harga) AS total
    FROM transaksi t
    JOIN detail_transaksi dt ON dt.transaksi_id = t.id
    JOIN users u ON u.id = t.kasir_id
    GROUP BY t.id, t.tanggal, t.jam, u.username
    ORDER BY t.tanggal DESC, t.jam DESC
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
    <div id="sidebar"
        class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 bg-gradient-to-br from-gray-200 via-white to-gray-200">
        <!-- Toggle Button -->
        <span onclick="toggleSidebar()"
            class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <h2 id="sidebar-title" class="text-xl font-bold text-green-600">Pemilik Panel</h2>

        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-green-600">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="laporan.php" class="flex items-center space-x-2 text-gray-800 hover:text-green-600">
                <span>📄</span> <span class="sidebar-text">Laporan</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
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
                        <th class="px-4 py-2 text-left">ID Transaksi</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Jam</th>
                        <th class="px-4 py-2 text-left">Kasir</th>
                        <th class="px-4 py-2 text-left">Pembeli</th>
                        <th class="px-4 py-2 text-left">Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= $row['transaksi_id'] ?></td>
                                <td class="px-4 py-2"><?= $row['tanggal'] ?></td>
                                <td class="px-4 py-2"><?= $row['jam'] ?></td>
                                <td class="px-4 py-2"><?= $row['kasir'] ?></td>
                                <td class="px-4 py-2"><?= $row['nama_pembeli'] ?></td>
                                <td class="px-4 py-2">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada data transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("w-[13rem]");
            sidebar.classList.toggle("w-0");
        }
    </script>
</body>

</html>