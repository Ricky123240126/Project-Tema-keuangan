<?php
session_start();
include 'connect.php';

// 1. Cek Login
if (!isset($_SESSION['id'])) {
    header('location: menu_login.php');
    exit();
}

$user_id = $_SESSION['id'];

// 2. AMBIL SALDO TERBARU (Real-time Update)
// Ini memperbaiki masalah saldo tidak berubah setelah transaksi
$stmt_saldo = $connection->prepare("SELECT saldo, nama FROM users WHERE id = ?");
$stmt_saldo->bind_param("i", $user_id);
$stmt_saldo->execute();
$user_data = $stmt_saldo->get_result()->fetch_assoc();

// Update session agar sinkron
if ($user_data) {
    $_SESSION['saldo'] = $user_data['saldo'];
    $_SESSION['username'] = $user_data['nama']; // Update nama juga jaga-jaga
}

// 3. QUERY RIWAYAT TRANSAKSI (Masuk & Keluar)
// Kita gunakan logika OR untuk mengambil transaksi dimana kita sebagai pengirim ATAU penerima
$query_transaksi = "
    SELECT 
        t.*, 
        u_penerima.nama AS nama_penerima,
        u_pengirim.nama AS nama_pengirim
    FROM transactions t
    LEFT JOIN users u_penerima ON t.penerima_id = u_penerima.id
    LEFT JOIN users u_pengirim ON t.user_id = u_pengirim.id
    WHERE t.user_id = ? OR t.penerima_id = ?
    ORDER BY t.tanggal_transaksi DESC
    LIMIT 10
";

$stmt = $connection->prepare($query_transaksi);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWallet - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background: linear-gradient(135deg, #ffffff, #f2f2f2, #e6e6e6);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .main-content {
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }

        .balance-card {
            background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        /* Hiasan background card */
        .balance-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .balance-amount {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 10px 0;
            letter-spacing: -1px;
        }

        .transaction-card {
            border-radius: 18px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
            background: white;
            padding: 20px;
        }

        .transaction-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
            border-radius: 10px;
        }
        
        .transaction-item:hover {
            background-color: #f8f9fa;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .bg-icon-in {
            background-color: #d1e7dd;
            color: #198754;
        }

        .bg-icon-out {
            background-color: #f8d7da;
            color: #dc3545;
        }

        .amount-positive {
            color: #198754;
            font-weight: 700;
        }

        .amount-negative {
            color: #dc3545;
            font-weight: 700;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-wallet2 me-2"></i>MyWallet
            </a>

            <div class="dropdown ms-auto">
                <button class="btn btn-link text-white text-decoration-none dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="fw-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 12px;">
                    <li><a class="dropdown-item py-2" href="profil.php"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">

        <div class="balance-card">
            <div class="opacity-75"><i class="bi bi-wallet me-2"></i>Total Saldo Aktif</div>
            <div class="balance-amount">Rp <?php echo number_format($_SESSION['saldo'], 0, ',', '.'); ?></div>
            
            <div class="d-flex gap-3 mt-4">
                <a href="topup.php" class="btn btn-light fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Top Up
                </a>
                <a href="transfer.php" class="btn btn-outline-light fw-bold px-4 py-2 rounded-pill">
                    <i class="bi bi-send me-2"></i>Transfer
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold m-0 text-secondary">Riwayat Transaksi</h5>
            
            <form action="search_transaksi.php" method="get" class="d-flex gap-2">
                <select name="jenis" class="form-select form-select-sm border-0 shadow-sm" style="width: 130px; cursor: pointer;">
                    <option value="">Semua</option>
                    <option value="top_up">Top Up</option>
                    <option value="transfer">Transfer</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm rounded-circle shadow-sm" title="Cari">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <div class="card transaction-card">
            <?php if ($result->num_rows === 0): ?>
                <div class="text-center py-5">
                    <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Belum ada transaksi apapun.</p>
                </div>
            <?php else: ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                        // Logika untuk menentukan jenis transaksi (Masuk/Keluar)
                        $is_masuk = false;
                        $label = "";
                        $sub_label = "";
                        
                        if ($row['jenis_transaksi'] == 'top_up') {
                            // Top Up selalu masuk
                            $is_masuk = true;
                            $label = "Top Up Saldo";
                            $sub_label = "Isi ulang via merchant/bank";
                        } elseif ($row['jenis_transaksi'] == 'transfer') {
                            // Cek apakah kita pengirim atau penerima
                            if ($row['penerima_id'] == $user_id) {
                                // Kita menerima uang
                                $is_masuk = true;
                                $label = "Terima Transfer";
                                $sub_label = "Dari: " . htmlspecialchars($row['nama_pengirim']);
                            } else {
                                // Kita mengirim uang
                                $is_masuk = false;
                                $label = "Transfer Keluar";
                                $sub_label = "Ke: " . htmlspecialchars($row['nama_penerima']);
                            }
                        }
                    ?>

                    <div class="transaction-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-box <?php echo $is_masuk ? 'bg-icon-in' : 'bg-icon-out'; ?> me-3">
                                <?php if ($row['jenis_transaksi'] == 'top_up'): ?>
                                    <i class="bi bi-wallet2"></i>
                                <?php elseif ($is_masuk): ?>
                                    <i class="bi bi-arrow-down-left"></i>
                                <?php else: ?>
                                    <i class="bi bi-arrow-up-right"></i>
                                <?php endif; ?>
                            </div>

                            <div>
                                <h6 class="mb-1 fw-bold text-dark"><?= $label ?></h6>
                                <small class="text-muted" style="font-size: 0.85rem;">
                                    <?= $sub_label ?>
                                </small>
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="<?php echo $is_masuk ? 'amount-positive' : 'amount-negative'; ?> fs-6">
                                <?= $is_masuk ? '+' : '-' ?> Rp <?= number_format($row['nominal'], 0, ',', '.') ?>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <?= date("d M Y H:i", strtotime($row['tanggal_transaksi'])) ?>
                            </small>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>