<?php
session_start();
include 'connect.php'; // Pastikan koneksi database benar

if (!isset($_SESSION['id'])) {
    header('location: menu_login.php');
    exit();
}

$id = $_SESSION['id'];
$pin_session = $_SESSION['pin'] ?? '';

// Cek User
$stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<script>alert('User tidak ditemukan!'); window.location='menu_login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $konfirm_pin = $_POST['konfirm_pin'];
    $pin_asli = $user['pin']; 

    if ($konfirm_pin === $pin_asli) {
        // --- LOGIKA UPDATE DATABASE DIMULAI DI SINI ---
        
        // 1. Ambil data dari session (dari konfirmasi_topup.php)
        if (!isset($_SESSION['topup_nominal'])) {
            echo "<script>alert('Sesi habis, ulangi transaksi.'); window.location='topup.php';</script>";
            exit();
        }

        $nominal = $_SESSION['topup_nominal'];
        $metode_raw = $_SESSION['topup_metode']; // Isinya misal "Transfer Bank"
        
        // 2. Mapping Metode Pembayaran agar sesuai ENUM Database
        // Database ENUM: 'bank_transfer','virtual_akun','minimarket'
        $metode_db = '';
        if ($metode_raw == 'Transfer Bank') {
            $metode_db = 'bank_transfer';
        } elseif ($metode_raw == 'Virtual Account') {
            $metode_db = 'virtual_akun';
        } else {
            $metode_db = 'minimarket';
        }

        $saldo_sebelum = $user['saldo'];
        $saldo_sesudah = $saldo_sebelum + $nominal;

        // Mulai Transaksi Database agar aman
        $connection->begin_transaction();

        try {
            // A. Update Saldo User
            $stmt = $connection->prepare("UPDATE users SET saldo = ? WHERE id = ?");
            $stmt->bind_param("di", $saldo_sesudah, $id);
            $stmt->execute();

            // B. Insert ke tabel 'transactions'
            // Perhatikan: tabel transactions tidak punya kolom metode_pembayaran, jadi jangan dimasukkan di sini
            $stmt = $connection->prepare("
                INSERT INTO transactions (user_id, jenis_transaksi, nominal, saldo_sebelum, saldo_sesudah, status)
                VALUES (?, 'top_up', ?, ?, ?, 'success')
            ");
            $stmt->bind_param("iddd", $id, $nominal, $saldo_sebelum, $saldo_sesudah);
            $stmt->execute();
            
            // Ambil ID transaksi yang baru saja dibuat
            $transaction_id = $connection->insert_id;

            // C. Insert ke tabel 'topup_history'
            $stmt = $connection->prepare("
                INSERT INTO topup_history (transaction_id, user_id, metode_pembayaran)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("iis", $transaction_id, $id, $metode_db);
            $stmt->execute();

            // Jika semua lancar, simpan perubahan
            $connection->commit();
            
            // Hapus session
            unset($_SESSION['topup_nominal']);
            unset($_SESSION['topup_metode']);

            echo "<script>
                alert('Top Up Berhasil sebesar Rp " . number_format($nominal,0,',','.') . "');
                window.location='dashboard.php';
            </script>";
            exit();

        } catch (Exception $e) {
            $connection->rollback();
            echo "<script>alert('Gagal: " . $e->getMessage() . "'); window.location='topup.php';</script>";
            exit();
        }

    } else {
        echo "<script>
            alert('PIN Salah!');
            window.location='verifikasi_pin_topup.php';
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
        body { background: #0d6efd; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .card { width: 100%; max-width: 400px; padding: 30px; border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); }
        .lock-icon { font-size: 4rem; color: #667eea; margin-bottom: 20px; }
        input[type="password"] { text-align: center; letter-spacing: 8px; font-size: 24px; font-weight: bold; padding: 15px; border-radius: 10px; }
        .btn-primary { background: #0d6efd; border: none; padding: 12px; border-radius: 10px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="text-center">
            <i class="bi bi-shield-lock-fill lock-icon"></i>
            <h3 class="mb-2">🔒 Verifikasi PIN</h3>
            <p class="text-muted">Masukkan PIN Anda untuk konfirmasi Top Up</p>
        </div>
        <form method="POST" autocomplete="off">
            <div class="mb-4">
                <input type="password" name="konfirm_pin" maxlength="6" pattern="[0-9]{6}" class="form-control" placeholder="••••••" autofocus required>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold">Verifikasi PIN</button>
            <a href="konfirmasi_topup.php" class="btn btn-outline-secondary w-100 mt-2">Kembali</a>
        </form>
    </div>
</body>
</html>