<?php
include 'connect.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('Không tìm thấy mã nhân viên!'); window.location.href='ql_hsnhanvien.php';</script>";
    exit();
}

$ma_nv = $_GET['id'];
$nhanvien = null;

// Lấy thông tin nhân viên từ database
$sql = "SELECT * 
        FROM hs_nhan_vien
        WHERE ma_nv = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ma_nv);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $nhanvien = $result->fetch_assoc();
} else {
    echo "<script>alert('Nhân viên không tồn tại!'); window.location.href='ql_hsnhanvien.php';</script>";
    exit();
}

// Xử lý khi ấn nút "Sửa"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['ho_ten'];
    $ngaysinh = $_POST['ngay_sinh'];
    $gioitinh = $_POST['gioi_tinh'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $diachi = $_POST['dia_chi'];
    $cmnd = $_POST['CCCD'];
    $phongban = $_POST['ten_phong_ban'];
    $chucvu = $_POST['ten_chuc_vu'];
    $ngayvaolam = $_POST['ngay_vao_lam'];

    $sql_update = "UPDATE hs_nhan_vien SET ho_ten=?, ngay_sinh=?, gioi_tinh=?, sdt=?, email=?, dia_chi=?, cccd=?, phong_ban=?, chuc_vu=?, ngay_vao_lam=? WHERE ma_nv=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssssssssss", $hoten, $ngaysinh, $gioitinh, $sdt, $email, $diachi, $cmnd, $phongban, $chucvu, $ngayvaolam, $ma_nv);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='ql_hsnhanvien.php';</script>";
    } else {
        echo "<script>alert('Cập nhật thất bại!');</script>";
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
            <h1 style="border-bottom: 1px solid #ccc; padding-bottom: 66px">Quản lý hồ sơ nhân viên</h1>
            <h2><a style="margin-right: 16px; padding: 2px; color: #0064ff" href="ql_hsnhanvien.php"><i class="fa-solid fa-backward"></i></i></a>Sửa hồ sơ nhân viên</h2>
            <div class="add">
            <form action="" method="post">
                    <table>
                        <tr>
                            <td>Mã nhân viên:</td>
                            <td><input type="text" name="ma_nv" value="<?= $nhanvien['ma_nv'] ?>" disabled></td>
                        </tr>
                        <tr>
                            <td>Họ tên:</td>
                            <td><input type="text" name="ho_ten" value="<?= $nhanvien['ho_ten'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>Ngày sinh:</td>
                            <td><input type="date" name="ngay_sinh" value="<?= $nhanvien['ngay_sinh'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>Giới tính:</td>
                            <td>
                                <select name="gioi_tinh">
                                    <option value="Nam" <?= $nhanvien['gioi_tinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                    <option value="Nữ" <?= $nhanvien['gioi_tinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Số điện thoại:</td>
                            <td><input type="text" name="sdt" value="<?= $nhanvien['sdt'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input type="email" name="email" value="<?= $nhanvien['email'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>Địa chỉ:</td>
                            <td><input type="text" name="dia_chi" value="<?= $nhanvien['dia_chi'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>Số CMND/CCCD:</td>
                            <td><input type="text" name="CCCD" value="<?= $nhanvien['cccd'] ?>" required></td>
                        </tr>
                        <tr>
                            <td>Phòng ban:</td>
                            <td>
                                <select name="ten_phong_ban" required>
                                    <?php
                                    $ds_phong_ban = ['Phòng quản lý chất lượng', 'Phòng tổng hợp', 'Phòng IDC MB', 'Phòng IDC MN'];
                                    foreach ($ds_phong_ban as $phongban) {
                                        $selected = ($phongban == $nhanvien['phong_ban']) ? 'selected' : '';
                                        echo "<option value=\"$phongban\" $selected>$phongban</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Chức vụ:</td>
                            <td>
                                <select name="ten_chuc_vu" required>
                                    <?php
                                    $ds_chuc_vu = ['Trưởng phòng', 'Nhân viên'];
                                    foreach ($ds_chuc_vu as $chucvu) {
                                        $selected = ($chucvu == $nhanvien['chuc_vu']) ? 'selected' : '';
                                        echo "<option value=\"$chucvu\" $selected>$chucvu</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Ngày vào làm:</td>
                            <td><input type="date" name="ngay_vao_lam" value="<?= $nhanvien['ngay_vao_lam'] ?>" required></td>
                        </tr>
                    </table>
                    <button type="submit" class="btn">Cập nhật</button>
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