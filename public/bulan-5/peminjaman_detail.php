<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    header("Location: peminjaman.php");
    exit;
}

// Ambil data peminjaman + nama peminjam (JOIN users)
$stmt = $conn->prepare("
    SELECT p.*, u.nama as nama_peminjam, u.email as email_peminjam
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$peminjaman = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$peminjaman) {
    $_SESSION['error'] = "Peminjaman tidak ditemukan.";
    header("Location: peminjaman.php");
    exit;
}

// Ambil detail buku via TABEL PENGHUBUNG (detail_peminjaman JOIN buku)
$stmtDetail = $conn->prepare("
    SELECT b.judul, b.pengarang, k.nama_kategori
    FROM detail_peminjaman dp
    JOIN buku b ON dp.buku_id = b.id
    JOIN kategori k ON b.kategori_id = k.id
    WHERE dp.peminjaman_id = ?
");
$stmtDetail->bind_param("i", $id);
$stmtDetail->execute();
$detailBuku = $stmtDetail->get_result();
$stmtDetail->close();
?>

<div class="page-header">
    <h1 class="page-title">Detail Peminjaman #<?php echo $id; ?></h1>
    <a href="peminjaman.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="glass-card" style="max-width: 700px;">
    <div class="detail-grid">
        <div class="detail-item">
            <div class="detail-label">Peminjam</div>
            <div class="detail-value"><?php echo htmlspecialchars($peminjaman['nama_peminjam']); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Email</div>
            <div class="detail-value"><?php echo htmlspecialchars($peminjaman['email_peminjam']); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal Pinjam</div>
            <div class="detail-value"><?php echo date('d M Y', strtotime($peminjaman['tgl_pinjam'])); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Tanggal Kembali</div>
            <div class="detail-value"><?php echo $peminjaman['tgl_kembali'] ? date('d M Y', strtotime($peminjaman['tgl_kembali'])) : '—'; ?></div>
        </div>
    </div>

    <div style="margin-bottom: 10px;">
        <span class="detail-label">Status: </span>
        <span class="badge badge-<?php echo $peminjaman['status']; ?>"><?php echo $peminjaman['status']; ?></span>
    </div>

    <hr style="border-color: var(--card-border); margin: 20px 0;">

    <h3 style="font-family: var(--font-heading); margin-bottom: 15px;">
        <i class="fas fa-book"></i> Buku yang Dipinjam
        <span style="font-size: 0.8rem; color: var(--text-muted);">(via tabel penghubung: detail_peminjaman)</span>
    </h3>

    <?php if ($detailBuku->num_rows > 0): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No</th><th>Judul Buku</th><th>Pengarang</th><th>Kategori</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($buku = $detailBuku->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($buku['judul']); ?></strong></td>
                    <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                    <td><span class="badge badge-anggota"><?php echo htmlspecialchars($buku['nama_kategori']); ?></span></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><p>Tidak ada detail buku.</p></div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
