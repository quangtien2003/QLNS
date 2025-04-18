<?php
$servername = "localhost";
$username = "root";     
$password = "";    
$database = "hris"; 

// Kết nối tới MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8"); 
?>
