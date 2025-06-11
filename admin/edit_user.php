<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $role = $_POST['role'];
  if (!empty($_POST['password'])) {
    $password = md5($_POST['password']);
    $conn->query("UPDATE users SET username='$username', password='$password', role='$role' WHERE id=$id");
  } else {
    $conn->query("UPDATE users SET username='$username', role='$role' WHERE id=$id");
  }
  header("Location: users.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
  <h1 class="text-2xl font-bold mb-4">Edit User</h1>
  <form method="POST" class="space-y-4 max-w-sm">
    <input name="username" required value="<?= $data['username'] ?>" class="w-full border p-2 rounded">
    <input name="password" type="password" placeholder="(Kosongkan jika tidak diubah)" class="w-full border p-2 rounded">
    <select name="role" class="w-full border p-2 rounded">
      <option value="admin" <?= $data['role']=='admin'?'selected':'' ?>>Admin</option>
      <option value="kasir" <?= $data['role']=='kasir'?'selected':'' ?>>Kasir</option>
      <option value="pemilik" <?= $data['role']=='pemilik'?'selected':'' ?>>Pemilik</option>
    </select>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
  </form>
</body>
</html>
