<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];
  $role = $_POST['role'];

  // Validasi panjang password
  if (strlen($password) < 6) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Password minimal 6 karakter'];
    header("Location: tambah_user.php");
    exit;
  }

  // Validasi konfirmasi password
  if ($password !== $confirm) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Password tidak cocok'];
    header("Location: tambah_user.php");
    exit;
  }

  // Cek username sudah ada atau belum
  $cek = $conn->query("SELECT * FROM users WHERE username='$username'");
  if ($cek->num_rows > 0) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Username sudah digunakan'];
    header("Location: tambah_user.php");
    exit;
  }

  // Simpan user
  $hashed = md5($password);
  $conn->query("INSERT INTO users (username, password, role) VALUES ('$username', '$hashed', '$role')");
  $_SESSION['message'] = ['type' => 'success', 'text' => 'User berhasil ditambahkan'];
  header("Location: users.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

  <form method="POST" class="bg-white p-6 rounded shadow max-w-md w-full space-y-4">
    <h1 class="text-2xl font-bold text-center">Tambah User</h1>

    <!-- Tampilkan pesan -->
    <?php if (isset($_SESSION['message'])): ?>
      <?php $msg = $_SESSION['message']; unset($_SESSION['message']); ?>
      <div class="p-3 rounded text-sm <?= $msg['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
        <?= $msg['text'] ?>
      </div>
    <?php endif; ?>

    <div>
      <label class="block mb-1 font-medium">Username</label>
      <input name="username" required class="w-full border p-2 rounded" placeholder="Masukkan username">
    </div>

    <div>
      <label class="block mb-1 font-medium">Password</label>
      <input name="password" type="password" required class="w-full border p-2 rounded" placeholder="Password minimal 6 karakter">
    </div>

    <div>
      <label class="block mb-1 font-medium">Konfirmasi Password</label>
      <input name="confirm_password" type="password" required class="w-full border p-2 rounded" placeholder="Ulangi password">
    </div>

    <div>
      <label class="block mb-1 font-medium">Role</label>
      <select name="role" class="w-full border p-2 rounded">
        <option value="admin">Admin</option>
        <option value="kasir">Kasir</option>
        <option value="pemilik">Pemilik</option>
      </select>
    </div>

    <div class="flex justify-between items-center">
      <a href="users.php" class="text-blue-600 hover:underline">← Kembali</a>
      <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
    </div>
  </form>
</body>
</html>
