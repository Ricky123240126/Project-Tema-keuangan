<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['id'])) {
    header('location: menu_login.php');
    exit();
}


if (!isset($_SESSION['pin']) || $_SESSION['pin'] === null || $_SESSION['pin'] === '') {
    echo "<script>
        alert('PIN Anda belum diatur! Silakan atur PIN terlebih dahulu.');
        window.location='update_profile.php';
    </script>";
    exit();
}

$id = $_SESSION['id'];

$stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>
        alert('Data user tidak ditemukan!');
        window.location='menu_login.php';
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $konfirm_pin = $_POST['konfirm_pin'];
    $pin_asli = $user['pin']; 

    if (!preg_match('/^\d{6}$/', $konfirm_pin)) {
        echo "<script>
            alert('PIN harus 6 digit angka!');
            window.location='verikasi_pin_Profil_pin.php';
        </script>";
        exit();
    }

    if ($konfirm_pin === $pin_asli) {
        $_SESSION['pin_verified'] = true;
        $_SESSION['pin_verified_time'] = time(); 
        
        echo "<script>
            alert('PIN terverifikasi! Silakan lanjut ke menu update profil.');
            window.location='edit_profile.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('PIN yang Anda masukkan salah!');
            window.location='verify_pin.php';
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi PIN - MyWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background: #0d6efd;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .lock-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        input[type="password"] {
            text-align: center;
            letter-spacing: 8px;
            font-size: 24px;
            font-weight: bold;
            padding: 15px;
            border-radius: 10px;
        }

        input[type="password"]::placeholder {
            letter-spacing: 3px;
            font-size: 20px;
        }

        .btn-primary {
            background: #0d6efd;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: #0d6efd;
            transform: translateY(-2px);
        }

        .security-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="text-center">
            <i class="bi bi-shield-lock-fill lock-icon"></i>
            <h3 class="mb-2">🔒 Verifikasi PIN</h3>
            <p class="text-muted">Masukkan PIN Anda untuk melanjutkan</p>
        </div>

        <form method="POST" autocomplete="off">
            <div class="mb-4">
                <label class="form-label fw-bold">
                    <i class="bi bi-lock-fill me-1"></i> PIN (6 digit)
                </label>
                <input 
                    type="password" 
                    name="konfirm_pin" 
                    maxlength="6" 
                    pattern="[0-9]{6}"
                    class="form-control" 
                    placeholder="••••••" 
                    autofocus
                    required>
                <small class="text-muted">Masukkan 6 digit angka PIN Anda</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold">
                <i class="bi bi-check-circle me-1"></i> Verifikasi PIN
            </button>

            <a href="profil.php" class="btn btn-outline-secondary w-100 mt-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </form>

        <div class="security-info">
            <small class="text-muted">
                <i class="bi bi-info-circle-fill me-1"></i>
                <strong>Keamanan Akun:</strong><br>
                PIN digunakan untuk verifikasi transaksi dan perubahan data sensitif.
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>