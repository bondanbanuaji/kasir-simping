<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Kasir Simping</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'media',
      theme: {
        extend: {
          animation: {
            fadeIn: 'fadeIn 1s ease-in-out',
            slideIn: 'slideIn 0.8s ease-out',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: 0 },
              '100%': { opacity: 1 }
            },
            slideIn: {
              '0%': { transform: 'translateY(30px)', opacity: 0 },
              '100%': { transform: 'translateY(0)', opacity: 1 }
            }
          }
        }
      }
    };
  </script>
</head>

<body class="bg-gradient-to-br from-blue-100 via-white to-pink-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center min-h-screen">

  <form action="proses/login.php" method="POST"
    class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl px-8 py-10 w-full max-w-sm animate-slideIn">
    <h1 class="text-3xl font-extrabold text-center mb-6 text-gray-800 dark:text-white">
      Login Kasir <span class="text-blue-500">Simping</span>
    </h1>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm animate-fadeIn">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Username</label>
    <input type="text" name="username" required
      class="mt-1 mb-4 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">

    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Password</label>
    <input type="password" name="password" required
      class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none dark:bg-gray-700 dark:text-white dark:border-gray-600">

    <button type="submit"
      class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">
      Login
    </button>
  </form>
</body>

</html>
