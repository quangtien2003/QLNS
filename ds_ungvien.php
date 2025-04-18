<?php
include 'connect.php';

if (isset($_GET['id']) && isset($_GET['trang_thai'])) {
    $id = intval($_GET['id']);
    $trang_thai = $_GET['trang_thai'];

    $sql = "UPDATE ung_vien SET trang_thai = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $trang_thai, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ds_ungvien.php"); // Quay lại danh sách
        exit();
    } else {
        echo "Lỗi cập nhật trạng thái.";
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
    <title>Quản lý tuyển dụng</title>
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
                    <li><a href="ql_hsnhanvien.php"><label><i class="fas fa-user"></i>Quản lý hồ sơ nhân viên</label></a></li>
                    <li><a href="ql_chamcong.php"><label><i class="fa-solid fa-clock"></i></i>Quản lý chấm công</label></a></li>
                    <li><a href="ql_luong.php"><label><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li><a href="ql_tuyendung.php"><label><i class="fa-solid fa-users"></i></i>Quản lý tuyển dụng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="ds_ungvien.php"><label style="color:#0064ff;"><i class="fa-solid fa-users"></i></i>Hồ sơ ứng viên</label></a></li>
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
            <h2>Danh sách hồ sơ ứng viên</h2>
            <a href="them_ung_vien.php"><button class="btn" style="font-weight: 600; font-size: 14px; margin-bottom: 30px">Thêm hồ sơ ứng viên</button></a>
            <table>
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Vị trí ứng tuyển</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM ung_vien";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['ho_ten']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['sdt']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vi_tri_ung_tuyen']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['trang_thai']) . "</td>";
                            echo "<td>";
                                switch ($row['trang_thai']) {
                                    case 'Đang xét duyệt':
                                        echo "
                                            <a href='ds_ungvien.php?id=" . $row['id'] . "&trang_thai=Mời phỏng vấn' onclick=\"return confirm('Xác nhận mời phỏng vấn?');\">
                                                <button class='btn'>Mời phỏng vấn</button>
                                            </a>
                                            <a href='ds_ungvien.php?id=" . $row['id'] . "&trang_thai=Trúng tuyển' onclick=\"return confirm('Xác nhận trúng tuyển?');\">
                                                <button class='btn'>Trúng tuyển</button>
                                            </a>
                                            <a href='ds_ungvien.php?id=" . $row['id'] . "&trang_thai=Không trúng tuyển' onclick=\"return confirm('Xác nhận không trúng tuyển?');\">
                                                <button class='btn'>Không trúng tuyển</button>
                                            </a>";
                                        break;
                                    case 'Mời phỏng vấn':
                                        echo "
                                            <a href='ds_ungvien.php?id=" . $row['id'] . "&trang_thai=Trúng tuyển' onclick=\"return confirm('Xác nhận trúng tuyển?');\">
                                                <button class='btn'>Trúng tuyển</button>
                                            </a>
                                            <a href='ds_ungvien.php?id=" . $row['id'] . "&trang_thai=Không trúng tuyển' onclick=\"return confirm('Xác nhận không trúng tuyển?');\">
                                                <button class='btn'>Không trúng tuyển</button>
                                            </a>";
                                        break;
                                    case 'Trúng tuyển':
                                    case 'Không trúng tuyển':
                                        echo "<i>Đã " . htmlspecialchars($row['trang_thai']) . "</i>";
                                        break;
                                    default:
                                        echo "<i>Trạng thái không xác định</i>";
                                        break;
                                }
                                echo "</td>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Chưa có ứng viên nào.</td></tr>";
                    }
                    ?>
                    </tbody>
            </table>
        </section>
    </div>
</body>
</html>
