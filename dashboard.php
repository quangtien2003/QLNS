<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/font/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
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
                    <li><a href="dashboard.php"><label style="color:#0064ff;"><i class="fa-solid fa-gauge-high"></i></i>Dashboard</label></a></li>
                    <li><a href="ql_hsnhanvien.php"><label ><i class="fas fa-user"></i>Quản lý hồ sơ nhân viên</label></a></li>
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
            <h1>Dashboard</h1>
        <div class="db">
        <div class="charts">
        <div class="chart-container">
            <h3>Nhân sự theo phòng ban</h3>
            <canvas id="nhanSuChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Lương trung bình theo tháng</h3>
            <canvas id="luongChart"></canvas>
        </div>
        <div class="chart-container">
            <h3>Tình trạng tuyển dụng</h3>
            <canvas id="tuyenDungChart"></canvas>
        </div>
    </div>
    </div>
        </section>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("du_lieu_dashboard.php")
        .then(response => response.json())
        .then(data => {
            // --- Nhân sự theo phòng ban (Bar chart)
            const nhanSuCtx = document.getElementById('nhanSuChart').getContext('2d');
            new Chart(nhanSuCtx, {
                type: 'bar',
                data: {
                    labels: data.nhan_su.map(p => p.phong_ban),
                    datasets: [{
                        label: 'Số lượng nhân viên',
                        data: data.nhan_su.map(p => p.so_luong),
                        backgroundColor: '#36A2EB'
                    }]
                }
            });

            // --- Lương trung bình theo tháng (Line chart)
            const luongCtx = document.getElementById('luongChart').getContext('2d');
            new Chart(luongCtx, {
                type: 'line',
                data: {
                    labels: data.luong.map(l => l.thang),
                    datasets: [{
                        label: 'Lương trung bình',
                        data: data.luong.map(l => l.luong_tb),
                        borderColor: '#4BC0C0',
                        fill: false,
                        tension: 0.3
                    }]
                }
            });

            // --- Tình trạng ứng viên tuyển dụng (Pie chart)
            const tdCtx = document.getElementById('tuyenDungChart').getContext('2d');
            new Chart(tdCtx, {
                type: 'pie',
                data: {
                    labels: ['Trúng tuyển', 'Không trúng', 'Mời phỏng vấn', 'Đang xét duyệt'],
                    datasets: [{
                        data: [
                            data.tuyen_dung.trung_tuyen,
                            data.tuyen_dung.khong_trung,
                            data.tuyen_dung.phong_van,
                            data.tuyen_dung.dang_xet
                        ],
                        backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#9966FF']
                    }]
                }
            });
        })
        .catch(error => console.error("Lỗi khi tải dữ liệu:", error));
});
</script>

<style>
      body {
    font-family: Arial, sans-serif;
    text-align: center;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
}

.content {
    flex-grow: 1;
    padding: 20px;
}

h1 {
    text-align: left;
}

.db {
    width: 100%;
    margin: auto;
}

.charts {
    display: flex;
    justify-content: space-evenly; 
    gap: 10px; 
}

.chart-container {
    width: 40%;  
    max-width: 380px; 
}

h3{
    color: red;
    font-weight: 600;
    margin-bottom: 20px;
}
    </style>

</body>
</html>
