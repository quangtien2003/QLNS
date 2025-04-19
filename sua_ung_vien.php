<?php
include 'connect.php';


if (!isset($_GET['id'])) {
    echo "Thiếu ID ứng viên.";
    exit;
}

$id = intval($_GET['id']);

// Lấy dữ liệu ứng viên
$sql = "SELECT * FROM ung_vien WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $ho_ten = $row['ho_ten'];
    $sdt = $row['sdt'];
    $email = $row['email'];
    $vi_tri_ung_tuyen = $row['vi_tri_ung_tuyen'];
} else {
    echo "Không tìm thấy ứng viên.";
    exit;
}

// Xử lý khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = $_POST['ho_ten'];
    $sdt = $_POST['sdt'];
    $email = $_POST['email'];
    $vi_tri_ung_tuyen = $_POST['vi_tri_ung_tuyen'];

    $sql_update = "UPDATE ung_vien SET ho_ten = ?, sdt = ?, email = ?, vi_tri_ung_tuyen = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "ssssi", $ho_ten, $sdt, $email, $vi_tri_ung_tuyen, $id);

    if (mysqli_stmt_execute($stmt_update)) {
        echo "<script>alert('Cập nhật thành công!'); window.location='ds_ungvien.php';</script>";
        exit();
    } else {
        echo "Lỗi cập nhật.";
    }
}

$tieu_de_options = [];
$query = "SELECT tieu_de FROM tin_tuyen_dung";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tieu_de_options[] = $row['tieu_de'];
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
        <!-- Sidebar -->
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
            <h1 style="padding-bottom: 66px">Quản lý tuyển dụng</h1>
            <h2><a style="margin-right: 16px; padding: 2px;" href="ds_ungvien.php"><i class="fa-solid fa-backward"></i></a>Thêm hồ sơ ứng viên</h2>
            <div class="add">
            <form action="" method="POST">
                <table>
                    <tr>
                        <td>Họ tên:</td>
                        <td><input type="text" name="ho_ten" value="<?= htmlspecialchars($ho_ten) ?>" required></td>
                    </tr>
                    <tr>
                        <td>Số điện thoại:</td>
                        <td><input type="text" name="sdt" value="<?= htmlspecialchars($sdt) ?>" required></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required></td>
                    </tr>
                    <tr>
                        <td>Vị trí ứng tuyển:</td>
                        <td>
                            <select name="vi_tri_ung_tuyen" required>
                                <option value="">-- Chọn vị trí --</option>
                                <?php foreach ($tieu_de_options as $tieu_de): ?>
                                    <option value="<?= htmlspecialchars($tieu_de) ?>" <?= ($vi_tri_ung_tuyen == $tieu_de) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($tieu_de) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <button class="btn" style="font-weight: 600; font-size: 14px; margin: 10px 37%">Cập nhật hồ sơ ứng viên</button>
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