<?php
session_start();
if ($_SESSION['role'] != 'kasir') {
    header("Location: ../index.php");
    exit();
}

require '../proses/connect.php';
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
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
    <link rel="stylesheet" href="../assets/css/typingEffect.css">
</head>
<body class="bg-gradient-to-br from-gray-200 via-white to-gray-200 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 
        bg-gradient-to-br from-gray-200 via-white to-gray-200">
        <span onclick="toggleSidebar()" class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <h2 id="sidebar-title" class="text-xl font-bold text-purple-600">Kasir Menu</h2>

        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="font-semibold flex items-center space-x-2 text-gray-800 hover:text-purple-600 hover:bg-gray-200">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="transaksi.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600 hover:bg-gray-200">
                <span>💵</span> <span class="sidebar-text">Transaksi</span>
            </a>
            <a href="riwayat.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600 hover:bg-gray-200">
                <span>⏲️</span> <span class="sidebar-text">Riwayat Transaksi</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4 hover:bg-red-200 hover:text-black">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h1 id="animated-text" class="text-3xl font-bold mb-4 text-gray-900 whitespace-nowrap">
            <span id="typed-text"></span><span class="cursor">|</span>
        </h1>
        <p class="text-gray-700 mb-6">
            Anda sedang berada di dashboard kasir. Di sini Anda dapat melakukan transaksi penjualan dan melihat riwayat transaksi.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Total Produk -->
            <div class="bg-white p-6 rounded shadow-xl">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Produk</h2>
                <?php
                $jumlahProduk = mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk");
                $jumlahProduk = mysqli_fetch_assoc($jumlahProduk)['total'];
                ?>
                <p class="text-2xl font-bold text-purple-700"><?= $jumlahProduk ?></p>
            </div>

            <!-- Transaksi Hari Ini -->
            <div class="bg-white p-6 rounded shadow-xl">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Transaksi Hari Ini</h2>
                <?php
                $today = date('Y-m-d');
                $kasir_id = $_SESSION['user_id'];
                $jumlahTransaksi = mysqli_query($conn, "SELECT COUNT(*) AS total FROM transaksi WHERE DATE(tgl_transaksi) = '$today' AND kasir_id = $kasir_id");
                $jumlahTransaksi = mysqli_fetch_assoc($jumlahTransaksi)['total'];
                ?>
                <p class="text-2xl font-bold text-purple-700"><?= $jumlahTransaksi ?></p>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="bg-white p-6 rounded shadow-xl">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Pendapatan Hari Ini</h2>
                <?php
                $totalPendapatan = mysqli_query($conn, "SELECT SUM(total) AS total FROM transaksi WHERE DATE(tgl_transaksi) = '$today' AND kasir_id = $kasir_id");
                $totalPendapatan = mysqli_fetch_assoc($totalPendapatan)['total'] ?? 0;
                ?>
                <p class="text-2xl font-bold text-green-600">Rp. <?= number_format($totalPendapatan, 0, ',', '.') ?></p>
            </div>
        </div>
    </main>

    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/kasirTypingEffect.js"></script>
</body>
</html>
