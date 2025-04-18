<?php
include 'connect.php';

if (isset($_GET['id'])) {
    $ma_nv = $_GET['id'];

    // Câu lệnh xóa nhân viên
    $sql_delete = "DELETE FROM hs_nhan_vien WHERE ma_nv = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("s", $ma_nv);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa nhân viên thành công!'); window.location.href='ql_hsnhanvien.php';</script>";
    } else {
        echo "Lỗi xóa nhân viên: " . $stmt->error;
    }
} else {
    echo "Không tìm thấy ID nhân viên.";
}
?>
