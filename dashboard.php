<?php
session_start();
include 'connect.php';
if (!isset($_SESSION['username'])) {
    header('location: menu_login.php');
}
$user_id = $_SESSION['id'];
$stmt = $connection->prepare("
    SELECT t.*, u.nama AS penerima_nama 
    FROM transactions t
    LEFT JOIN users u ON t.penerima_id = u.id
    WHERE t.user_id = ?
    ORDER BY t.tanggal_transaksi DESC
");

$stmt->bind_param("i", $user_id);
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

        /* Navbar */
        .navbar-custom {
            background-color: #0d6efd;
        }

        /* Konten utama */
        .main-content {
            padding: 30px;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Card saldo */
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

        /* Card transaksi */
        .transaction-card {
            border-radius: 18px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        }

        .transaction-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .amount-positive {
            color: green;
        }

        .amount-negative {
            color: red;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-wallet2"></i> MyWallet</a>

            <div class="dropdown ms-auto">
                <button class="btn btn-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-4 me-2"></i>
                    <span><?php echo $_SESSION['username']; ?></span>
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


        <!-- Transaksi -->
        <h5 class="mb-3">Transaksi Terakhir</h5>

        <div class="card transaction-card">
            <div class="card-body">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="transaction-item d-flex justify-content-between">

                        <div class="d-flex align-items-center">
                            <?php if ($result->num_rows === 0): ?>
                                <p class="text-center text-muted">Belum ada transaksi.</p>
                            <?php endif; ?>

                            <div class="me-3">
                                <?php if ($row['jenis_transaksi'] == 'top_up'): ?>
                                    <i class="bi bi-arrow-down-left text-success"></i>
                                <?php elseif ($row['jenis_transaksi'] == 'transfer'): ?>
                                    <i class="bi bi-arrow-up-right text-danger"></i>
                                <?php endif; ?>
                            </div>

                            <div>
                                <h6 class="mb-0">
                                    <?= ucfirst(str_replace('_', ' ', $row['jenis_transaksi'])) ?>
                                </h6>

                                <?php if ($row['jenis_transaksi'] == 'transfer'): ?>
                                    <small class="text-muted">ke: <?= $row['penerima_nama'] ?></small>
                                <?php elseif ($row['jenis_transaksi'] == 'top_up'): ?>
                                    <small class="text-muted">Saldo sebelum: Rp
                                        <?= number_format($row['saldo_sebelum']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="text-end">
                            <?php if ($row['jenis_transaksi'] == 'top_up'): ?>
                                <div class="amount-positive">+Rp <?= number_format($row['nominal']) ?></div>
                            <?php elseif ($row['jenis_transaksi'] == 'transfer'): ?>
                                <div class="amount-negative">-Rp <?= number_format($row['nominal']) ?></div>
                            <?php endif; ?>

                            <small class="text-muted">
                                <?= date("d M Y, H:i", strtotime($row['tanggal_transaksi'])) ?>
                            </small>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>