<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cari user berdasarkan email
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);

    // Jika email ditemukan dan password cocok
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        echo "<script>alert('Login berhasil! Selamat datang, ".$user['nama']."');window.location='home.php';</script>";
        exit;
    } else {
        echo "<script>alert('Email atau Password salah!');window.location='login_view.php';</script>";
        exit;
    }
} else {
    header("Location: login_view.php");
    exit;
}
?>
