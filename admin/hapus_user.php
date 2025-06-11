<?php
session_start();
if ($_SESSION['role'] != 'admin') header("Location: ../index.php");
require '../includes/db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id=$id");
header("Location: users.php");
?>
