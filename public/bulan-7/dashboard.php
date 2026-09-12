<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row align-items-stretch g-4 mb-4">
    <div class="col-lg-8">
        <div class="page-intro rounded-4 p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                    <i class="fas fa-store fa-lg"></i>
                </div>
                <div>
                    <p class="text-uppercase small text-muted mb-1">Sistem Kasir</p>
                    <h1 class="h3 mb-1">Toko Sembako yang rapi dan cepat</h1>
                </div>
            </div>
            <p class="text-muted mb-0">Selamat datang, <?php echo htmlspecialchars($_SESSION['user']['nama']); ?>! Kelola stok, jalankan transaksi, dan pantau penjualan dari satu tempat.</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <span class="badge bg-success-subtle text-success px-3 py-2"><i class="fas fa-bolt me-1"></i> Cepat</span>
                <span class="badge bg-primary-subtle text-primary px-3 py-2"><i class="fas fa-shield-alt me-1"></i> Aman</span>
                <span class="badge bg-warning-subtle text-warning px-3 py-2"><i class="fas fa-mobile-alt me-1"></i> Responsif</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="rounded-4 p-4 h-100 text-white" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fas fa-bolt"></i>
                <span class="fw-semibold">Mode Penjualan</span>
            </div>
            <h4 class="mb-1">Siap melayani pelanggan</h4>
            <p class="mb-0 opacity-75">Transaksi lebih cepat dan stok lebih terkontrol.</p>
            <div class="mt-3 d-flex align-items-center gap-2 floating-badge">
                <span class="badge bg-white text-success px-3 py-2"><i class="fas fa-crown me-1"></i> Premium</span>
            </div>
        </div>
    </div>
</div>

<?php
$totalProdukResult = @$conn->query("SELECT COUNT(*) as total FROM produk");
$totalProduk = ($totalProdukResult && $totalProdukResult->num_rows > 0) ? (int) $totalProdukResult->fetch_assoc()['total'] : 0;

$stokMenipisResult = @$conn->query("SELECT COUNT(*) as total FROM produk WHERE stok <= 5");
$stokMenipis = ($stokMenipisResult && $stokMenipisResult->num_rows > 0) ? (int) $stokMenipisResult->fetch_assoc()['total'] : 0;

$transaksiHariIniResult = @$conn->query("SELECT COUNT(*) as total FROM transaksi WHERE DATE(created_at) = CURDATE()");
$transaksiHariIni = ($transaksiHariIniResult && $transaksiHariIniResult->num_rows > 0) ? (int) $transaksiHariIniResult->fetch_assoc()['total'] : 0;

$pendapatanHariIniResult = @$conn->query("SELECT COALESCE(SUM(total_harga), 0) as total FROM transaksi WHERE DATE(created_at) = CURDATE()");
$pendapatanHariIni = ($pendapatanHariIniResult && $pendapatanHariIniResult->num_rows > 0) ? (int) $pendapatanHariIniResult->fetch_assoc()['total'] : 0;

$produkTerlaris = @$conn->query("SELECT p.nama_produk, SUM(dt.qty) as terjual FROM detail_transaksi dt JOIN produk p ON p.id = dt.produk_id GROUP BY p.id ORDER BY terjual DESC LIMIT 3");

// Query tren penjualan 7 hari terakhir
$salesTrend = @$conn->query("
    SELECT DATE(created_at) as tanggal, SUM(total_harga) as total 
    FROM transaksi 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) ASC
");

$trendData = [];
if ($salesTrend) {
    while ($row = $salesTrend->fetch_assoc()) {
        $trendData[$row['tanggal']] = (int)$row['total'];
    }
}

