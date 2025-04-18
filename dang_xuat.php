<?php
session_start();
session_destroy(); // Hủy toàn bộ session
header("Location: index.php"); // Chuyển hướng về trang đăng nhập
exit();
?>
