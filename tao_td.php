<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tieu_de = $_POST['tieu_de'];
    $mo_ta = $_POST['mo_ta'];
    $yeu_cau = $_POST['yeu_cau'];
    $luong = $_POST['luong'];

    // Chuẩn bị câu lệnh để chèn vào DB
    $sql = "INSERT INTO tin_tuyen_dung (tieu_de, mo_ta, yeu_cau, muc_luong) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $tieu_de, $mo_ta, $yeu_cau, $luong);

    if ($stmt->execute()) {
        echo "<script>alert('Tạo tin tuyển dụng thành công!'); window.location.href='ql_tuyendung.php';</script>";
    } else {
        echo "Lỗi khi thêm: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <title>Quản lý tuyển dụng</title>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="assets/img/logo.jpg" alt="Company Logo" width="60" height="60">
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><label style="font-weight: bold;"><i class="fa-solid fa-gauge-high"></i></i>Dashboard</label></a></li>
                    <li><a href="ql_hsnhanvien.php"><label><i class="fas fa-user"></i>Quản lý hồ sơ nhân viên</label></a></li>
                    <li><a href="ql_chamcong.php"><label><i class="fa-solid fa-clock"></i></i>Quản lý chấm công</label></a></li>
                    <li><a href="ql_luong.php"><label><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li><a href="ql_tuyendung.php"><label style="color:#0064ff;"><i class="fa-solid fa-users"></i></i>Quản lý tuyển dụng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="ds_ungvien.php"><label><i class="fa-solid fa-users"></i></i>Hồ sơ ứng viên</label></a></li>
                    <li><a href="bc_nhansu.php"><label><i class="fas fa-chart-line"></i>Báo cáo thống kê</label></a></li>
                    <li>
                        <a href="dang_xuat.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                            <label><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</label>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <section class="content">
            <h1 style="padding-bottom: 66px">Quản lý tuyển dụng</h1>
            <h2><a style="margin-right: 16px; padding: 2px;" href="ql_tuyendung.php"><i class="fa-solid fa-backward"></i></a>Tạo tin tuyển dụng</h2>
            <div class="add">
                <form action="" method="POST">
                    <table>
                        <tr>
                            <td>Tiêu đề:</td>
                            <td><input type="text"  name="tieu_de" required></td>
                        </tr>
                        <tr>
                            <td>Mô tả công việc:</td>
                            <td><textarea name="mo_ta" required></textarea></td>
                        </tr>
                        <tr>
                            <td>Yêu cầu:</td>
                            <td><textarea name="yeu_cau" required></textarea></td>
                        </tr>
                        <tr>
                            <td>Mức lương:</td>
                            <td><input type="text" name="luong" required></td>
                        </tr>
                    </table>
            <button class="btn" style="font-weight: 600; font-size: 14px; margin: 10px 37%">Tạo tin tuyển dụng</button>
                </form>
            </div>
        </section>
    </div>
</body>
<style>
    .add{
        width: 60%;
    }

    input{
        width: 60%;
        height: 22px;
    }
    select{
        width: 60%;
        height: 22px;
    }
    table{
        margin: 20px;
    }
    textarea {
    width: 60%;
    height: 80px;
    resize: vertical;
}
</style>
</html>