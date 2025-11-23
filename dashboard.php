<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('location: menu_login.php');
}
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
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 230px;
            height: 100vh;
            background-color: #0d6efd;
            color: white;
            padding-top: 60px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .sidebar.collapsed {
            margin-left: -230px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            width: 100%;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.15);
        }

        /* Navbar atas */
        .navbar-custom {
            background-color: #0d6efd;
            z-index: 1100;
        }

        /* Konten utama */
        .main-content {
            margin-left: 230px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .balance-card {
            background-color: #0d6efd;
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .transaction-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .amount-positive {
            color: green;
            font-weight: 600;
        }

        .amount-negative {
            color: red;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -230px;
                position: fixed;
                height: 100%;
                top: 0;
                left: 0;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <a href="#" class="active"><i class="bi bi-house-door-fill"></i> Beranda</a>
        <a href="#"><i class="bi bi-clock-history"></i> Riwayat</a>
        <a href="#"><i class="bi bi-credit-card"></i> Kartu</a>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-2" id="toggleBtn"><i class="bi bi-list"></i></button>
            <a class="navbar-brand" href="#"><i class="bi bi-wallet2"></i> MyWallet</a>

            <div class="d-flex align-items-center ms-auto">
                <button class="btn btn-link text-white position-relative me-3">
                    <i class="bi bi-bell fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                </button>

                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-4 me-2"></i>
                        <span><?php echo $_SESSION['username']; ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="delete_akun.php"
                                onclick="return confirm('Yakin mau hapus akun? Semua data akan hilang!');"><i
                                    class="bi bi-basket me-2"></i>hapus akun</a></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i
                                    class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten utama -->
    <div id="mainContent" class="main-content">
        <div class="balance-card">
            <div class="mb-2"><i class="bi bi-credit-card"></i> Saldo Anda</div>
            <div class="balance-amount"><?php echo $_SESSION['saldo']; ?></div>
            <div class="d-flex gap-2">
                <button class="btn btn-light"><i class="bi bi-plus-circle me-1"></i> Top Up</button>
                <button class="btn btn-outline-light"><i class="bi bi-arrow-right-circle me-1"></i> Transfer</button>
            </div>
        </div>

        <h5 class="mb-3">Fitur Cepat</h5>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="card-body">
                        <div>📱</div>
                        <h6>Top Up</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="card-body">
                        <div>💸</div>
                        <h6>Transfer</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center p-3">
                    <div class="card-body">
                        <div>📊</div>
                        <h6>Riwayat</h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Transaksi Terakhir</h5>
            <a href="#" class="text-decoration-none">Lihat Semua</a>
        </div>

        <div class="card transaction-card">
            <div class="card-body">
                <div class="transaction-item d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-danger"><i class="bi bi-arrow-up-right"></i></div>
                        <div>
                            <h6 class="mb-0">Transfer</h6><small class="text-muted">ke: Budi Santoso</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="amount-negative">-Rp 50.000</div>
                        <small class="text-muted">05 Nov 2025, 14:30</small>
                    </div>
                </div>

                <div class="transaction-item d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-success"><i class="bi bi-arrow-down-left"></i></div>
                        <div>
                            <h6 class="mb-0">Top Up</h6><small class="text-muted">via: Bank BCA</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="amount-positive">+Rp 100.000</div>
                        <small class="text-muted">04 Nov 2025, 10:15</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });
    </script>
</body>

</html>