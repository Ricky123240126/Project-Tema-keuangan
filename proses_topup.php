<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['id'])) {
    header("location: menu_login.php");
    exit();
}

$user_id = $_SESSION['id'];
$nominal = $_POST['nominal'];

// Ambil user
$stmt = $connection->prepare("SELECT saldo, pin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// Verifikasi PIN
if ($pin_input !== $data['pin']) {
    echo "<script>alert('PIN salah!'); window.location='topup.php';</script>";
    exit();
}

$saldo_before = $data['saldo'];
$saldo_after = $saldo_before + $nominal;

// Insert ke transactions
$stmt = $connection->prepare("
    INSERT INTO transactions (user_id, jenis_transaksi, nominal, saldo_sebelum, saldo_sesudah, status)
    VALUES (?, 'top_up', ?, ?, ?, 'success')
");
$stmt->bind_param("iddd", $user_id, $nominal, $saldo_before, $saldo_after);
$stmt->execute();

$transaction_id = $stmt->insert_id;

// Insert ke topup_history
$stmt = $connection->prepare("
    INSERT INTO topup_history (transaction_id, user_id, metode_pembayaran)
    VALUES (?, ?, 'bank_transfer')
");
$stmt->bind_param("ii", $transaction_id, $user_id);
$stmt->execute();

// Update saldo user
$stmt = $connection->prepare("UPDATE users SET saldo = ? WHERE id = ?");
$stmt->bind_param("di", $saldo_after, $user_id);
$stmt->execute();

// Update session
$_SESSION['saldo'] = $saldo_after;

echo "<script>alert('Permintaan top-up diproses! silahkan konfirmasi detail transaksi'); window.location='dashboard.php';</script>";
?>
