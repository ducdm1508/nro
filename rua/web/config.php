<?php
$host = "localhost";   // server MySQL
$user = "root";        // user MySQL
$pass = "";            // mật khẩu MySQL
$db   = "nroxz";    // tên database

$conn = new mysqli($host, $user, $pass, $db);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập charset UTF-8
$conn->set_charset("utf8mb4");
?>
