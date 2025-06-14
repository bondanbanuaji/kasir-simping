<?php
session_start();
require '../includes/db.php';

$username = $_POST['username'];
$password = $_POST['password']; // tidak pakai md5 / hash

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['username'] = $user['username'];
  $_SESSION['role'] = $user['role'];

  header("Location: ../dashboard.php");
} else {
  $_SESSION['error'] = "Username atau Password salah!";
  header("Location: ../index.php");
}
?>
