<?php
include 'connect.php';

// Lấy ID nhân viên từ URL
if (isset($_GET['id'])) {
    $ma_nv = $_GET['id'];

    // Truy vấn lấy thông tin nhân viên theo mã nhân viên
    $sql = "SELECT * 
            FROM hs_nhan_vien
            WHERE ma_nv = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_nv);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $nhan_vien = $result->fetch_assoc();
    } else {
        echo "<script>alert('Nhân viên không tồn tại!'); window.location.href='ql_hsnhanvien.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Không tìm thấy nhân viên!'); window.location.href='ql_hsnhanvien.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <title>Quản lý hồ sơ nhân viên</title>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="assets/img/logo.jpg" alt="Company Logo" width="60" height="60">
            </div>
            <nav>
                <ul>
                    <li><a href="dashboard.php"><label style="font-weight: bold;"><i class="fa-solid fa-gauge-high"></i></i>Dashboard</label></a></li>
                    <li><a href="ql_hsnhanvien.php"><label style="color:#0064ff;"><i class="fas fa-user"></i>Quản lý hồ sơ nhân viên</label></a></li>
                    <li><a href="ql_chamcong.php"><label><i class="fa-solid fa-clock"></i></i>Quản lý chấm công</label></a></li>
                    <li><a href="ql_luong.php"><label><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li><a href="ql_luong.php"><label><i class="fa-solid fa-users"></i></i>Quản lý tuyển dụng</label></a></li>
                    <li><a href="baocao.php"><label><i class="fas fa-chart-line"></i>Báo cáo thống kê</label></a></li>
                    <li>
                        <a href="dang_xuat.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                            <label><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</label>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <section class="content">
            <h1 style="padding-bottom: 66px">Quản lý hồ sơ nhân viên</h1>
            <h2><a style="margin-right: 16px; padding: 2px; color: #0064ff" href="ql_hsnhanvien.php"><i class="fa-solid fa-backward"></i></a>Chi tiết hồ sơ nhân viên</h2>
            <div class="add">
                <table>
                    <tr>
                        <td><strong>Mã nhân viên:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['ma_nv']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Họ tên:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['ho_ten']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Ngày sinh:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['ngay_sinh']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Giới tính:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['gioi_tinh']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Số điện thoại:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['sdt']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['email']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Địa chỉ:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['dia_chi']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Số CMND/CCCD:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['cccd']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phòng ban:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['phong_ban']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Chức vụ:</strong></td>
                        <td><?= htmlspecialchars($nhan_vien['chuc_vu']) ?></td>
                    </tr>
                </table>
            </div>
        </section>
    </div>
</body>
<style>
    .add {
        width: 60%;
    }
    table {
        margin: 20px;
        border-collapse: collapse;
        width: 100%;
    }
    td {
        padding: 8px;
        border-bottom: 1px solid #ccc;
    }
    tr td:first-child {
        width: 30%;
        font-weight: bold;
    }
</style>
</html>
