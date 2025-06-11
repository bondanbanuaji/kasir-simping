<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
  <h1 class="text-2xl font-bold mb-4">Manajemen User</h1>
  <a href="tambah_user.php" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">+ Tambah User</a>
  <table class="w-full table-auto border border-collapse">
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
        <td class="border p-2"><?= $row['role'] ?></td>
        <td class="border p-2">
          <a href="edit_user.php?id=<?= $row['id'] ?>" class="text-blue-500">Edit</a> |
          <a href="hapus_user.php?id=<?= $row['id'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
