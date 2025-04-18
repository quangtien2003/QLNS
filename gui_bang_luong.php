<?php
include 'connect.php';

$month = isset($_GET['month']) && is_numeric($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
$year = isset($_GET['year']) && is_numeric($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

$sql = "SELECT 
            nv.ma_nv, 
            nv.ho_ten AS ten_nhan_vien, 
            IFNULL(SUM(cc.so_gio_lam) / 8, 0) AS so_ngay_cong, 
            l.luong_cb, 
            l.phu_cap, 
            l.khau_tru, 
            ((l.luong_cb/22) * IFNULL(SUM(cc.so_gio_lam) / 8, 2) + l.phu_cap - l.khau_tru) AS luong_thuc_nhan
        FROM hs_nhan_vien nv
        LEFT JOIN tt_luong l ON nv.ma_nv = l.ma_nv
        LEFT JOIN cham_cong cc ON nv.ma_nv = cc.ma_nv
        AND MONTH(cc.ngay) = ? AND YEAR(cc.ngay) = ?
        GROUP BY nv.ma_nv, nv.ho_ten, l.luong_cb, l.phu_cap, l.khau_tru";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['gui'])) {
    $insert_sql = "INSERT INTO luong (ma_nv, thang, nam, luong_cb, phu_cap, khau_tru, so_ngay_cong, luong_thuc_nhan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_sql);

    // Duyệt qua từng dòng dữ liệu để insert vào bảng lương
    foreach ($_POST['ma_nv'] as $key => $ma_nv) {
        $luong_co_ban = str_replace(",", "", $_POST['luong_cb'][$key]);
        $phu_cap = str_replace(",", "", $_POST['phu_cap'][$key]);
        $khau_tru = str_replace(",", "", $_POST['khau_tru'][$key]);
        $so_ngay_cong = str_replace(",", "", $_POST['so_ngay_cong'][$key]);
        $luong_thuc_nhan = str_replace(",", "", $_POST['luong_thuc_nhan'][$key]);

        $stmt_insert->bind_param("siiddddi", $ma_nv, $month, $year, $luong_co_ban, $phu_cap, $khau_tru, $so_ngay_cong, $luong_thuc_nhan);
        $stmt_insert->execute();
    }

    echo "<script>alert('Gửi bảng lương thành công!');</script>";
}

// Kiểm tra xem bảng lương đã có dữ liệu của tháng, năm hiện tại chưa
$check_sql = "SELECT COUNT(*) AS count FROM luong WHERE thang = ? AND nam = ?";
$stmt_check = $conn->prepare($check_sql);
$stmt_check->bind_param("ii", $month, $year);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$row_check = $result_check->fetch_assoc();
$luong_da_gui = ($row_check['count'] > 0); // Nếu count > 0, nghĩa là đã có dữ liệu
?>
<!DOCTYPE html>
<html lang="en">
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
                    <li><a href="ql_luong.php"><label ><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="cap_nhat_tt_luong.php"><label ><i class="fa-solid fa-pen-to-square"></i>Cập nhật thông tin lương</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="gui_bang_luong.php"><label style="color:#0064ff;"><i class="fa-solid fa-square-check"></i>Chốt bảng lương</label></a></li>
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
            <form action="" method="POST">
            <h1>Cập nhật bảng lương</h1>
            <button class="btn" name="gui" 
    style="background-color: <?= $luong_da_gui ? '#ccc' : '#007bff' ?>; 
           color: <?= $luong_da_gui ? '#666' : '#fff' ?>;" 
    <?= $luong_da_gui ? 'disabled' : '' ?>>
    Chốt bảng lương
</button>
            <h2>Danh sách lương nhân viên tháng <?php echo $month . "/" . $year; ?></h2>
            <table id="salaryTable">
    <thead>
        <tr>
            <th>Mã NV</th>
            <th>Nhân viên</th>
            <th>Ngày công</th>
            <th>Lương cơ bản</th>
            <th>Phụ cấp</th>
            <th>Khấu trừ</th>
            <th>Lương thực nhận</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td>
                <input type="hidden" name="ma_nv[]" value="<?php echo $row['ma_nv']; ?>">
                <?php echo $row['ma_nv']; ?>
            </td>
            <td><?php echo $row['ten_nhan_vien']; ?></td>
            <td>
                <input type="hidden" name="so_ngay_cong[]" value="<?php echo $row['so_ngay_cong']; ?>">
                <?php echo number_format($row['so_ngay_cong'], 2); ?>
            </td>
            <td>
                <input type="hidden" name="luong_cb[]" value="<?php echo $row['luong_cb']; ?>">
                <?php echo number_format($row['luong_cb']). " VNĐ"; ?>
            </td>
            <td>
                <input type="hidden" name="phu_cap[]" value="<?php echo $row['phu_cap']; ?>">
                <?php echo number_format($row['phu_cap']). " VNĐ"; ?>
            </td>
            <td>
                <input type="hidden" name="khau_tru[]" value="<?php echo $row['khau_tru']; ?>">
                <?php echo number_format($row['khau_tru']). " VNĐ"; ?>
            </td>
            <td>
                <input type="hidden" name="luong_thuc_nhan[]" value="<?php echo $row['luong_thuc_nhan']; ?>">
                <?php echo number_format($row['luong_thuc_nhan']). " VNĐ"; ?>
            </td>
        </tr>
    <?php } ?>
</tbody>
</table>
</form>
        </section>
    </div>
<style>
    input{
        border: none;
        background: none;
        text-align: center;
    }
</style>
</body>
</html>
