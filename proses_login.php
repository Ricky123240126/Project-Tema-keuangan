<?php
include 'connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi input tidak kosong
    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = 'Email dan password harus diisi!';
        $_SESSION['login_email'] = $email;
        header("Location: menu_login.php");
        exit();
    }

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['login_error'] = 'Format email tidak valid!';
        $_SESSION['login_email'] = $email;
        header("Location: menu_login.php");
        exit();
    }

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

            unset($_SESSION['login_error']);
            unset($_SESSION['login_email']);

            $stmt->close();
            $connection->close();
            
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Password yang Anda masukkan salah. Silakan coba lagi.';
            $_SESSION['login_email'] = $email;
            
            $stmt->close();
            $connection->close();
            
            header("Location: menu_login.php");
            exit();
        }
    } else {
        // Email tidak ditemukan
        $_SESSION['login_error'] = 'Email tidak terdaftar. Pastikan email Anda sudah benar atau daftar terlebih dahulu.';
        $_SESSION['login_email'] = $email;
        
        $stmt->close();
        $connection->close();
        
        header("Location: menu_login.php");
        exit();
    }
} else {
    header("Location: menu_login.php");
    exit();
}
?>