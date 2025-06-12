<?php
session_start();
session_destroy(); // hapus semua data session
header("Location: ../index.php"); // kembali ke halaman login
exit;
?>
