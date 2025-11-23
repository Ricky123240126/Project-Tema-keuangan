<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tampilan top-up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        .back {
            background: #2563eb;
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
            <span class="text-white fw-bold">Top Up</span>
            <a href="dashboard.php" class="btn btn-link text-white">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </a>
        </div>
    </nav>

    <div class="back">
        <div class="div-center">
            <div class="content">
                <h3>Top Up</h3>
                <hr />
                <form action="proses_topup.php" method="post">
                    <div class="form-group mb-2">
                        <label for="exampleInputNominal1">Nominal:</label>
                        <input type="number" class="form-control" name="nominal" id="exampleInputNominal1"
                            placeholder="Masukkan nominal top up" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="exampleInputNominal1">Metode Pembayaran:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="transfer_bank" id="radioDefault1">
                            <label class="form-check-label" for="radioDefault1">
                                Transfer bank
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="virtual_akun" id="radioDefault2">
                            <label class="form-check-label" for="radioDefault2">
                                Transfer virtual akun
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="minimarket" id="radioDefault3">
                            <label class="form-check-label" for="radioDefault2">
                                Minimarket
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label for="exampleInputPin1">PIN</label>
                        <input type="text" maxlength="6" pattern="[0-9]{6}" class="form-control" name="pin" placeholder="masukkan angka 0-9" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Konfirmasi</button>
                    <a href="profil.php" class="btn btn-outline-primary btn-sm w-100 mt-3">
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