<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <p style="color: var(--text-muted);">Selamat datang, <?php echo htmlspecialchars($_SESSION['user']['nama']); ?>!</p>
</div>

<?php
// Statistik
$totalBuku = $conn->query("SELECT COUNT(*) as total FROM buku")->fetch_assoc()['total'];
$totalAnggota = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'anggota'")->fetch_assoc()['total'];
$totalKategori = $conn->query("SELECT COUNT(*) as total FROM kategori")->fetch_assoc()['total'];
$totalPinjamAktif = $conn->query("SELECT COUNT(*) as total FROM peminjaman WHERE status = 'dipinjam'")->fetch_assoc()['total'];
?>

<div class="stats-grid">
    <div class="stat-card card-primary">
        <div class="stat-header">
            <div class="stat-label">Total Buku</div>
            <div class="stat-icon books"><i class="fas fa-book"></i></div>
        </div>
        <div class="stat-number"><?php echo $totalBuku; ?></div>
    </div>
    <div class="stat-card card-secondary">
        <div class="stat-header">
            <div class="stat-label">Total Anggota</div>
            <div class="stat-icon members"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-number"><?php echo $totalAnggota; ?></div>
    </div>
    <div class="stat-card card-warning">
        <div class="stat-header">
            <div class="stat-label">Kategori</div>
            <div class="stat-icon categories"><i class="fas fa-tags"></i></div>
        </div>
        <div class="stat-number"><?php echo $totalKategori; ?></div>
    </div>
    <div class="stat-card card-accent">
        <div class="stat-header">
            <div class="stat-label">Pinjam Aktif</div>
            <div class="stat-icon loans"><i class="fas fa-exchange-alt"></i></div>
        </div>
        <div class="stat-number"><?php echo $totalPinjamAktif; ?></div>
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

<div class="glass-card">
    <h3 style="font-family: var(--font-heading); margin-bottom: 15px;">Peminjaman Terbaru</h3>
    <?php if ($recentLoans->num_rows > 0): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Peminjam</th><th>Tgl Pinjam</th><th>Status</th></tr></thead>
            <tbody>
            <?php while ($row = $recentLoans->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(124, 58, 237, 0.3);">
                                <?php echo strtoupper(substr($row['nama_peminjam'], 0, 1)); ?>
                            </div>
                            <span style="font-weight: 500;"><?php echo htmlspecialchars($row['nama_peminjam']); ?></span>
                        </div>
                    </td>
                    <td style="color: var(--text-muted);"><i class="far fa-calendar-alt" style="margin-right: 6px;"></i> <?php echo date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                    <td><span class="badge badge-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada peminjaman.</p></div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
