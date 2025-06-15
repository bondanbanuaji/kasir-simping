<?php
session_start();
if ($_SESSION['role'] != 'pemilik') {
    header("Location: ../login.php");
    exit();
}

include '../proses/connect.php';

date_default_timezone_set('Asia/Jakarta');
$today = date('Y-m-d');

// Total Transaksi
$queryTransaksi = "SELECT COUNT(*) AS total FROM transaksi";
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, $queryTransaksi))['total'] ?? 0;

// Produk Terjual
$queryProdukTerjual = "SELECT SUM(jumlah) AS total FROM detail_transaksi";
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, $queryProdukTerjual))['total'] ?? 0;

// Total Pemasukan Keseluruhan (dari tabel transaksi)
$queryPemasukan = "SELECT SUM(total) AS total FROM transaksi";
$total_pemasukan = mysqli_fetch_assoc(mysqli_query($conn, $queryPemasukan))['total'] ?? 0;

// Total Pemasukan Hari Ini (filter berdasarkan tanggal)
$queryHarian = "SELECT SUM(total) AS total FROM transaksi WHERE DATE(tgl_transaksi) = '$today'";
$total_pemasukan_harian = mysqli_fetch_assoc(mysqli_query($conn, $queryHarian))['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pemilik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-200 via-white to-gray-200 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 
        bg-gradient-to-br from-gray-200 via-white to-gray-200">
        <!-- Toggle Button -->
        <span onclick="toggleSidebar()" class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <!-- Judul Sidebar -->
        <h2 id="sidebar-title" class="text-xl font-bold text-green-600">Pemilik Panel</h2>

        <!-- Menu -->
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

    <!-- Konten Utama -->
    <div class="flex-1 p-6">
        <h1 id="animated-text" class="text-3xl font-bold mb-4 text-gray-900 whitespace-nowrap">
            <span id="typed-text"></span><span class="cursor text-green-600">|</span>
        </h1> 
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md p-4">
                <p class="text-gray-600">Total Transaksi</p>
                <p class="text-2xl font-bold text-green-600"><?= $total_transaksi ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4">
                <p class="text-gray-600">Produk Terjual</p>
                <p class="text-2xl font-bold text-green-600"><?= $total_produk ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4">
                <p class="text-gray-600">Pendapatan Hari Ini</p>
                <p class="text-2xl font-bold text-green-600">Rp <?= number_format($total_pemasukan_harian, 0, ",", ".") ?></p>
            </div>
            <div class="bg-white rounded-xl shadow-md p-4">
                <p class="text-gray-600">Seluruh Pendapatan Total</p>
                <p class="text-2xl font-bold text-green-600">Rp <?= number_format($total_pemasukan, 0, ",", ".") ?></p>
            </div>
        </div>
    </div>

    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/pemilikTypingEffect.js"></script>
</body>
</html>
