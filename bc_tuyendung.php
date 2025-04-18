<?php
include 'connect.php';

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
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="bc_luong.php"><label><i class="fas fa-chart-line"></i>Báo cáo lương, thưởng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="bc_tuyendung.php"><label style="color:#0064ff;"><i class="fas fa-chart-line"></i>Báo cáo tuyển dụng</label></a></li>
                    <li>
                        <a href="dang_xuat.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                            <label><i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất</label>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <section class="content">
        <h2>Báo cáo Tuyển dụng</h2>
        <table id="bc_tuyendung">
            <thead>
                <tr>
                    <th>Vị trí ứng tuyển</th>
                    <th>Số lượng ứng viên</th>
                    <th>Trúng tuyển</th>
                    <th>Không trúng tuyển</th>
                </tr>
            </thead>
            <tbody >
            <?php
            $sql = "SELECT 
                        vi_tri_ung_tuyen,
                        COUNT(*) AS so_luong_ung_vien,
                        SUM(CASE WHEN trang_thai = 'Trúng tuyển' THEN 1 ELSE 0 END) AS trung_tuyen,
                        SUM(CASE WHEN trang_thai = 'Không trúng tuyển' THEN 1 ELSE 0 END) AS khong_trung_tuyen
                    FROM ung_vien
                    GROUP BY vi_tri_ung_tuyen";
            $result = mysqli_query($conn, $sql);

            $tong_uv = $tong_tt = $tong_ktt = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['vi_tri_ung_tuyen']}</td>
                        <td>{$row['so_luong_ung_vien']}</td>
                        <td>{$row['trung_tuyen']}</td>
                        <td>{$row['khong_trung_tuyen']}</td>
                    </tr>";

                $tong_uv += $row['so_luong_ung_vien'];
                $tong_tt += $row['trung_tuyen'];
                $tong_ktt += $row['khong_trung_tuyen'];
            }

            // Hàng tổng cộng
            echo "<tr>
                    <td><b>Tổng cộng</b></td>
                    <td><b>$tong_uv</b></td>
                    <td><b>$tong_tt</b></td>
                    <td><b>$tong_ktt</b></td>
                </tr>";
            ?>
            </tbody>
        </table>
        <button class="btn" style="margin-top: 20px;" onclick="exportToExcel('bc_tuyendung', 'BaoCaoTuyenDung.xlsx')">Xuất Excel</button>   
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
