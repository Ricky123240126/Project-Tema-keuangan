<?php
include_once(__DIR__ . '/connect.php');

$nama = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$no_hp = $_POST['nomor'];

try {
    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: login_view.php");
        exit();
    }
    $stmt->close();

    $sql = "INSERT INTO users (nama, email, password, no_hp) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssss", $nama, $email, $hashedPassword, $no_hp);
    $stmt->execute();

    header("Location: menu_login.php");
    exit();

} catch (mysqli_sql_exception $e) {
    echo "Error MySQL: " . $e->getMessage();
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($connection)) $connection->close();
}
?>
