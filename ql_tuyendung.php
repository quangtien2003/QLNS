<?php
include 'connect.php';
$sql_td = "SELECT * FROM tin_tuyen_dung";
$result_td = $conn->query($sql_td);
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
            <h1>Quản lý tuyển dụng</h1>
            <a href="tao_td.php"><button class="btn" style="font-weight: 600; font-size: 14px;">Tạo tin tuyển dụng</button></a>
            <h2>Danh sách tin tuyển dụng</h2>

            <table>
                <thead>
                    <tr>
                        <th>Tiêu đề</th>
                        <th>Mô tả công việc</th>
                        <th>Yêu cầu</th>
                        <th>Mức lương</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    if ($result_td->num_rows > 0) {
        while ($row = $result_td->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . nl2br($row['tieu_de']) . "</td>";
            echo "<td>" . nl2br($row['mo_ta']) . "</td>";
            echo "<td>" . nl2br($row['yeu_cau']) . "</td>";
            echo "<td>" . nl2br($row['muc_luong']) . "</td>";
            echo "<td>
                    <a href='sua_td.php?id=" . $row['id'] . "'><button class='btn' style='margin-bottom: 4px;'>Sửa</button></a><br>
                    <a href='xuat_file_td.php?id=" . $row['id'] . "'><button class='btn'>Xuất file</button></a>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Không có tin tuyển dụng nào.</td></tr>";
    }
    ?>
</tbody>
            </table>
        </section>
    </div>
</body>
</html>
