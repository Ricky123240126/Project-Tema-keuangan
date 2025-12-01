<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['id'])) {
    header("location: menu_login.php");
    exit();
}

if (!isset($_SESSION['pin']) || $_SESSION['pin'] === null || $_SESSION['pin'] === '' || empty($_SESSION['pin'])) {
    echo "<script>
        alert('Silahkan lengkapi PIN terlebih dahulu!'); 
        window.location='update_profile.php';
    </script>";
    exit();
}
// Ambil data dari form topup.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nominal = $_POST['nominal'];
    $metode = '';
    
    if (isset($_POST['transfer_bank'])) {
        $metode = 'Transfer Bank';
    } elseif (isset($_POST['virtual_akun'])) {
        $metode = 'Virtual Account';
    } elseif (isset($_POST['minimarket'])) {
        $metode = 'Minimarket';
    }
    
    // Validasi
    if (empty($nominal) || $nominal <= 0) {
        echo "<script>alert('Nominal tidak valid!'); window.location='topup.php';</script>";
        exit();
    }
    
    if (empty($metode)) {
        echo "<script>alert('Pilih metode pembayaran!'); window.location='topup.php';</script>";
        exit();
    }
    
    // Simpan ke session
    $_SESSION['topup_nominal'] = $nominal;
    $_SESSION['topup_metode'] = $metode;
} else {
    header("location: topup.php");
    exit();
}

// Ambil data user
$user_id = $_SESSION['id'];
$stmt = $connection->prepare("SELECT nama, email, saldo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Hitung biaya admin (contoh: 2500)
$biaya_admin = 2500;
$total_bayar = $nominal + $biaya_admin;
$saldo_setelah = $user['saldo'] + $nominal;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Top Up - MyWallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            margin: 0 auto;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px;
        }

        .detail-row {
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .label {
            color: #6c757d;
            font-size: 14px;
        }

        .value {
            font-weight: 600;
            font-size: 16px;
            color: #212529;
        }

        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }

        .total-label {
            font-size: 14px;
            color: #6c757d;
        }

        .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .badge-metode {
            background: #e7f3ff;
            color: #0d6efd;
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-weight: 600;
            color: white;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                <i class="bi bi-cash-coin fs-1"></i>
                <h4 class="mt-2 mb-0">Konfirmasi Top Up</h4>
            </div>

            <div class="card-body p-4">
                <div class="detail-row">
                    <div class="label">Nama Akun</div>
                    <div class="value"><?= htmlspecialchars($user['nama']) ?></div>
                </div>

                <div class="detail-row">
                    <div class="label">Email</div>
                    <div class="value"><?= htmlspecialchars($user['email']) ?></div>
                </div>

                <div class="detail-row">
                    <div class="label">Saldo Saat Ini</div>
                    <div class="value">Rp <?= number_format($user['saldo'], 0, ',', '.') ?></div>
                </div>

                <div class="detail-row">
                    <div class="label">Nominal Top Up</div>
                    <div class="value text-primary">Rp <?= number_format($nominal, 0, ',', '.') ?></div>
                </div>

                <div class="detail-row">
                    <div class="label">Metode Pembayaran</div>
                    <div class="value">
                        <span class="badge-metode">
                            <i class="bi bi-credit-card me-1"></i>
                            <?= htmlspecialchars($metode) ?>
                        </span>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="label">Biaya Admin</div>
                    <div class="value text-danger">Rp <?= number_format($biaya_admin, 0, ',', '.') ?></div>
                </div>

                <div class="total-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="total-label">Total Pembayaran</div>
                            <div class="total-value">Rp <?= number_format($total_bayar, 0, ',', '.') ?></div>
                        </div>
                        <i class="bi bi-arrow-right-circle-fill text-primary fs-1"></i>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="label">Saldo Setelah Top Up</div>
                    <div class="value text-success">
                        <i class="bi bi-arrow-up-circle-fill me-1"></i>
                        Rp <?= number_format($saldo_setelah, 0, ',', '.') ?>
                    </div>
                </div>

                <div class="info-box">
                    <small>
                        <i class="bi bi-info-circle-fill me-1"></i>
                        <strong>Perhatian:</strong> Pastikan semua data sudah benar. Setelah mengklik tombol konfirmasi, Anda akan diminta memasukkan PIN untuk keamanan transaksi.
                    </small>
                </div>

                <form action="verifikasi_pin_topup.php" method="POST" class="mt-4">
                    <button type="submit" class="btn btn-confirm w-100">
                        <i class="bi bi-shield-lock-fill me-2"></i>
                        Lanjutkan ke Verifikasi PIN
                    </button>
                </form>

                <a href="topup.php" class="btn btn-outline-secondary w-100 mt-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>