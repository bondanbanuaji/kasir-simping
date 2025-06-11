<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM produk WHERE id=$id");
header("Location: produk.php");
?>
