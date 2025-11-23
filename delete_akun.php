<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['id'])) {
    header("location: menu_login.php");
    exit();
}

$id = $_SESSION['id'];

$stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    session_destroy();
    echo "<script>alert('Akun berhasil dihapus'); window.location='menu_login.php';</script>";
    exit();
} else {
    echo "<script>alert('Gagal menghapus akun'); window.location='dashboard.php';</script>";
}
?>