// Lengkapi data untuk 7 hari terakhir
$days = [];
$revenues = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $label = date('d M', strtotime("-$i days"));
    $days[] = $label;
    $revenues[] = $trendData[$d] ?? 0;
}
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 h-100 stat-card stat-card--produk">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Total Produk</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2"><i class="fas fa-boxes fa-lg"></i></div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $totalProduk; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100 stat-card stat-card--stok">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Stok Menipis</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2"><i class="fas fa-exclamation-triangle fa-lg"></i></div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $stokMenipis; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100 stat-card stat-card--transaksi">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Transaksi Hari Ini</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2"><i class="fas fa-receipt fa-lg"></i></div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $transaksiHariIni; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100 stat-card stat-card--pendapatan">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Pendapatan Hari Ini</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2"><i class="fas fa-money-bill-wave fa-lg"></i></div>
                </div>
                <h2 class="display-5 fw-bold mb-0">Rp <?php echo number_format($pendapatanHariIni, 0, ',', '.'); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <!-- Sales Chart Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-1">Tren Penjualan (7 Hari Terakhir)</h5>
                        <p class="text-muted mb-0">Grafik pendapatan harian toko Anda.</p>
                    </div>
                    <span class="badge bg-success-subtle text-success px-3 py-2"><i class="fas fa-chart-line me-1"></i> Live</span>
                </div>
                <div class="chart-container" style="position: relative; height:240px; width:100%">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-1">Aksi Cepat</h5>
                        <p class="text-muted mb-0">Pintasan untuk operasi harian toko.</p>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="transaksi.php" class="btn btn-success w-100 py-3">
                            <i class="fas fa-cash-register me-2"></i> Kasir Baru
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="produk.php" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-boxes me-2"></i> Kelola Produk
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="riwayat.php" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-history me-2"></i> Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Info Hari Ini</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Stok menipis</span>
                        <strong><?php echo $stokMenipis; ?></strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Transaksi</span>
                        <strong><?php echo $transaksiHariIni; ?></strong>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between">
                        <span>Pendapatan</span>
                        <strong>Rp <?php echo number_format($pendapatanHariIni, 0, ',', '.'); ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="card-title mb-1">Transaksi Terbaru</h5>
                        <p class="text-muted mb-0">Riwayat penjualan terkini dari toko.</p>
                    </div>
                    <a href="riwayat.php" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                </div>
                <?php
                $recentTransactions = @$conn->query("SELECT t.*, u.nama as kasir FROM transaksi t JOIN users u ON u.id = t.kasir_id ORDER BY t.id DESC LIMIT 5");
                if ($recentTransactions && $recentTransactions->num_rows > 0):
                ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Kasir</th>
                                <th>Total</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $recentTransactions->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['kasir']); ?></td>
                                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-receipt fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0">Belum ada transaksi hari ini.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">Produk Terlaris</h5>
                <?php if ($produkTerlaris->num_rows > 0): ?>
                <ul class="list-group list-group-flush">
                    <?php while ($item = $produkTerlaris->fetch_assoc()): ?>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($item['nama_produk']); ?></span>
                        <span class="badge bg-success rounded-pill"><?php echo $item['terjual']; ?> terjual</span>
                    </li>
                    <?php endwhile; ?>
                </ul>
                <?php else: ?>
                <p class="text-muted mb-0">Belum ada data penjualan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const getGridColor = () => document.body.classList.contains('dark-mode') ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';
    const getTextColor = () => document.body.classList.contains('dark-mode') ? '#a7b8ac' : '#4b5563';

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($days); ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?php echo json_encode($revenues); ?>,
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#16a34a',
                pointBorderColor: '#ffffff',
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#16a34a',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: getGridColor() },
                    ticks: {
                        color: getTextColor(),
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: getTextColor() }
                }
            }
        }
    });

    // Listen to theme-toggle click events to update chart colors dynamically
    const themeToggles = document.querySelectorAll('.theme-toggle');
    themeToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            // Wait slightly for body class to toggle
            setTimeout(() => {
                salesChart.options.scales.y.grid.color = getGridColor();
                salesChart.options.scales.y.ticks.color = getTextColor();
                salesChart.options.scales.x.ticks.color = getTextColor();
                salesChart.update();
            }, 100);
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
