<?php
include 'connect.php';

$nhan_vien_result = mysqli_query($conn, "SELECT ma_nv, ho_ten FROM hs_nhan_vien");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ma_nv = $_POST['ma_nv'];
    $ngay = $_POST['ngay'];
    $check_in = $_POST['checkIn'];
    $check_out = $_POST['checkOut'];

    $sql_insert = "INSERT INTO cham_cong(ma_nv, ngay, check_in, check_out, so_gio_lam)
                    VALUES (?, ?, ?, ?, (TIME_TO_SEC(TIMEDIFF(?, ?)) / 3600) -1)";
    
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ssssss", $ma_nv, $ngay, $check_in, $check_out, $check_out, $check_in);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm dữ liệu thành công!'); window.location.href='ql_chamcong.php';</script>";
    } else {
        echo "<script>alert('Thêm dữ liệu thất bại!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <title>Quản lý chấm công</title>
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
                    <li><a href="ql_chamcong.php"><label style="color:#0064ff;"><i class="fa-solid fa-clock"></i></i>Quản lý chấm công</label></a></li>
                    <li><a href="ql_luong.php"><label><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li><a href="ql_tuyendung.php"><label><i class="fa-solid fa-users"></i></i>Quản lý tuyển dụng</label></a></li>
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
            <h1 style="padding-bottom: 66px">Quản lý chấm công</h1>
            <h2><a style="margin-right: 16px; padding: 2px;" href="ql_chamcong.php"><i class="fa-solid fa-backward"></i></a>Thêm dữ liệu chấm công</h2>
            <div class="add">
                <form method="post">
            <label>Ngày:</label>
            <input type="date" id="ngay" name="ngay" required><br><br>
            <label>Mã nhân viên:</label>
            <select name="ma_nv" required>
                <option value="">-- Chọn nhân viên --</option>
                <?php while($row = mysqli_fetch_assoc($nhan_vien_result)): ?>
                    <option value="<?= $row['ma_nv'] ?>"><?= $row['ma_nv'] ?> - <?= $row['ho_ten'] ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <label>Check in:</label>
            <input type="time" id="checkIn" name="checkIn" required><br><br>
            <label>Check out:</label>
            <input type="time" id="checkOut" name="checkOut" required><br><br>
            <button class="btn" type="submit" name="them">Thêm</button>
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
    form{
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
</html>