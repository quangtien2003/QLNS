<?php
include 'connect.php';

$query = "SELECT phong_ban,
    COUNT(*) AS tong_nv,
    SUM(CASE WHEN gioi_tinh = 'Nam' THEN 1 ELSE 0 END) AS so_nam,
    SUM(CASE WHEN gioi_tinh = 'Nữ' THEN 1 ELSE 0 END) AS so_nu,
    SUM(CASE WHEN chuc_vu = 'Nhân viên' THEN 1 ELSE 0 END) AS so_nv,
    SUM(CASE WHEN chuc_vu = 'Trưởng phòng' THEN 1 ELSE 0 END) AS truong_phong
FROM hs_nhan_vien
GROUP BY phong_ban
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
                    <li><a href="bc_nhansu.php"><label style="color:#0064ff;"><i class="fas fa-chart-line"></i>Báo cáo thống kê</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="bc_luong.php"><label ><i class="fas fa-chart-line"></i>Báo cáo lương, thưởng</label></a></li>
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
        <h2>Báo cáo Thống kê Nhân sự</h2>
        <table id="employeeReport">
            <thead>
                <tr>
                    <th>Phòng ban</th>
                    <th>Số lượng nhân viên</th>
                    <th>Nam</th>
                    <th>Nữ</th>
                    <th>Nhân viên</th>
                    <th>Trưởng phòng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_nv = $total_nam = $total_nu = $total_nv_role = $total_tp = 0;

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['phong_ban']}</td>
                                <td>{$row['tong_nv']}</td>
                                <td>{$row['so_nam']}</td>
                                <td>{$row['so_nu']}</td>
                                <td>{$row['so_nv']}</td>
                                <td>{$row['truong_phong']}</td>
                              </tr>";
                        
                        // Tính tổng cuối bảng
                        $total_nv += $row['tong_nv'];
                        $total_nam += $row['so_nam'];
                        $total_nu += $row['so_nu'];
                        $total_nv_role += $row['so_nv'];
                        $total_tp += $row['truong_phong'];
                    }
                    echo "<tr>
                            <td><b>Tổng cộng</b></td>
                            <td><b>$total_nv</b></td>
                            <td><b>$total_nam</b></td>
                            <td><b>$total_nu</b></td>
                            <td><b>$total_nv_role</b></td>
                            <td><b>$total_tp</b></td>
                          </tr>";
                    
                ?>
            </tbody>
        </table>
        <button class="btn" style="margin-top: 20px;" onclick="exportToExcel('employeeReport', 'BaoCaoNhanSu.xlsx')">Xuất Excel</button>
            </div>
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
