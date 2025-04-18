<?php
include 'connect.php';

$response = [];

// --- 1. Nhân sự theo phòng ban
$q1 = mysqli_query($conn, "SELECT phong_ban, COUNT(*) AS so_luong
    FROM hs_nhan_vien
    GROUP BY phong_ban
");
while ($row = mysqli_fetch_assoc($q1)) {
    $response['nhan_su'][] = $row;
}

// --- 2. Lương trung bình theo tháng
$q2 = mysqli_query($conn, "SELECT thang, ROUND(AVG(luong_thuc_nhan), 0) AS luong_tb
    FROM luong
    GROUP BY thang
    ORDER BY thang ASC
");
while ($row = mysqli_fetch_assoc($q2)) {
    $response['luong'][] = $row;
}

// --- 3. Tình trạng ứng viên tuyển dụng
$q3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT
        SUM(CASE WHEN trang_thai = 'trúng tuyển' THEN 1 ELSE 0 END) AS trung_tuyen,
        SUM(CASE WHEN trang_thai = 'không trúng tuyển' THEN 1 ELSE 0 END) AS khong_trung,
        SUM(CASE WHEN trang_thai = 'mời phỏng vấn' THEN 1 ELSE 0 END) AS phong_van,
        SUM(CASE WHEN trang_thai = 'đang xét duyệt' THEN 1 ELSE 0 END) AS dang_xet
    FROM ung_vien
"));
$response['tuyen_dung'] = $q3;

// Trả về JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
