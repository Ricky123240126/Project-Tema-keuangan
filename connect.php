<?php
    $hostname = "localhost";
    $username_DB = "root";
    $password_DB = "";
    $database = "e-money";

    $connection = null;

    try {
        $connection = new mysqli($hostname, $username_DB, $password_DB, $database);
        echo "koneksi ke database berhasil";
    } catch (Exception $e) {
        echo $e;
    }
?>