<?php
require_once 'PHPWord-master/src/PhpWord/Autoloader.php'; 
\PhpOffice\PhpWord\Autoloader::register(); 

include 'connect.php';

if (!isset($_GET['id'])) {
    die("Thiếu ID tin tuyển dụng.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM tin_tuyen_dung WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tin = $result->fetch_assoc();

if (!$tin) {
    die("Không tìm thấy tin tuyển dụng.");
}

// Load file mẫu Word
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('mau_tintd.docx');

// Điền dữ liệu vào các placeholder trong file mẫu
$templateProcessor->setValue('tieu_de', $tin['tieu_de']);
$templateProcessor->setValue('mo_ta', $tin['mo_ta']);
$templateProcessor->setValue('yeu_cau', $tin['yeu_cau']);
$templateProcessor->setValue('muc_luong', $tin['muc_luong']);

// Lưu tệp tạm thời
$outputFile = 'JD_' . $tin['tieu_de'] . '.docx';
$templateProcessor->saveAs($outputFile);

// Tải file về
header("Content-Disposition: attachment; filename=\"$outputFile\"");
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
readfile($outputFile);
unlink($outputFile);
exit;
?>
