<?php
session_start();
if ($_SESSION['role'] != 'kasir') header("Location: ../index.php");
require '../includes/db.php';
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
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 bg-gradient-to-br from-gray-200 via-white to-gray-200">
        <!-- Toggle Button -->
        <span onclick="toggleSidebar()" class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <!-- Judul Sidebar -->
        <h2 id="sidebar-title" class="text-xl font-bold text-green-600">Kasir Menu</h2>

        <!-- Menu -->
        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-green-600">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="transaksi.php" class="flex items-center space-x-2 text-gray-800 hover:text-green-600">
                <span>💵</span> <span class="sidebar-text">Transaksi</span>
            </a>
            <a href="riwayat.php" class="flex items-center space-x-2 text-gray-800 hover:text-green-600">
                <span>⏲️</span> <span class="sidebar-text">Riwayat Transaksi</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h1 id="animated-text" class="text-3xl font-bold mb-4 text-gray-900 whitespace-nowrap">
            <span id="typed-text"></span><span class="cursor">|</span>
        </h1>
        <p class="text-gray-700">
            Anda sedang berada di dashboard kasir. Di sini Anda dapat melakukan transaksi penjualan dan melihat riwayat transaksi.
        </p>

        <div class="mt-6 grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded shadow-xl">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Produk</h2>
                <?php
                $jumlahProduk = $conn->query("SELECT COUNT(*) AS total FROM produk")->fetch_assoc()['total'];
                ?>
                <p class="text-2xl font-bold text-green-700"><?= $jumlahProduk ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow-xl">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Transaksi Hari Ini</h2>
                <?php
                $today = date('Y-m-d');
                $jumlahTransaksi = $conn->query("SELECT COUNT(*) AS total FROM transaksi WHERE DATE(tanggal) = '$today'")->fetch_assoc()['total'];
                ?>
                <p class="text-2xl font-bold text-green-700"><?= $jumlahTransaksi ?></p>
            </div>
        </div>
    </main>

    <script src="../assets/js/sidebar.js"></script>
    <script src="../assets/js/kasirTypingEffect.js"></script>
</body>

</html>
