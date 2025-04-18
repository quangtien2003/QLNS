<?php
include 'connect.php';

// Xử lý khi ấn nút "Thêm hồ sơ nhân viên"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ma_nv = $_POST['ma_nv'];
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $dia_chi = $_POST['dia_chi'];
    $cccd = $_POST['CCCD'];
    $phong_ban = $_POST['phong_ban'];
    $chuc_vu = $_POST['chuc_vu'];
    $ngay_vao_lam = $_POST['ngay_vao_lam'];

    $sql_insert = "INSERT INTO hs_nhan_vien (ma_nv, ho_ten, ngay_sinh, gioi_tinh, sdt, email, dia_chi, cccd, phong_ban, chuc_vu, ngay_vao_lam)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssssssssss", $ma_nv, $ho_ten, $ngay_sinh, $gioi_tinh, $sdt, $email, $dia_chi, $cccd, $phong_ban, $chuc_vu, $ngay_vao_lam);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm nhân viên thành công!'); window.location.href='ql_hsnhanvien.php';</script>";
    } else {
        echo "<script>alert('Thêm nhân viên thất bại!');</script>";
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
    <title>Quản lý hồ sơ nhân viên</title>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
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
            <h1 style="padding-bottom: 66px">Quản lý hồ sơ nhân viên</h1>
            <h2><a style="margin-right: 16px; padding: 2px;" href="ql_hsnhanvien.php"><i class="fa-solid fa-backward"></i></a>Thêm hồ sơ nhân viên</h2>
            <div class="add">
                <form action="" method="POST">
                    <table>
                        <tr>
                            <td>Mã nhân viên:</td>
                            <td><input type="text"  name="ma_nv" required></td>
                        </tr>
                        <tr>
                            <td>Họ tên:</td>
                            <td><input type="text" name="ho_ten" required></td>
                        </tr>
                        <tr>
                            <td>Ngày sinh:</td>
                            <td><input type="date" name="ngay_sinh" required></td>
                        </tr>
                        <tr>
                            <td>Giới tính:</td>
                            <td><select name="gioi_tinh" id="">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td>Số điện thoại:</td>
                            <td><input type="text" name="sdt" required></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input type="email" name="email" required></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ:</td>
                            <td><input type="text" name="dia_chi" required></td>
                        </tr>
                        <tr>
                            <td>Số CMND/CCCD:</td>
                            <td><input type="text" name="CCCD" required></td>
                        </tr>
                        <tr>
                            <td>Phòng ban:</td>
                            <td><select name="phong_ban">
                            <option value="Phòng quản lý chất lượng">Phòng quản lý chất lượng</option>
                            <option value="Phòng tổng hợp">Phòng tổng hợp</option>
                            <option value="Phòng IDC MB">Phòng IDC MB</option>
                            <option value="Phòng IDC MN">Phòng IDC MN</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td>Chức vụ:</td>
                            <td><select name="chuc_vu">
                                <option value="Nhân viên">Nhân viên</option>
                                <option value="Trưởng phòng">Trưởng phòng</option>
                        </select>
                        </td>
                        </tr>
                        <tr>
                            <td>Ngày vào làm:</td>
                            <td><input type="date" name="ngay_vao_lam" required></td>
                        </tr>
                    </table>
            <button class="btn" style="font-weight: 600; font-size: 14px; margin: 10px 37%">Thêm hồ sơ nhân viên</button>
                </form>
            </div>
        </section>
    </div>
</body>
<style>
    .add{
        width: 60%;
    }

    input{
        width: 60%;
        height: 22px;
    }
    select{
        width: 60%;
        height: 22px;
    }
    table{
        margin: 20px;
    }
</style>
</html>