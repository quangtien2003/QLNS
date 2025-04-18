<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_dang_nhap = $_POST['ten_dang_nhap'];
    $mat_khau = $_POST['mat_khau']; 

    $sql = "SELECT * FROM tai_khoan WHERE ten_dang_nhap='$ten_dang_nhap' AND mat_khau='$mat_khau'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        header("Location: dashboard.php");}}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đăng nhập</title>
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-size: cover;
            background-position: center;
            background-color: #ece9e9;
        }

        .form-login {
            width: 400px;
            height: 440px;
            border: 2px solid rgba(255, 255, 255, .5);
            border-radius: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, .5);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-login img {
            width: 100px;
            margin-bottom: 20px;
            border: 2px solid #f1efef;
        }

        .form-login h2 {
            margin-bottom: 20px;
            font-size: 25px;
            font-weight: 600;
            color: #5b5b5b;
        }

        .input-box {
            width: 100%;
            margin-bottom: 20px;
            position: relative;
        }

        .input-box span {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .input-box input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .password-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }

        #eye-icon{
            font-size: 14px;
            position: absolute;
            right: 0;
            top: -8px;
        }

        .form-login button {
            width: 100%;
            font-size: 14px;
            padding: 10px 20px;
            background-color: #5c77ff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-login button:hover {
            background-color: #4d6afc;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="form-login">
            <h2>Đăng nhập</h2>
            <form action="" method="POST">
                <div class="input-box">
                    <span class="icon">
                    <i class="fa-solid fa-circle-user"></i>
                    </span>
                    <input type="text" name="ten_dang_nhap" required placeholder="Tên đăng nhập">
                </div>  
                <div class="input-box">
                    <span class="icon">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <!-- Add the id="password" here -->
                    <input type="password" id="password" name="mat_khau" required placeholder="Mật khẩu">
                </div> 
                    <!-- Hiển thị thông báo lỗi -->
    <?php if (!empty($error)): ?>
        <p style="color: red; text-align: center; margin: 10px 0 "><?php echo $error; ?></p>
    <?php endif; ?>
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
    </div>
</body>
</html>
