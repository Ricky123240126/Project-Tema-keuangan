<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            //update status akun menjadi 'active'
            $stmt_update = $connection->prepare("UPDATE users SET status = 'active' WHERE email = ?");
            $stmt_update->bind_param("s", $email);
            $stmt_update->execute();
            $stmt_update->close();

            $_SESSION['username'] = $user['nama'];
            $_SESSION['saldo'] = $user['saldo'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['no_hp'] = $user['no_hp'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['tanggal_daftar'] = $user['tanggal_daftar'];
            $_SESSION['pin'] = $user['pin'];
            $_SESSION['tanggal_lahir'] = $user['tanggal_lahir'];
            $_SESSION['status'] = 'active';
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Password salah!'); window.location.href='menu_login.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.location.href='menu_login.php';</script>";
    }

    $stmt->close();
    $connection->close();
} else {
    header("Location: login_view.php");
    exit();
}
?>