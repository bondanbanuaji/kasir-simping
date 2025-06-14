<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if (!$data) {
  $_SESSION['message'] = ['type' => 'error', 'text' => 'Akun tidak ditemukan'];
  header("Location: users.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $role = $_POST['role'];
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  if (!empty($password)) {
    if (strlen($password) < 6) {
      $_SESSION['message'] = ['type' => 'error', 'text' => 'Password minimal 6 karakter'];
      header("Location: edit_user.php?id=$id");
      exit;
    }

    if ($password !== $confirm) {
      $_SESSION['message'] = ['type' => 'error', 'text' => 'Password tidak cocok'];
      header("Location: edit_user.php?id=$id");
      exit;
    }

    // Update dengan password baru (tanpa hash)
    $conn->query("UPDATE users SET username='$username', password='$password', role='$role' WHERE id=$id");
  } else {
    // Update tanpa mengubah password
    $conn->query("UPDATE users SET username='$username', role='$role' WHERE id=$id");
  }

  $_SESSION['message'] = ['type' => 'success', 'text' => 'Akun berhasil diperbarui'];
  header("Location: users.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Akun</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

  <form method="POST" class="bg-white p-6 rounded shadow max-w-md w-full space-y-4">
    <h1 class="text-2xl font-bold text-center">Edit Akun</h1>

    <!-- Tampilkan pesan -->
    <?php if (isset($_SESSION['message'])): ?>
      <?php $msg = $_SESSION['message']; unset($_SESSION['message']); ?>
      <div class="p-3 rounded text-sm <?= $msg['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
        <?= $msg['text'] ?>
      </div>
    <?php endif; ?>

    <div>
      <label class="block mb-1 font-medium">Username</label>
      <input name="username" value="<?= htmlspecialchars($data['username']) ?>" required class="w-full border p-2 rounded">
    </div>

    <div>
      <label class="block mb-1 font-medium">Password Baru</label>
      <input name="password" type="password" class="w-full border p-2 rounded" placeholder="Kosongkan jika tidak diubah">
    </div>

    <div>
      <label class="block mb-1 font-medium">Konfirmasi Password</label>
      <input name="confirm_password" type="password" class="w-full border p-2 rounded" placeholder="Ulangi password baru">
    </div>

    <div>
      <label class="block mb-1 font-medium">Role</label>
      <select name="role" class="w-full border p-2 rounded">
        <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="kasir" <?= $data['role'] == 'kasir' ? 'selected' : '' ?>>Kasir</option>
        <option value="pemilik" <?= $data['role'] == 'pemilik' ? 'selected' : '' ?>>Pemilik</option>
      </select>
    </div>

    <div class="flex justify-between items-center">
      <a href="users.php" class="text-blue-600 hover:underline">← Kembali</a>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update</button>
    </div>
  </form>

</body>
</html>
