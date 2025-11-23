<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("location: menu_login.php");
    exit();
}

$email = $_SESSION['email'];
$stmt = $connection->prepare("UPDATE users SET status = 'inactive' WHERE email = ?");
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    session_destroy();
    header("location: menu_login.php");
    exit();
} else {
    echo "<script>alert('Gagal menonaktifkan akun!'); window.location='profil.php';</script>";
}
