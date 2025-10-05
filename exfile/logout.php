<?php
session_start(); // Mulai session

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan kembali ke halaman login
header("Location: loginadmin.php");
exit;
?>