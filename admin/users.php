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

<body class="flex min-h-screen bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-64 bg-white shadow-lg flex flex-col p-4 space-y-4"
        style="width: 16rem;">
        <button onclick="toggleSidebar()" class="self-end text-gray-600 hover:text-blue-600">
            ☰
        </button>
        <h2 id="sidebar-title" class="text-xl font-bold text-blue-600">Admin Menu</h2>
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

    <!-- Content -->
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold mb-4">Manajemen User</h1>
        <a href="tambah_user.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah User</a>

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

</body>

</html>