<?php
session_start();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - MyWallet</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            padding-bottom: 80px;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }

        .profile-id {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Info Cards */
        .info-card {
            border: none;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .info-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            color: #212529;
            font-weight: 600;
            font-size: 1rem;
        }

        /* Menu Items */
        .menu-item {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .menu-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 1rem;
        }

        .icon-blue {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .icon-green {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .icon-purple {
            background-color: #e9d5ff;
            color: #9333ea;
        }

        .icon-orange {
            background-color: #fed7aa;
            color: #ea580c;
        }

        .icon-red {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .menu-text h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .menu-text p {
            margin: 0;
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Badge */
        .verified-badge {
            background-color: #dcfce7;
            color: #16a34a;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .nav-item-custom {
            flex: 1;
            text-align: center;
            padding: 0.75rem 0;
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-item-custom:hover,
        .nav-item-custom.active {
            color: #2563eb;
        }

        .nav-item-custom i {
            font-size: 1.5rem;
            display: block;
            margin-bottom: 0.25rem;
        }

        .nav-item-custom span {
            font-size: 0.75rem;
        }

        /* Camera Button */
        .camera-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: white;
            border: 2px solid #2563eb;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .camera-btn:hover {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <a href="dashboard.php" class="btn btn-link text-white">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <span class="text-white fw-bold">Profil Saya</span>
            <a href="#" class="btn btn-link text-white">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </a>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header text-center">
        <div class="container">
            <div class="position-relative d-inline-block">
                <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&size=100&background=2563eb&color=fff"
                    alt="Profile" class="profile-avatar">
                <div class="camera-btn">
                    <i class="bi bi-camera-fill"></i>
                </div>
            </div>
            <div class="profile-id">Id : <?php echo $_SESSION['id']; ?></div>
            <div class="mt-2">
                <span class="verified-badge">
                    <i class="bi bi-patch-check-fill"></i><?php echo $_SESSION['status']; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Personal Info -->
        <h6 class="mb-3 fw-bold">Informasi Pribadi</h6>

        <div class="card info-card">
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="info-label">
                            <i class="bi bi-person-circle me-1"></i> Username
                        </div>
                        <div class="info-value"><?php echo $_SESSION['username']; ?></div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="info-label">
                            <i class="bi bi-envelope me-1"></i> Email
                        </div>
                        <div class="info-value"><?php echo $_SESSION['email']; ?></div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="info-label">
                            <i class="bi bi-phone me-1"></i> No. Handphone
                        </div>
                        <div class="info-value"><?php echo $_SESSION['no_hp']; ?></div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="info-label">
                            <i class="bi bi-calendar me-1"></i> Tanggal Daftar akun
                        </div>
                        <div class="info-value"><?php echo $_SESSION['tanggal_daftar']; ?></div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="info-label">
                            <i class="bi bi-credit-card me-1"></i> PIN
                        </div>
                        <div class="info-value"><?php echo $_SESSION['pin']; ?></div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="info-label">
                            <i class="bi bi-calendar me-1"></i> Tanggal lahir
                        </div>
                        <div class="info-value"><?php echo $_SESSION['tanggal_lahir']; ?></div>
                    </div>

                </div>
                <a href="update_profile.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                    <i class="bi bi-pencil me-1"></i> Update PIN
                </a>
                <a href="edit_profile.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                    <i class="bi bi-pencil me-1"></i> Update Profil
                </a>
            </div>
        </div>
    </div>