<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Kasir Simping</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <form action="proses/login.php" method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-sm">
    <h1 class="text-2xl font-bold mb-4 text-center">Login Kasir Simping</h1>
    <?php if (!empty($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-sm">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>
    <label class="block mb-2 text-sm font-medium">Username</label>
    <input type="text" name="username" required class="w-full px-3 py-2 border rounded">

    <label class="block mb-2 mt-4 text-sm font-medium">Password</label>
    <input type="password" name="password" required class="w-full px-3 py-2 border rounded">

    <button type="submit" class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
      Login
    </button>
  </form>
</body>
</html>
