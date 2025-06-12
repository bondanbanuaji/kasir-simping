<?php
session_start();
if ($_SESSION['role'] != 'pemilik')
    header("Location: ../index.php");
require '../includes/db.php';

$dari = $_GET['dari'] ?? date('Y-m-01');
$sampai = $_GET['sampai'] ?? date('Y-m-d');

$query = $conn->prepare("SELECT transaksi.*, user.username FROM transaksi 
                         JOIN user ON user.id = transaksi.kasir_id 
                         WHERE tanggal BETWEEN ? AND ?");
$query->bind_param("ss", $dari, $sampai);
$query->execute();
$result = $query->get_result();

$total_omset = 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Transaksi</h1>

    <form method="GET" class="mb-4 flex gap-4 items-end">
        <div>
            <label>Dari Tanggal</label>
            <input type="date" name="dari" value="<?= $dari ?>" class="border p-2">
        </div>
        <div>
            <label>Sampai Tanggal</label>
            <input type="date" name="sampai" value="<?= $sampai ?>" class="border p-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <table class="w-full border table-auto border-collapse mb-4">
        <thead class="bg-gray-200">
            <tr>
                <th class="border p-2">Tanggal</th>
                <th class="border p-2">Kasir</th>
                <th class="border p-2">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()):
                $total_omset += $row['total'];
                ?>
                <tr>
                    <td class="border p-2"><?= $row['tanggal'] ?></td>
                    <td class="border p-2"><?= $row['username'] ?></td>
                    <td class="border p-2">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot class="bg-gray-100 font-bold">
            <tr>
                <td class="border p-2 text-right" colspan="2">Total Omset</td>
                <td class="border p-2">Rp <?= number_format($total_omset, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>