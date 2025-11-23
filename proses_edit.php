<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('location: menu_login.php');
    exit();
}

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email='$email";
$result = $connection->query($query);
$user = $result->fetch_assoc();

if (empty($user['pin'])) {
    echo "<script><alert>Silahkan lengkapi pin terlebih dahulu!; window.location.href='update_profile.php';</alert></script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pin = $_POST['pin'];
    $nomor = $_POST['nomor'];
    $tanggal = $_POST['tanggal'];
    
    if (strlen($pin) != 6) {
        echo "<script>alert('PIN harus 6 digit!');</script>";
    } else {
        $query = "UPDATE users SET nama='$username',pin='$pin',nomor_telepon='$nomor', tanggal_lahir='$tanggal'  WHERE email='$email'";
        if ($connection->query($query)) {
            echo "<script>alert('Update Profil Berhasil!'); window.location='profil.php';</script>";
            exit();
        } else {
            echo "<script>alert('Update Profil Gagal!');</script>";
        }
    }
}

?>