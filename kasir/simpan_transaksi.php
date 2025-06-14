<?php
session_start();
if ($_SESSION['role'] !== 'kasir') {
    header("Location: ../index.php");
    exit;
}

require '../includes/db.php';
date_default_timezone_set('Asia/Jakarta');

try {
    $produk_ids = $_POST['produk_id'] ?? [];
    $jumlahs = $_POST['jumlah'] ?? [];
    $hargas = $_POST['harga'] ?? [];
    $total = $_POST['total'] ?? 0;
    $metode_pembayaran = $_POST['metode_pembayaran'] ?? '';
    $kasir_id = $_SESSION['user_id'];
    $tgl_transaksi = date("Y-m-d");
    $no_invoice = 'INV' . time() . rand(100, 999);
    $id_produk = $produk_ids[0] ?? null;

    if (empty($produk_ids) || $id_produk === null) {
        throw new Exception("Produk belum dipilih.");
    }

    // Simpan ke tabel transaksi
    $stmt = $conn->prepare("INSERT INTO transaksi (tgl_transaksi, total, kasir_id, metode_pembayaran, no_invoice, id_produk) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisss", $tgl_transaksi, $total, $kasir_id, $metode_pembayaran, $no_invoice, $id_produk);
    $stmt->execute();
    $transaksi_id = $conn->insert_id;

    // Detail transaksi
    $stmt_detail = $conn->prepare("INSERT INTO detail_transaksi (transaksi_id, produk_id, jumlah, harga, total) VALUES (?, ?, ?, ?, ?)");
    $stmt_update = $conn->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?");

    for ($i = 0; $i < count($produk_ids); $i++) {
        $jumlah = (int)$jumlahs[$i];
        $harga = (float)$hargas[$i];
        $total_per_item = $jumlah * $harga;
        $stmt_detail->bind_param("iiidd", $transaksi_id, $produk_ids[$i], $jumlah, $harga, $total_per_item);
        $stmt_detail->execute();

        // Update stok
        $stmt_update->bind_param("ii", $jumlah, $produk_ids[$i]);
        $stmt_update->execute();
    }

    header("Location: transaksi.php?status=success");
    exit;
} catch (Exception $e) {
    header("Location: transaksi.php?status=error&msg=" . urlencode($e->getMessage()));
    exit;
}
