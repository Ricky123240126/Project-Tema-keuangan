<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['id'])) {
    header("location: menu_login.php");
    exit();
}

$pengirim_id = $_SESSION['id'];
$email = $_POST['email'];
$nominal = $_POST['nominal'];
$catatan = $_POST['catatan'];

// Cek penerima
$stmt = $connection->prepare("SELECT id, saldo FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$penerima = $stmt->get_result()->fetch_assoc();

if (!$penerima) {
    echo "<script>alert('Penerima tidak ditemukan'); window.location='transfer.php';</script>";
    exit();
}

$penerima_id = $penerima['id'];

// Ambil saldo pengirim
$stmt = $connection->prepare("SELECT saldo FROM users WHERE id=?");
$stmt->bind_param("i", $pengirim_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$saldo_pengirim = $row['saldo'];

if ($saldo_pengirim < $nominal) {
    echo "<script>alert('Saldo tidak cukup'); window.location='transfer.php';</script>";
    exit();
}

$saldo_pengirim_baru = $saldo_pengirim - $nominal;
$saldo_penerima_baru = $penerima['saldo'] + $nominal;

// 1️⃣ Simpan transaksi
$stmt = $connection->prepare("
    INSERT INTO transactions (user_id, jenis_transaksi, nominal, saldo_sebelum, saldo_sesudah, penerima_id, status)
    VALUES (?, 'transfer', ?, ?, ?, ?, 'success')
");
$stmt->bind_param("idddi", $pengirim_id, $nominal, $saldo_pengirim, $saldo_pengirim_baru, $penerima_id);
$stmt->execute();

$transaction_id = $connection->insert_id;

// 2️⃣ Simpan riwayat transfer
$stmt = $connection->prepare("
    INSERT INTO transfer_history (transaction_id, pengirim_id, penerima_id, nominal, catatan)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("iiids", $transaction_id, $pengirim_id, $penerima_id, $nominal, $catatan);
$stmt->execute();

// 3️⃣ Update saldo pengirim dan penerima
$stmt = $connection->prepare("UPDATE users SET saldo=? WHERE id=?");
$stmt->bind_param("di", $saldo_pengirim_baru, $pengirim_id);
$stmt->execute();

$stmt = $connection->prepare("UPDATE users SET saldo=? WHERE id=?");
$stmt->bind_param("di", $saldo_penerima_baru, $penerima_id);
$stmt->execute();

echo "<script>alert('Transfer Berhasil!'); window.location='dashboard.php';</script>";
exit();
