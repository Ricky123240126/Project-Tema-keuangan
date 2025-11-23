<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header('location: menu_login.php');
    exit();
}

$email = $_SESSION['email'];

$stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (is_null($user['pin']) || $user['pin'] === null) {
    echo "<script>alert('Silahkan lengkapi pin terlebih dahulu!'); window.location='update_profile.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $pin = $_POST['pin'];
    $nomor = $_POST['nomor'];
    $tanggal = $_POST['tanggal'];
    
    if (strlen($pin) != 6) {
        echo "<script>alert('PIN harus 6 digit!');</script>";
    } else {
        $stmt = $connection->prepare("UPDATE users SET nama=?, pin=?, nomor_telepon=?, tanggal_lahir=? WHERE email=?");
        $stmt->bind_param("sssss", $username, $pin, $nomor, $tanggal, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Update Profil Berhasil!'); window.location='profil.php';</script>";
            exit();
        } else {
            echo "<script>alert('Update Profil Gagal!'); window.location='edit_profile.php';</script>";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tampilan update profil</title>
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
            <span class="text-white fw-bold">Update Profil</span>
            <a href="#" class="btn btn-link text-white">
                <i class="bi bi-three-dots-vertical fs-5"></i>
            </a>
        </div>
    </nav>

    <div class="back">
        <div class="div-center">
            <div class="content">
                <h3>Update Profil</h3>
                <hr />
                <form method="post">
                    <div class="form-group mb-2">
                        <label for="exampleInputUsername1">Username</label>
                        <input type="username" class="form-control" name="username" id="exampleInputUsername1"
                            placeholder="username" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="exampleInputPin1">PIN</label>
                        <input type="number" class="form-control" name="pin" id="exampleInputPin1" placeholder="Pin"
                            required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="exampleInputNomor1">Nomor Handphone</label>
                        <input type="number" class="form-control" name="nomor" id="exampleInputNomor1"
                            placeholder="nomor hp" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="exampleInputTanggal1">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tanggal" id="exampleInputTanggal1" required>
                    </div>
                    <a href="edit_profile.php" class="btn btn-outline-primary btn-sm w-100 mt-3" type="submit">
                        <i class="bi bi-pencil me-1"></i> Update Profil
                    </a>
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