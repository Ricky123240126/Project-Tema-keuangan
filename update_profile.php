<?php
session_start();
include "connect.php";

if (!isset($_SESSION['id'])) {
    header("Location: menu_login.php");
    exit();
}

$id = $_SESSION['id'];
$query = "SELECT * FROM users WHERE id='$id'";
$result = $connection->query($query);
$user = $result->fetch_assoc();

// Jika user sudah punya PIN, tidak boleh buka halaman ini lagi
if (!empty($user['pin'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pin = $_POST['pin'];

    if (strlen($pin) != 6) {
        echo "<script>alert('PIN harus 6 digit!');</script>";
    } else {
        $query = "UPDATE users SET pin='$pin' WHERE id='$id'";
        if ($connection->query($query)) {
            $_SESSION['pin'] = $pin;
            echo "<script>alert('PIN berhasil disimpan!'); window.location='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan PIN!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #e6f2ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 400px;
            padding: 20px;
            border-radius: 15px;
        }

        input {
            text-align: center;
            letter-spacing: 5px;
            font-size: 20px;
        }
    </style>
</head>

<body>

    <div class="card shadow-lg">
        <h3 class="text-center mb-2">🔒 Lengkapi Akun</h3>
        <p class="text-center text-muted">Buat PIN sebelum melanjutkan</p>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">PIN (6 digit)</label>
                <input type="password" name="pin" maxlength="6" class="form-control" placeholder="••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan PIN</button>
            <a href="profil.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                <i class="bi bi-pencil me-1"></i> kembali
            </a>
        </form>

        <p class="text-center mt-3 text-muted" style="font-size: 12px">
            PIN akan digunakan saat transaksi untuk keamanan.
        </p>
    </div>

</body>

</html>