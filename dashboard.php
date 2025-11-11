<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWallet - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style_dashboard.css">

</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-wallet2"></i> MyWallet
            </a>
            <div class="d-flex align-items-center">
                <div class="position-relative me-3">
                    <button class="btn btn-link text-white position-relative" type="button">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            3
                        </span>
                    </button>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                        <div class="bg-white text-primary rounded-circle me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            JD
                        </div>
                        <span>John Doe</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="balance-card">
            <div class="mb-2">
                <i class="bi bi-credit-card"></i> Saldo Anda
            </div>
            <div class="balance-amount">Rp 500.000</div>
            <div class="d-flex gap-2">
                <button class="btn btn-light"><i class="bi bi-plus-circle me-1"></i> Top Up</button>
                <button class="btn btn-outline-light"><i class="bi bi-arrow-right-circle me-1"></i> Transfer</button>
            </div>
        </div>

        <h5 class="mb-3">Fitur Cepat</h5>
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card feature-card text-center p-3">
                    <div class="card-body p-2">
                        <div class="feature-icon">📱</div>
                        <h6 class="card-title mb-0">Top Up</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card feature-card text-center p-3">
                    <div class="card-body p-2">
                        <div class="feature-icon">💸</div>
                        <h6 class="card-title mb-0">Transfer</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card feature-card text-center p-3">
                    <div class="card-body p-2">
                        <div class="feature-icon">📊</div>
                        <h6 class="card-title mb-0">Riwayat</h6>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card feature-card text-center p-3">
                    <div class="card-body p-2">
                        <div class="feature-icon">⚙️</div>
                        <h6 class="card-title mb-0">Pengaturan</h6>
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
                <div class="transaction-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="transaction-icon icon-outgoing me-3">
                                <i class="bi bi-arrow-up-right"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Transfer</h6>
                                <small class="text-muted">ke: Budi Santoso</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="amount-negative">-Rp 50.000</div>
                            <small class="text-muted">05 Nov 2025, 14:30</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="transaction-icon icon-incoming me-3">
                                <i class="bi bi-arrow-down-left"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Top Up</h6>
                                <small class="text-muted">via: Bank BCA</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="amount-positive">+Rp 100.000</div>
                            <small class="text-muted">04 Nov 2025, 10:15</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="transaction-icon icon-outgoing me-3">
                                <i class="bi bi-arrow-up-right"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Transfer</h6>
                                <small class="text-muted">ke: Ani Wulandari</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="amount-negative">-Rp 25.000</div>
                            <small class="text-muted">04 Nov 2025, 16:45</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="transaction-icon icon-incoming me-3">
                                <i class="bi bi-arrow-down-left"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Terima Transfer</h6>
                                <small class="text-muted">dari: Citra Dewi</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="amount-positive">+Rp 75.000</div>
                            <small class="text-muted">03 Nov 2025, 09:20</small>
                        </div>
                    </div>
                </div>

                <div class="transaction-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="transaction-icon icon-incoming me-3">
                                <i class="bi bi-arrow-down-left"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Top Up</h6>
                                <small class="text-muted">via: Transfer Bank</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="amount-positive">+Rp 200.000</div>
                            <small class="text-muted">02 Nov 2025, 13:00</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-nav">
        <div class="d-flex">
            <a href="#" class="nav-item-custom active">
                <i class="bi bi-house-door-fill"></i>
                <span>Beranda</span>
            </a>
            <a href="#" class="nav-item-custom">
                <i class="bi bi-clock-history"></i>
                <span>Riwayat</span>
            </a>
            <a href="#" class="nav-item-custom">
                <i class="bi bi-credit-card"></i>
                <span>Kartu</span>
            </a>
            <a href="#" class="nav-item-custom">
                <i class="bi bi-person"></i>
                <span>Profil</span>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>