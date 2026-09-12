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

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Detail Peminjaman #<?php echo $id; ?></h1>
    <a href="peminjaman.php" class="btn btn-outline-secondary shadow-sm">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">
        <div class="row g-4 mb-4">
            <div class="col-sm-6">
                <p class="text-muted small mb-1">Peminjam</p>
                <p class="fw-bold mb-0"><?php echo htmlspecialchars($peminjaman['nama_peminjam']); ?></p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-1">Email</p>
                <p class="fw-bold mb-0"><?php echo htmlspecialchars($peminjaman['email_peminjam']); ?></p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-1">Tanggal Pinjam</p>
                <p class="fw-bold mb-0"><i class="far fa-calendar-alt text-primary me-2"></i><?php echo date('d M Y', strtotime($peminjaman['tgl_pinjam'])); ?></p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-1">Tanggal Kembali</p>
                <p class="fw-bold mb-0">
                    <?php if ($peminjaman['tgl_kembali']): ?>
                        <i class="far fa-calendar-check text-success me-2"></i><?php echo date('d M Y', strtotime($peminjaman['tgl_kembali'])); ?>
                    <?php else: ?>
                        <span class="text-muted">&mdash;</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-muted small mb-1">Status</p>
            <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                <span class="badge bg-danger">Dipinjam</span>
            <?php else: ?>
                <span class="badge bg-success">Dikembalikan</span>
            <?php endif; ?>
        </div>

        <hr class="text-muted opacity-25">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-book text-primary me-2"></i>Buku yang Dipinjam
            </h5>
            <span class="badge bg-light text-dark border">via tabel detail_peminjaman</span>
        </div>

        <?php if ($detailBuku->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle border mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="10%">No</th>
                        <th width="40%">Judul Buku</th>
                        <th width="30%">Pengarang</th>
                        <th width="20%">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($buku = $detailBuku->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($buku['judul']); ?></td>
                        <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($buku['nama_kategori']); ?></span></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="alert alert-warning py-3 mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>Tidak ada detail buku yang dipinjam.
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
