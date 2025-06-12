<?php
session_start();
if ($_SESSION['role'] != 'admin')
    header("Location: ../index.php");
require '../includes/db.php';
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

<body class="bg-gradient-to-br from-gray-200 via-white to-gray-100 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4 bg-gradient-to-br from-gray-200 via-white to-gray-100">

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
                <span>👤</span> <span class="sidebar-text">Kelola Akun</span>
            </a>
            <a href="produk.php" class="flex items-center space-x-2 text-gray-800 hover:text-blue-600">
                <span>📦</span> <span class="sidebar-text">Kelola Produk</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>


    <!-- Content -->
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-4">Manajemen Akun</h1>
        <a href="tambah_user.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah Akun</a>
        <?php if (isset($_SESSION['message'])): ?>
            <?php $msg = $_SESSION['message'];
            unset($_SESSION['message']); ?>
            <div
                class="mb-4 p-3 rounded text-sm font-medium 
        <?= $msg['type'] === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' ?>">
                <?= $msg['text'] ?>
            </div>
        <?php endif; ?>
        <table class="w-full table-auto border border-collapse bg-white">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border p-2">No</th>
                    <th class="border p-2">Username</th>
                    <th class="border p-2">Role</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $result = $conn->query("SELECT * FROM users");
                while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="border p-2"><?= $no++ ?></td>
                        <td class="border p-2"><?= $row['username'] ?></td>
                        <td class="border p-2 capitalize"><?= $row['role'] ?></td>
                        <td class="border p-2 space-x-2">
                            <a href="edit_user.php?id=<?= $row['id'] ?>" class="text-blue-600">Edit</a>
                            <a href="hapus_user.php?id=<?= $row['id'] ?>" class="text-red-600"
                                onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <script src="../assets/js/sidebar.js">

    </script>
</body>

</html>