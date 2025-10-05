<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "websekolah";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    // Atur mode error ke exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi berhasil!";
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>