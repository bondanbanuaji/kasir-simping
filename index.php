<?php
session_start();
if (isset($_SESSION['role'])) {
  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Web Kasir Simping</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer" />
  <script>
    tailwind.config = {
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

<body class="bg-gradient-to-br from-blue-100 via-white to-pink-100 flex items-center justify-center min-h-screen">

  <form action="proses/login.php" method="POST"
    class="bg-white shadow-2xl rounded-2xl px-8 py-10 w-full max-w-sm animate-slideIn">
    <h1 class="flex-row text-2xl font-extrabold text-center mb-6 text-gray-800">
      <i class="fa-solid fa-cart-shopping"></i> Web Kasir <span class="text-blue-500">Simping</span>
    </h1>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm animate-fadeIn shadow">
        <?= $_SESSION['error'];
        unset($_SESSION['error']); ?>
      </div>
    <?php endif; ?>

    <!-- Username -->
    <div class="relative z-0 w-full mb-6 group">
      <input type="text" name="username" id="username" required
        class="peer block w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pt-5 pb-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-0"
        placeholder=" " />
      <label for="username" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3.5 scale-75 top-0 left-3 z-10 origin-[0] px-1 bg-white shadow-sm
               peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-3
               peer-placeholder-shown:text-base peer-focus:scale-75 peer-focus:-translate-y-3.5">
        Masukkan Username
      </label>
    </div>

    <!-- Password -->
    <div class="relative z-0 w-full mb-6 group">
      <input type="password" name="password" id="password" required
        class="peer block w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pt-5 pb-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-0"
        placeholder=" " />
      <label for="password" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3.5 scale-75 top-0 left-3 z-10 origin-[0] px-1 bg-white shadow-sm
               peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-3
               peer-placeholder-shown:text-base peer-focus:scale-75 peer-focus:-translate-y-3.5">
        Masukkan Password
      </label>
    </div>

    <button type="submit"
      class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold shadow-md transition duration-300 ease-in-out transform hover:-translate-y-1">
      Login
    </button>
  </form>
</body>

</html>