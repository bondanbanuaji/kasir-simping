<?php
session_start();
if (!isset($_SESSION['role'])) {
    header("Location: index.php");
    exit;
}

switch ($_SESSION['role']) {
    case 'admin':
        header("Location: admin/dashboard.php");
        break;
    case 'kasir':
        header("Location: kasir/dashboard.php");
        break;
    case 'pemilik':
        header("Location: pemilik/laporan.php");
        break;
    default:
        echo "Role tidak dikenal.";
}
?>