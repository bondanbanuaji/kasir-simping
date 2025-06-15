<?php
session_start();
if ($_SESSION['role'] != 'kasir')
    header("Location: ../index.php");
require '../includes/db.php';

$produk = $conn->query("SELECT * FROM produk");

$status = $_GET['status'] ?? null;
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Transaksi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../assets/js/sidebar.js" defer></script>
    <script src="../assets/js/kasirTypingEffect.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;800&display=swap" rel="stylesheet">

    <script>
        function tambahProduk() {
            const select = document.getElementById("produk");
            const jumlah = document.getElementById("jumlah").value;
            const harga = parseInt(select.options[select.selectedIndex].getAttribute("data-harga"));
            const id = select.value;
            const nama = select.options[select.selectedIndex].text;
            const subtotal = harga * jumlah;

            const table = document.getElementById("keranjang");
            const row = table.insertRow();
            row.innerHTML = `
                <td class="border p-2">${nama}
                    <input type="hidden" name="produk_id[]" value="${id}">
                    <input type="hidden" name="nama[]" value="${nama}">
                </td>
                <td class="border p-2">${harga}<input type="hidden" name="harga[]" value="${harga}"></td>
                <td class="border p-2">${jumlah}<input type="hidden" name="jumlah[]" value="${jumlah}"></td>
                <td class="border p-2">${subtotal}</td>`;

            const totalInput = document.getElementById("total");
            totalInput.value = parseInt(totalInput.value) + subtotal;
        }
    </script>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-200 via-white to-gray-200 flex min-h-screen">

    <!-- Sidebar -->
    <div id="sidebar" class="transition-all duration-300 w-[13rem] bg-white shadow-lg flex flex-col p-4 space-y-4">
        <span onclick="toggleSidebar()"
            class="cursor-pointer w-10 h-10 flex flex-col justify-center items-center hover:bg-gray-200 rounded transition">
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600 mb-1"></span>
            <span class="block w-6 h-0.5 bg-gray-600"></span>
        </span>

        <h2 class="text-xl font-bold text-purple-600">Kasir Menu</h2>
        <nav class="flex flex-col space-y-3">
            <a href="dashboard.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600">
                <span>📊</span> <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="transaksi.php" class="font-bold flex items-center space-x-2 text-gray-800 hover:text-purple-600">
                <span>💵</span> <span class="sidebar-text">Transaksi</span>
            </a>
            <a href="riwayat.php" class="flex items-center space-x-2 text-gray-800 hover:text-purple-600">
                <span>⏲️</span> <span class="sidebar-text">Riwayat Transaksi</span>
            </a>
            <a href="../proses/logout.php" class="flex items-center space-x-2 text-red-600 mt-4">
                <span>🚪</span> <span class="sidebar-text">Logout</span>
            </a>
        </nav>
    </div>

    <!-- KONTEN -->
    <div class="flex-1 p-6">
        <?php if ($status): ?>
    <div class="mb-4">
        <div class="<?= $status === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' ?> border px-4 py-3 rounded relative animate-fade-in-down">
            <strong class="font-bold"><?= $status === 'success' ? 'Berhasil!' : 'Gagal!' ?></strong>
            <span class="block sm:inline">
                <?= $status === 'success' ? 'Transaksi berhasil disimpan.' : htmlspecialchars($msg) ?>
            </span>
        </div>
    </div>
<?php endif; ?>
        <h1 class="text-2xl font-bold mb-4">Simpan Transaksi</h1>

        <form action="simpan_transaksi.php" method="POST">
            <!-- Hidden field untuk kasir_id -->
            <input type="hidden" name="kasir_id" value="<?= $_SESSION['user_id']; ?>">

            <div class="mb-4 flex flex-wrap gap-4">
                <select id="produk" class="border p-2" required>
                    <option disabled selected>Pilih Produk</option>
                    <?php while ($p = $produk->fetch_assoc()): ?>
                        <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>">
                            <?= $p['nama'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="number" id="jumlah" placeholder="Jumlah" class="border p-2" min="1" required>
                <button type="button" onclick="tambahProduk()" class="bg-purple-500 text-white px-4 py-2 rounded">+
                    Tambah</button>
            </div>

            <table class="w-full table-auto border border-collapse mb-4" id="keranjang">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Produk</th>
                        <th class="border p-2">Harga</th>
                        <th class="border p-2">Jumlah</th>
                        <th class="border p-2">Total Harga</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div class="mb-4">
                <label for="metode">Metode Pembayaran: </label>
                <select name="metode_pembayaran" required class="border p-2">
                    <option value="tunai">Tunai</option>
                    <option value="non-tunai">Non-Tunai</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Total: </label>
                <input type="text" id="total" name="total" value="0" readonly class="border p-2">
            </div>

            <button type="submit" class="bg-purple-500 text-white px-6 py-2 rounded">Simpan Transaksi</button>
        </form>
    </div>
    <script src="../assets/js/sidebar.js"></script>
</body>

</html>