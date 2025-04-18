<?php
include 'connect.php';

// Xử lý tìm kiếm theo tháng và năm
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

$query = "SELECT l.ma_nv, n.ho_ten, l.thang, l.nam, l.luong_cb, l.phu_cap, l.khau_tru, luong_thuc_nhan
          FROM luong l
          JOIN hs_nhan_vien n ON l.ma_nv = n.ma_nv
          WHERE l.thang = $month AND l.nam = $year
          ORDER BY l.ma_nv ASC";

$result = mysqli_query($conn, $query);

// Kiểm tra lỗi truy vấn
if (!$result) {
    die("Lỗi truy vấn SQL: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <title>Quản lý lương, thưởng</title>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="assets/img/logo.jpg" alt="Company Logo" width="60" height="60">
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><label><i class="fa-solid fa-gauge-high"></i></i>Dashboard</label></a></li>
                    <li><a href="ql_hsnhanvien.php"><label ><i class="fas fa-user"></i>Quản lý hồ sơ nhân viên</label></a></li>
                    <li><a href="ql_chamcong.php"><label><i class="fa-solid fa-clock"></i></i>Quản lý chấm công</label></a></li>
                    <li><a href="ql_luong.php"><label style="color:#0064ff;"><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="cap_nhat_tt_luong.php"><label><i class="fa-solid fa-pen-to-square"></i>Cập nhật thông tin lương</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="gui_bang_luong.php"><label><i class="fa-solid fa-square-check"></i>Chốt bảng lương</label></a></li>
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
            <h1>Quản lý lương, thưởng</h1>

            <!-- Form tìm kiếm -->
            <form method="GET" style="margin-bottom: 20px;">
                <label>Tháng:</label>
                <select name="month">
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        echo "<option value='$i' " . ($i == $month ? "selected" : "") . ">$i</option>";
                    }
                    ?>
                </select><br><br>
                <label>Năm:</label>
                <select name="year">
                    <?php
                    for ($i = date('Y'); $i >= 2000; $i--) {
                        echo "<option value='$i' " . ($i == $year ? "selected" : "") . ">$i</option>";
                    }
                    ?>
                </select><br><br>
                <button type="submit" class="btn">Tìm kiếm</button>
            </form>

            <h2>Danh sách lương, thưởng của nhân viên</h2>
            <table id="salaryTable" style="margin-top: 30px;">
                <thead>
                    <tr>
                        <th>Mã NV</th>
                        <th>Nhân viên</th>
                        <th>Tháng</th>
                        <th>Năm</th>
                        <th>Lương cơ bản</th>
                        <th>Phụ cấp</th>
                        <th>Khấu trừ</th>
                        <th>Lương thực nhận</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['ma_nv']}</td>
                                <td>{$row['ho_ten']}</td>
                                <td>{$row['thang']}</td>
                                <td>{$row['nam']}</td>
                                <td>" . number_format($row['luong_cb'], 0, ',', '.') . " VNĐ</td>
                                <td>" . number_format($row['phu_cap'], 0, ',', '.') . " VNĐ</td>
                                <td>" . number_format($row['khau_tru'], 0, ',', '.') . " VNĐ</td>
                                <td><b>" . number_format($row['luong_thuc_nhan'], 0, ',', '.') . " VNĐ</b></td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
