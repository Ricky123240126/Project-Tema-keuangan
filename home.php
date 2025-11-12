<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Beranda - E-Money</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Selamat Datang!</h2>
    <p>Halo, <b><?php echo $_SESSION['nama']; ?></b></p>
    <p>Anda berhasil login ke sistem E-Money.</p>
    <a href="logout.php"><button>Logout</button></a>
</div>
</body>
</html>
