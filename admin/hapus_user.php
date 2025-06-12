<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

require '../includes/db.php';

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'ID user tidak valid'];
    header("Location: users.php");
    exit;
}

$id = intval($_GET['id']);

// Cegah admin menghapus dirinya sendiri
if (isset($_SESSION['id']) && $_SESSION['id'] == $id) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Tidak dapat menghapus akun sendiri'];
    header("Location: users.php");
    exit;
}

// Cek apakah user ada
$user = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
if (!$user) {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'User tidak ditemukan'];
    header("Location: users.php");
    exit;
}

// Hapus user
$delete = $conn->query("DELETE FROM users WHERE id=$id");

if ($delete) {
    $_SESSION['message'] = ['type' => 'success', 'text' => 'Akun berhasil dihapus'];
} else {
    $_SESSION['message'] = ['type' => 'error', 'text' => 'Gagal menghapus Akun'];
}

header("Location: users.php");
exit;
?>