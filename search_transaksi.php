<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header('location: menu_login.php');
    exit();
}

$user_id = $_SESSION['id'];

$jenis_filter = isset($_GET['jenis']) ? $_GET['jenis'] : '';
$is_searching = !empty($jenis_filter);

if ($is_searching) {
    $stmt = $connection->prepare("
        SELECT t.*, u.nama AS penerima_nama 
        FROM transactions t
        LEFT JOIN users u ON t.penerima_id = u.id
        WHERE t.user_id = ? AND t.jenis_transaksi = ?
        ORDER BY t.tanggal_transaksi DESC
    ");
    $stmt->bind_param("is", $user_id, $jenis_filter);
} else {
    $stmt = $connection->prepare("
        SELECT t.*, u.nama AS penerima_nama 
        FROM transactions t
        LEFT JOIN users u ON t.penerima_id = u.id
        WHERE t.user_id = ?
        ORDER BY t.tanggal_transaksi DESC
    ");
    $stmt->bind_param("i", $user_id);
}

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
            background-color: #f0f2f5;
        }

        .navbar-custom {
            background-color: #0d6efd;
        }

        .main-content {
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }

        .balance-card {
            background-color: #0d6efd;
            color: white;
            border-radius: 18px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }

        .balance-amount {
            font-size: 2.2rem;
            font-weight: bold;
        }

        .transaction-card {
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .transaction-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .amount-positive {
            color: green;
        }

        .amount-negative {
            color: red;
        }

        .search-bar {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .filter-badge {
            background-color: #0d6efd;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php"><i class="bi bi-wallet2"></i> MyWallet</a>

            <div class="dropdown ms-auto">
                <button class="btn btn-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="delete_akun.php"
                            onclick="return confirm('Yakin mau hapus akun? Semua data akan hilang!');">
                            <i class="bi bi-trash me-2"></i>Hapus Akun
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Konten -->
    <div class="main-content">

        <!-- Saldo -->
        <div class="balance-card">
            <div><i class="bi bi-credit-card"></i> Saldo Anda</div>
            <div class="balance-amount">Rp <?php echo number_format($_SESSION['saldo'], 0, ',', '.'); ?></div>

            <div class="d-flex gap-2 mt-3">
                <a href="topup.php" class="btn btn-light">
                    <i class="bi bi-plus-circle me-1"></i> Top Up
                </a>

                <a href="transfer.php" class="btn btn-outline-light">
                    <i class="bi bi-arrow-right-circle me-1"></i> Transfer
                </a>
            </div>
        </div>


        <!-- Search Bar -->
        <div class="search-bar">
            <h5 class="mb-3"><i class="bi bi-search"></i> Cari Transaksi</h5>
            <form action="dashboard.php" method="get" class="row g-2">
                <div class="col-md-8">
                    <select name="jenis" class="form-select" required>
                        <option value="">-- Pilih Jenis Transaksi --</option>
                        <option value="top_up" <?= $jenis_filter == 'top_up' ? 'selected' : '' ?>>Top Up</option>
                        <option value="transfer" <?= $jenis_filter == 'transfer' ? 'selected' : '' ?>>Transfer</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if ($is_searching): ?>
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> kembali
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Transaksi -->
        <h5 class="mb-3">
            <?= $is_searching ? 'Hasil Pencarian' : 'Transaksi Terakhir' ?>
            <?php if ($result->num_rows > 0): ?>
                <span class="badge bg-secondary"><?= $result->num_rows ?></span>
            <?php endif; ?>
        </h5>

        <div class="card transaction-card">
            <div class="card-body">
                <?php if ($result->num_rows === 0): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-2">
                            <?= $is_searching ? 'Tidak ada transaksi dengan filter tersebut.' : 'Belum ada transaksi.' ?>
                        </p>
                    </div>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="transaction-item d-flex justify-content-between align-items-center">

                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <?php if ($row['jenis_transaksi'] == 'top_up'): ?>
                                        <i class="bi bi-arrow-down-left-circle-fill fs-4 text-success"></i>
                                    <?php elseif ($row['jenis_transaksi'] == 'transfer'): ?>
                                        <i class="bi bi-arrow-up-right-circle-fill fs-4 text-danger"></i>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <h6 class="mb-0">
                                        <?= ucfirst(str_replace('_', ' ', $row['jenis_transaksi'])) ?>
                                    </h6>

                                    <?php if ($row['jenis_transaksi'] == 'transfer'): ?>
                                        <small class="text-muted">
                                            <i class="bi bi-person"></i> ke: <?= htmlspecialchars($row['penerima_nama']) ?>
                                        </small>
                                    <?php elseif ($row['jenis_transaksi'] == 'top_up'): ?>
                                        <small class="text-muted">
                                            Saldo sebelum: Rp <?= number_format($row['saldo_sebelum'], 0, ',', '.') ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-end">
                                <?php if ($row['jenis_transaksi'] == 'top_up'): ?>
                                    <div class="amount-positive fw-bold">+Rp <?= number_format($row['nominal'], 0, ',', '.') ?></div>
                                <?php elseif ($row['jenis_transaksi'] == 'transfer'): ?>
                                    <div class="amount-negative fw-bold">-Rp <?= number_format($row['nominal'], 0, ',', '.') ?></div>
                                <?php endif; ?>

                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    <?= date("d M Y, H:i", strtotime($row['tanggal_transaksi'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>