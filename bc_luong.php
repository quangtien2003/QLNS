<?php
include 'connect.php';

$sql = "SELECT 
        nv.phong_ban,
        SUM(l.luong_cb) AS tong_luong_cb,
        SUM(l.phu_cap) AS tong_phu_cap,
        SUM(l.khau_tru) AS tong_khau_tru,
        SUM(l.luong_thuc_nhan) AS tong_thuc_nhan
    FROM hs_nhan_vien nv
    JOIN luong l ON nv.ma_nv = l.ma_nv
    GROUP BY nv.phong_ban
";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <title>Báo cáo thống kê</title>
   
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
                    <li><a href="ql_luong.php"><label ><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li><a href="ql_tuyendung.php"><label><i class="fa-solid fa-users"></i></i>Quản lý tuyển dụng</label></a></li>
                    <li><a href="bc_nhansu.php"><label><i class="fas fa-chart-line"></i>Báo cáo thống kê</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="bc_luong.php"><label style="color:#0064ff;"><i class="fas fa-chart-line"></i>Báo cáo lương, thưởng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="bc_tuyendung.php"><label><i class="fas fa-chart-line"></i>Báo cáo tuyển dụng</label></a></li>
                    <li>
                        <a href="dang_xuat.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                            <label><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</label>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <section class="content">
        <h2>Báo cáo Lương, Thưởng</h2>
        <table id="salaryReport">
            <thead>
                <tr>
                    <th>Phòng ban</th>
                    <th>Tổng lương cơ bản</th>
                    <th>Phụ cấp</th>
                    <th>Khấu trừ</th>
                    <th>Lương thực nhận</th>
                </tr>
            </thead>
            <tbody>
                <?php
            $result = mysqli_query($conn, $sql);

                $tong_cb = $tong_pc = $tong_kt = $tong_thuc = 0;
                $count = 0;

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['phong_ban']}</td>
                            <td>" . number_format($row['tong_luong_cb'], 0, ',', '.') . "</td>
                            <td>" . number_format($row['tong_phu_cap'], 0, ',', '.') . "</td>
                            <td>" . number_format($row['tong_khau_tru'], 0, ',', '.') . "</td>
                            <td>" . number_format($row['tong_thuc_nhan'], 0, ',', '.') . "</td>
                        </tr>";
                    
                    $tong_cb += $row['tong_luong_cb'];
                    $tong_pc += $row['tong_phu_cap'];
                    $tong_kt += $row['tong_khau_tru'];
                    $tong_thuc += $row['tong_thuc_nhan'];
                    $count++;
                }

                // Hàng tổng cộng
                echo "<tr><td><b>Tổng cộng</b></td>
                        <td><b>" . number_format($tong_cb, 0, ',', '.') . "</b></td>
                        <td><b>" . number_format($tong_pc, 0, ',', '.') . "</b></td>
                        <td><b>" . number_format($tong_kt, 0, ',', '.') . "</b></td>
                        <td><b>" . number_format($tong_thuc, 0, ',', '.') . "</b></td>
                    </tr>";

                // Hàng trung bình
                if ($count > 0) {
                    echo "<tr><td><b>Trung bình</b></td>
                            <td><b>" . number_format($tong_cb / $count, 0, ',', '.') . "</b></td>
                            <td><b>" . number_format($tong_pc / $count, 0, ',', '.') . "</b></td>
                            <td><b>" . number_format($tong_kt / $count, 0, ',', '.') . "</b></td>
                            <td><b>" . number_format($tong_thuc / $count, 0, ',', '.') . "</b></td>
                        </tr>";
                }
?>
</tbody>
        </table>
        <button class="btn" style="margin-top: 20px;" onclick="exportToExcel('salaryReport', 'BaoCaoLuong.xlsx')">Xuất Excel</button>   
        </section>
    </div>
    <script>
        function exportToExcel(tableID, filename) {
            let table = document.getElementById(tableID);
            let wb = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
            XLSX.writeFile(wb, filename);
        }
    </script>
</body>
</html>
