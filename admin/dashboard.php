<?php
session_start();
if ($_SESSION['role'] != 'admin')
    header("Location: ../index.php");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Halaman Admin</title>
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


<body class="flex min-h-screen bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-64 bg-white shadow-lg flex flex-col p-4 space-y-4"
        style="width: 16rem;">

        <!-- Toggle Button -->
        <span onclick="toggleSidebar()"
            class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <!-- Judul Sidebar -->
        <h2 id="sidebar-title" class="text-xl font-bold text-blue-600">Admin Menu</h2>

        <!-- Menu -->
        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="users.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600">
                <span>👤</span> <span class="sidebar-text">Kelola User</span>
            </a>
            <a href="produk.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600">
                <span>📦</span> <span class="sidebar-text">Kelola Produk</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>


    <!-- Main Content -->
    <main class="flex-1 p-8">
        <h1 class="text-3xl font-bold mb-4">Selamat Datang, Admin <?= $_SESSION['username'] ?></h1>
        <p class="text-gray-700">Ini adalah halaman dashboard khusus untuk admin. Dari sini, Anda bisa mengelola user
            dan produk.</p>

        <!-- Contoh statistik sederhana -->
        <div class="mt-6 grid grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Total User</h2>
                <?php
                require '../includes/db.php';
                $jumlahUser = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
                ?>
                <p class="text-2xl font-bold text-blue-600"><?= $jumlahUser ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Produk</h2>
                <?php
                $jumlahProduk = $conn->query("SELECT COUNT(*) AS total FROM produk")->fetch_assoc()['total'];
                ?>
                <p class="text-2xl font-bold text-green-600"><?= $jumlahProduk ?></p>
            </div>
        </div>
    </main>
    <script src="../assets/js/sidebar.js">

    </script>
</body>


</body>

</html>