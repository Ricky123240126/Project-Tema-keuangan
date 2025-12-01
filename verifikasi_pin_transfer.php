<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['transfer_nominal'])) {
    header('location: transfer.php');
    exit();
}

$user_id = $_SESSION['id'];

// Ambil PIN User Pengirim
$stmt = $connection->prepare("SELECT pin, saldo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (isset($_POST['konfirm_pin'])) {
    $pin_input = $_POST['konfirm_pin'];
    $pin_asli = $user['pin'];

    // Cek PIN
    if ($pin_input !== $pin_asli) {
        echo "<script>alert('PIN Salah!'); window.location='verifikasi_pin_transfer.php';</script>";
        exit();
    }

    // Persiapan Data
    $pengirim_id = $user_id;
    $penerima_id = $_SESSION['transfer_penerima_id'];
    $nominal = $_SESSION['transfer_nominal'];
    $catatan = $_SESSION['transfer_catatan'];

    // Cek Saldo lagi untuk keamanan
    if ($user['saldo'] < $nominal) {
        echo "<script>alert('Saldo tidak mencukupi!'); window.location='transfer.php';</script>";
        exit();
    }

    // Ambil Saldo Penerima
    $stmt = $connection->prepare("SELECT saldo FROM users WHERE id = ?");
    $stmt->bind_param("i", $penerima_id);
    $stmt->execute();
    $penerima = $stmt->get_result()->fetch_assoc();

    if (!$penerima) {
        echo "<script>alert('Penerima tidak ditemukan!'); window.location='transfer.php';</script>";
        exit();
    }

    // Hitung Saldo Baru
    $saldo_pengirim_baru = $user['saldo'] - $nominal;
    $saldo_penerima_baru = $penerima['saldo'] + $nominal;

    // --- MULAI TRANSAKSI DATABASE ---
    $connection->begin_transaction();

    try {
        // 1. Kurangi Saldo Pengirim
        $stmt = $connection->prepare("UPDATE users SET saldo = ? WHERE id = ?");
        $stmt->bind_param("di", $saldo_pengirim_baru, $pengirim_id);
        $stmt->execute();

        // 2. Tambah Saldo Penerima
        $stmt = $connection->prepare("UPDATE users SET saldo = ? WHERE id = ?");
        $stmt->bind_param("di", $saldo_penerima_baru, $penerima_id);
        $stmt->execute();

        // 3. Catat di tabel 'transactions' (Log Pengirim - Uang Keluar)
        // Note: penerima_id dicatat di sini sesuai struktur DB Anda
        $stmt = $connection->prepare("
            INSERT INTO transactions (user_id, jenis_transaksi, nominal, saldo_sebelum, saldo_sesudah, penerima_id, status)
            VALUES (?, 'transfer', ?, ?, ?, ?, 'success')
        ");
        $stmt->bind_param("idddi", $pengirim_id, $nominal, $user['saldo'], $saldo_pengirim_baru, $penerima_id);
        $stmt->execute();
        
        $transaction_id = $connection->insert_id;

        // 4. Catat di tabel 'transfer_history'
        $stmt = $connection->prepare("
            INSERT INTO transfer_history (transaction_id, pengirim_id, penerima_id, nominal, catatan)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiids", $transaction_id, $pengirim_id, $penerima_id, $nominal, $catatan);
        $stmt->execute();

        // // 5. (Opsional tapi Bagus) Catat transaksi untuk Penerima juga (Uang Masuk)
        // // Agar penerima bisa lihat history saldo bertambah.
        // // Karena ENUM Anda hanya punya 'top_up' dan 'transfer', kita pakai 'transfer'
        // $stmt = $connection->prepare("
        //     INSERT INTO transactions (user_id, jenis_transaksi, nominal, saldo_sebelum, saldo_sesudah, penerima_id, status)
        //     VALUES (?, 'transfer', ?, ?, ?, ?, 'success')
        // ");
        // // Di sini user_id adalah penerima, penerima_id adalah pengirim (sumber dana)
        // $stmt->bind_param("idddi", $penerima_id, $nominal, $penerima['saldo'], $saldo_penerima_baru, $pengirim_id);
        // $stmt->execute();

        // Komit Transaksi
        $connection->commit();

        // Bersihkan Session
        unset($_SESSION['transfer_penerima_id']);
        unset($_SESSION['transfer_nominal']);
        unset($_SESSION['transfer_catatan']);
        unset($_SESSION['transfer_penerima_nama']);
        unset($_SESSION['transfer_penerima_email']);

        echo "<script>alert('Transfer Berhasil!'); window.location='dashboard.php';</script>";
        exit();

    } catch (Exception $e) {
        $connection->rollback();
        echo "<script>alert('Transaksi Gagal: " . $e->getMessage() . "'); window.location='transfer.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi PIN Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Masukkan CSS Anda disini */
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { width: 100%; max-width: 400px; padding: 30px; border-radius: 20px; }
    </style>
</head>
<body>
    <div class="card bg-white">
        <h3 class="text-center mb-4">Masukkan PIN</h3>
        <form method="POST">
            <input type="password" name="konfirm_pin" class="form-control text-center fs-3 mb-3" maxlength="6" required autofocus placeholder="******">
            <button type="submit" class="btn btn-primary w-100">Konfirmasi</button>
        </form>
    </div>
</body>
</html>