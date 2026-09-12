<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Selamat datang, <?php echo htmlspecialchars($_SESSION['user']['nama']); ?>!</p>
    </div>
</div>

<?php
// Statistik
$totalBuku = $conn->query("SELECT COUNT(*) as total FROM buku")->fetch_assoc()['total'];
$totalAnggota = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'anggota'")->fetch_assoc()['total'];
$totalKategori = $conn->query("SELECT COUNT(*) as total FROM kategori")->fetch_assoc()['total'];
$totalPinjamAktif = $conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'")->fetch_assoc()['total'];
?>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Total Buku</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2">
                        <i class="fas fa-book fa-lg"></i>
                    </div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $totalBuku; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Total Anggota</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $totalAnggota; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Kategori</h6>
                    <div class="bg-dark bg-opacity-10 rounded p-2">
                        <i class="fas fa-tags fa-lg"></i>
                    </div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $totalKategori; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title text-uppercase mb-0">Pinjam Aktif</h6>
                    <div class="bg-white bg-opacity-25 rounded p-2">
                        <i class="fas fa-exchange-alt fa-lg"></i>
                    </div>
                </div>
                <h2 class="display-5 fw-bold mb-0"><?php echo $totalPinjamAktif; ?></h2>
            </div>
        </div>
    </div>
</div>

<?php
// Peminjaman terbaru
$recentLoans = $conn->query("
    SELECT p.*, u.nama as nama_peminjam 
    FROM peminjaman p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.id DESC LIMIT 5
");
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h5 class="card-title mb-4">Peminjaman Terbaru</h5>
        <?php if ($recentLoans->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Peminjam</th>
                        <th>Tgl Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $recentLoans->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <?php echo strtoupper(substr($row['nama_peminjam'], 0, 1)); ?>
                                </div>
                                <span class="fw-medium"><?php echo htmlspecialchars($row['nama_peminjam']); ?></span>
                            </div>
                        </td>
                        <td><i class="far fa-calendar-alt text-muted me-2"></i><?php echo date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                        <td>
                            <?php if ($row['status'] == 'dipinjam'): ?>
                                <span class="badge bg-danger">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge bg-success">Dikembalikan</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada peminjaman.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
