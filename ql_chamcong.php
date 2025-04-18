<?php
include 'connect.php';

// Lấy danh sách nhân viên
$queryNhanVien = "SELECT ma_nv, ho_ten FROM hs_nhan_vien";
$resultNhanVien = mysqli_query($conn, $queryNhanVien);

// Xử lý cập nhật dữ liệu chấm công
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_attendance'])) {
    $ma_nv = $_POST['ma_nv'];
    $ngay = $_POST['ngay'];
    $check_in = $_POST['checkIn'];
    $check_out = $_POST['checkOut'];

    $queryUpdate = "UPDATE cham_cong 
                    SET check_in = '$check_in', check_out = '$check_out', so_gio_lam = (TIME_TO_SEC(TIMEDIFF(check_out, check_in)) / 3600) -1
                    WHERE ma_nv = '$ma_nv' AND ngay = '$ngay'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href = 'ql_chamcong.php';</script>";
    } else {
        echo "<script>alert('Lỗi cập nhật: " . mysqli_error($conn) . "');</script>";
    }
}

// Lấy dữ liệu chấm công
$queryChamCong = "SELECT cham_cong.ngay, hs_nhan_vien.ho_ten, cham_cong.ma_nv, cham_cong.check_in, cham_cong.check_out, cham_cong.so_gio_lam
    FROM cham_cong
    JOIN hs_nhan_vien ON cham_cong.ma_nv = hs_nhan_vien.ma_nv
";
$resultChamCong = mysqli_query($conn, $queryChamCong);

// Lấy dữ liệu tìm kiếm
$where = []; // Mảng điều kiện lọc dữ liệu

if (!empty($_GET['ngay'])) {
    $ngay = $_GET['ngay'];
    $where[] = "cham_cong.ngay = '$ngay'";
}

if (!empty($_GET['ma_nv'])) {
    $ma_nv = $_GET['ma_nv'];
    $where[] = "cham_cong.ma_nv = '$ma_nv'";
}

// Tạo câu truy vấn SQL
$queryChamCong = "SELECT cham_cong.ngay, hs_nhan_vien.ho_ten, cham_cong.ma_nv, cham_cong.check_in, cham_cong.check_out, cham_cong.so_gio_lam
                  FROM cham_cong
                  JOIN hs_nhan_vien ON cham_cong.ma_nv = hs_nhan_vien.ma_nv";

// Nếu có điều kiện lọc, thêm vào câu truy vấn
if (!empty($where)) {
    $queryChamCong .= " WHERE " . implode(" AND ", $where);
}

// Thực thi truy vấn
$resultChamCong = mysqli_query($conn, $queryChamCong);


if (isset($_GET['delete']) && $_GET['delete'] == 1 && isset($_GET['ma_nv']) && isset($_GET['ngay'])) {
    $ma_nv = $_GET['ma_nv'];
    $ngay = $_GET['ngay'];

    $queryDelete = "DELETE FROM cham_cong WHERE ma_nv = '$ma_nv' AND ngay = '$ngay'";
    if (mysqli_query($conn, $queryDelete)) {
        echo "<script>alert('Xóa dữ liệu chấm công thành công!'); window.location.href = 'ql_chamcong.php';</script>";
    } else {
        echo "<script>alert('Lỗi xóa dữ liệu: " . mysqli_error($conn) . "');</script>";
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
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            text-align: center;
        }
        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
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
            <h1>Quản lý chấm công</h1>
            <a href="them_dlcc.php"><button class="btn" style="font-weight: 600; font-size: 14px;">Thêm dữ liệu chấm công</button></a>
            <h2>Xem dữ liệu chấm công</h2>
            <form action="" method="GET">
                <label>Chọn ngày:</label>
                <input type="date" name="ngay"><br><br>
                <label>Chọn nhân viên:</label>
                <select style="height: 24px;" name="ma_nv">
                    <option value="">-- Chọn nhân viên --</option>
                    <?php while ($row = mysqli_fetch_assoc($resultNhanVien)) { ?>
                        <option value="<?php echo $row['ma_nv']; ?>"><?php echo $row['ho_ten']; ?></option>
                    <?php } ?>
                </select><br><br>
                <button type="submit" class="btn">Xem</button>
            </form>
            <table style="margin-top: 30px;">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Nhân viên</th>
                        <th>Check in</th>
                        <th>Check out</th>
                        <th>Số giờ làm</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
    <?php while ($row = mysqli_fetch_assoc($resultChamCong)) { ?>
        <tr>
            <td><?php echo $row['ngay']; ?></td>
            <td><?php echo $row['ho_ten']; ?></td>
            <td><?php echo $row['check_in']; ?></td>
            <td><?php echo $row['check_out']; ?></td>
            <td><?php echo $row['so_gio_lam']; ?></td>
            <td>
                <button class="btn" onclick="openModal(
                    '<?php echo $row['ma_nv']; ?>',
                    '<?php echo $row['ngay']; ?>',
                    '<?php echo $row['ho_ten']; ?>',
                    '<?php echo $row['check_in']; ?>',
                    '<?php echo $row['check_out']; ?>'
                )">Sửa</button>
                <button class="btn"><a style="text-decoration: none; color: #fff" 
   href="ql_chamcong.php?delete=1&ma_nv=<?php echo $row['ma_nv']; ?>&ngay=<?php echo $row['ngay']; ?>" 
   onclick="return confirm('Bạn có chắc chắn muốn xóa dữ liệu chấm công này?');">
   Xóa
</a></button>
            </td>
        </tr>
    <?php } ?>
</tbody>
            </table>
        </section>
    </div>

    <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Chỉnh sửa giờ chấm công</h2>
        <form id="editForm" method="post">
            <input type="hidden" id="ma_nv" name="ma_nv">
            <input type="hidden" id="ngay" name="ngay">
            <label>Nhân viên:</label>
            <input type="text" id="employeeName" readonly><br><br>
            <label>Check in:</label>
            <input type="time" id="checkIn" name="checkIn"><br><br>
            <label>Check out:</label>
            <input type="time" id="checkOut" name="checkOut"><br><br>
            <button class="btn" type="submit" name="update_attendance">Lưu</button>
        </form>
    </div>
</div>


    <script>
        function openModal(ma_nv, ngay, ho_ten, checkIn, checkOut, diMuon, veSom) {
    document.getElementById("ma_nv").value = ma_nv;
    document.getElementById("ngay").value = ngay;
    document.getElementById("employeeName").value = ho_ten;
    document.getElementById("checkIn").value = checkIn;
    document.getElementById("checkOut").value = checkOut;


    document.getElementById("editModal").style.display = "block";
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

    </script>
</body>
</html>