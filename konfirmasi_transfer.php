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

// Ambil data dari form transfer.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_penerima = $_POST['email'];
    $nominal = $_POST['nominal'];
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : '';
    
    // Validasi nominal
    if (empty($nominal) || $nominal <= 0) {
        echo "<script>alert('Nominal tidak valid!'); window.location='transfer.php';</script>";
        exit();
    }
    
    // Cek penerima
    $stmt = $connection->prepare("SELECT id, nama, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email_penerima);
    $stmt->execute();
    $penerima = $stmt->get_result()->fetch_assoc();
    
    if (!$penerima) {
        echo "<script>alert('Penerima tidak ditemukan!'); window.location='transfer.php';</script>";
        exit();
    }
    
    // Cek jika transfer ke diri sendiri
    if ($penerima['id'] == $_SESSION['id']) {
        echo "<script>alert('Tidak dapat transfer ke akun sendiri!'); window.location='transfer.php';</script>";
        exit();
    }
    
    // Simpan ke session
    $_SESSION['transfer_penerima_id'] = $penerima['id'];
    $_SESSION['transfer_penerima_nama'] = $penerima['nama'];
    $_SESSION['transfer_penerima_email'] = $penerima['email'];
    $_SESSION['transfer_nominal'] = $nominal;
    $_SESSION['transfer_catatan'] = $catatan;
} else {
    header("location: transfer.php");
    exit();
}

// Ambil data pengirim
$user_id = $_SESSION['id'];
$stmt = $connection->prepare("SELECT nama, email, saldo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pengirim = $stmt->get_result()->fetch_assoc();

// Validasi saldo
if ($pengirim['saldo'] < $nominal) {
    echo "<script>alert('Saldo Anda tidak mencukupi!'); window.location='transfer.php';</script>";
    exit();
}

$saldo_setelah = $pengirim['saldo'] - $nominal;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Transfer - MyWallet</title>
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

        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            margin-top: 25px;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .user-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
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

        .amount-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
        }

        .amount-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .amount-value {
            font-size: 32px;
            font-weight: bold;
        }

        .catatan-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
        }

        .saldo-info {
            background: #d1ecf1;
            border-left: 4px solid #0dcaf0;
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
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

        .arrow-icon {
            font-size: 30px;
            color: #667eea;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>

<body>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                <i class="bi bi-arrow-left-right fs-1"></i>
                <h4 class="mt-2 mb-0">Konfirmasi Transfer</h4>
            </div>

            <div class="card-body p-4">
                
                <!-- Pengirim -->
                <div class="section-title">
                    <i class="bi bi-person-fill me-1"></i> DARI
                </div>
                <div class="user-card">
                    <div class="d-flex align-items-center">
                        <div class="user-icon">
                            <?= strtoupper(substr($pengirim['nama'], 0, 1)) ?>
                        </div>
                        <div class="ms-3">
                            <div class="fw-bold"><?= htmlspecialchars($pengirim['nama']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($pengirim['email']) ?></small>
                        </div>
                    </div>
                </div>

                <div class="arrow-icon">
                    <i class="bi bi-arrow-down-circle-fill"></i>
                </div>

                <!-- Penerima -->
                <div class="section-title">
                    <i class="bi bi-person-check-fill me-1"></i> KEPADA
                </div>
                <div class="user-card">
                    <div class="d-flex align-items-center">
                        <div class="user-icon">
                            <?= strtoupper(substr($penerima['nama'], 0, 1)) ?>
                        </div>
                        <div class="ms-3">
                            <div class="fw-bold"><?= htmlspecialchars($penerima['nama']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($penerima['email']) ?></small>
                        </div>
                    </div>
                </div>

                <!-- Nominal -->
                <div class="amount-section">
                    <div class="amount-label">JUMLAH TRANSFER</div>
                    <div class="amount-value">Rp <?= number_format($nominal, 0, ',', '.') ?></div>
                </div>

                <!-- Catatan -->
                <?php if (!empty($catatan)): ?>
                <div class="catatan-box">
                    <div class="label mb-1">
                        <i class="bi bi-chat-left-text-fill me-1"></i> Catatan
                    </div>
                    <div class="value"><?= htmlspecialchars($catatan) ?></div>
                </div>
                <?php endif; ?>

                <!-- Info Saldo -->
                <div class="saldo-info">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="label">Saldo Saat Ini:</span>
                        <span class="value">Rp <?= number_format($pengirim['saldo'], 0, ',', '.') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="label">Jumlah Transfer:</span>
                        <span class="value text-danger">- Rp <?= number_format($nominal, 0, ',', '.') ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="label">Saldo Setelah Transfer:</span>
                        <span class="value text-success fw-bold">Rp <?= number_format($saldo_setelah, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <small>
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>Perhatian:</strong> Pastikan data penerima sudah benar. Transaksi yang sudah diproses tidak dapat dibatalkan.
                    </small>
                </div>

                <form action="verifikasi_pin_transfer.php" method="POST" class="mt-4">
                    <button type="submit" class="btn btn-confirm w-100">
                        <i class="bi bi-shield-lock-fill me-2"></i>
                        Lanjutkan ke Verifikasi PIN
                    </button>
                </form>

                <a href="transfer.php" class="btn btn-outline-secondary w-100 mt-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>