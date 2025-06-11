<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $role = $_POST['role'];
  $conn->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
  header("Location: users.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
  <h1 class="text-2xl font-bold mb-4">Tambah User</h1>
  <form method="POST" class="space-y-4 max-w-sm">
    <input name="username" required placeholder="Username" class="w-full border p-2 rounded">
    <input name="password" type="password" required placeholder="Password" class="w-full border p-2 rounded">
    <select name="role" class="w-full border p-2 rounded">
      <option value="admin">Admin</option>
      <option value="kasir">Kasir</option>
      <option value="pemilik">Pemilik</option>
    </select>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</body>
</html>
