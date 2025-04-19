<?php
include 'connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Tạo câu truy vấn dựa trên có hay không có tìm kiếm
if ($search != '') {
    // Nếu có tìm kiếm, lọc theo từ khóa
    $sql = "SELECT * 
            FROM hs_nhan_vien
            WHERE ho_ten LIKE ? OR ma_nv LIKE ?";
    
    $stmt = $conn->prepare($sql);
    $searchParam = "%$search%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Nếu không có tìm kiếm, lấy toàn bộ nhân viên
    $sql = "SELECT * 
            FROM hs_nhan_vien";
    
    $result = $conn->query($sql);
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
            <h1>Quản lý hồ sơ nhân viên</h1>
            <a href="them_hsnv.php"><button class="btn" style="font-weight: 600; font-size: 14px;">Thêm hồ sơ nhân viên</button></a>
            <h2>Danh sách nhân viên</h2>
            <form style="margin-top: 30px;" method="GET" action="">
                <input style="height: 24px; width: 300px; padding: 4px" type="text" name="search" placeholder="Nhập thông tin tìm kiếm">
                <button style="height: 34px; margin-left: 20px;">Tìm kiếm</button>
            </form><br>
            <table>
                <thead>
                    <tr>
                        <th>Mã nhân viên</th>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Phòng ban</th>
                        <th>Chức vụ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['ma_nv']}</td>
                                <td>{$row['ho_ten']}</td>
                                <td>{$row['sdt']}</td>
                                <td>{$row['phong_ban']}</td>
                                <td>{$row['chuc_vu']}</td>
                                <td>
                                    <a href='chitiet_hsnv.php?id={$row['ma_nv']}'>Chi tiết</a> |
                                    <a href='sua_hsnv.php?id={$row['ma_nv']}'>Sửa</a> |
                                    <a href='xoa_hsnv.php?id={$row['ma_nv']}' onclick=\"return confirm('Bạn có chắc chắn muốn xóa?');\">Xóa</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align: center;'>Không có nhân viên nào</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
