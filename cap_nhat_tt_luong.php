<?php
include 'connect.php';


// Xử lý cập nhật dữ liệu chấm công
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $ma_nv = $_POST['ma_nv'];
    $luong_co_ban = $_POST['luong_co_ban'];
    $phu_cap = $_POST['phu_cap'];
    $khau_tru = $_POST['khau_tru'];

    $queryUpdate = "UPDATE tt_luong 
                    SET luong_cb = '$luong_co_ban', phu_cap = '$phu_cap', khau_tru = '$khau_tru'
                    WHERE ma_nv = '$ma_nv'";
    
    if (mysqli_query($conn, $queryUpdate)) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href = 'cap_nhat_tt_luong.php';</script>";
    } else {
        echo "<script>alert('Lỗi cập nhật: " . mysqli_error($conn) . "');</script>";
    }
}


// Tạo câu truy vấn SQL
$queryttluong = "SELECT *
                  FROM hs_nhan_vien, tt_luong
                  WHERE hs_nhan_vien.ma_nv = tt_luong.ma_nv";

// Thực thi truy vấn
$resultttluong = mysqli_query($conn, $queryttluong);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <title>Quản lý lương, thưởng</title>
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
                    <li><a href="ql_chamcong.php"><label><i class="fa-solid fa-clock"></i></i>Quản lý chấm công</label></a></li>
                    <li><a href="ql_luong.php"><label ><i class="fa-solid fa-money-bill"></i>Quản lý lương, thưởng</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="cap_nhat_tt_luong.php"><label style="color:#0064ff;"><i class="fa-solid fa-pen-to-square"></i>Cập nhật thông tin lương</label></a></li>
                    <li style="margin: 16px 0 16px 30px; border: none"><a href="gui_bang_luong.php"><label><i class="fa-solid fa-square-check"></i>Chốt bảng lương</label></a></li>
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
            <h2>Cập nhật thông tin lương, phụ cấp, khấu trừ</h2>
            <table id="salaryTable">
    <thead>
        <tr>
            <th>Mã NV</th>
            <th>Nhân viên</th>
            <th>Lương cơ bản</th>
            <th>Phụ cấp</th>
            <th>Khấu trừ</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
                    <?php while ($row = $resultttluong->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['ma_nv']; ?></td>
                            <td><?php echo $row['ho_ten']; ?></td>
                            <td><?php echo number_format($row['luong_cb']). " VNĐ" ; ?></td>
                            <td><?php echo number_format($row['phu_cap']). " VNĐ"; ?></td>
                            <td><?php echo number_format($row['khau_tru']). " VNĐ"; ?></td>
                            <td>
                            <button class="btn" onclick="openModal(
                                '<?php echo $row['ma_nv']; ?>',
                                '<?php echo $row['ho_ten']; ?>',
                                '<?php echo $row['luong_cb'] ?>',
                                '<?php echo $row['phu_cap']; ?>',
                                '<?php echo $row['khau_tru']; ?>'
                            )">Sửa
                            </button>
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
        <h2>Cập nhật thông tin lương, phụ cấp</h2>
        <form id="editForm" method="post">
            <input type="hidden" id="ma_nv" name="ma_nv">
            <label>Nhân viên:</label>
            <input type="text" id="ho_ten" readonly><br><br>
            <label>Lương cơ bản</label>
            <input type="number" id="luong_co_ban" name="luong_co_ban"><br><br>
            <label>Phụ cấp</label>
            <input type="number" id="phu_cap" name="phu_cap"><br><br>
            <label>Khấu trừ</label>
            <input type="number" id="khau_tru" name="khau_tru"><br><br>

            <button class="btn" type="submit" name="update">Cập nhật</button>
        </form>
    </div>
</div>
<script>
        function openModal(ma_nv, ho_ten, luong_co_ban, phu_cap, khau_tru) {
    document.getElementById("ma_nv").value = ma_nv;
    document.getElementById("ho_ten").value = ho_ten;
    document.getElementById("luong_co_ban").value = luong_co_ban;
    document.getElementById("phu_cap").value = phu_cap;  
    document.getElementById("khau_tru").value = khau_tru;

    document.getElementById("editModal").style.display = "block";
}

function closeModal() {
    document.getElementById("editModal").style.display = "none";
}

    </script>

</body>
</html>
