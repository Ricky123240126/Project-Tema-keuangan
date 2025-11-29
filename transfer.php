<?php
session_start();
if (!isset($_SESSION['pin']) || $_SESSION['pin'] === null || $_SESSION['pin'] === '' || empty($_SESSION['pin'])) {
    echo "<script>
        alert('Silahkan lengkapi PIN terlebih dahulu!'); 
        window.location='update_profile.php';
    </script>";
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tampilan transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            width: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
        }

        .div-center {
            width: 400px;
            height: 400px;
            background-color: #fff;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            margin: auto;
            max-width: 100%;
            max-height: 100%;
            overflow: auto;
            padding: 1em 2em;
            border-bottom: 2px solid #ccc;
            display: table;
        }

        div.content {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid">
            <a href="profil.php" class="btn btn-link text-white">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <span class="text-white fw-bold">Transfer</span>
            <a href="dashboard.php" class="btn btn-link text-white">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </a>
        </div>
    </nav>

    <div class="back">
        <div class="div-center">
            <div class="content">
                <h3>Transfer</h3>
                    <hr />
                    <form action="konfirmasi_transfer.php" method="post">
                        <div class="form-group mb-2">
                            <label for="exampleInputEmail1">Email penerima:</label>
                            <input type="email" class="form-control" name="email" placeholder="masukkan email penerima"
                                required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="exampleInputNominal1">Nominal:</label>
                            <input type="number" class="form-control" name="nominal" id="exampleInputNominal1"
                                placeholder="Masukkan nominal transfer" required min="1000">
                            <small class="text-muted">Minimal Rp 1.000</small>
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-control mb-3" rows="2" placeholder="Tambahkan pesan (opsional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Lanjutkan</button>
                        <a href="dashboard.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
                            <i class="bi bi-pencil me-1"></i> kembali
                        </a>
                    </form>
            </div>
            </span>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"></script>
</body>

</html>