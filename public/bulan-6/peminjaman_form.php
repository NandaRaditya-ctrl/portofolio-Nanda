<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin();

require_once __DIR__.'/../shared/transactions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        journey_borrow($conn, (int)($_POST['user_id'] ?? 0), is_array($_POST['buku_id'] ?? null) ? $_POST['buku_id'] : []);
        $_SESSION['success'] = 'Peminjaman berhasil dicatat.';
    } catch (Throwable $e) { $_SESSION['error'] = $e instanceof DomainException ? $e->getMessage() : 'Peminjaman gagal. Data dan stok tidak berubah.'; }
    header('Location: peminjaman.php'); exit;
}

// Ambil data anggota untuk dropdown
$anggotaList = $conn->query("SELECT id, nama FROM users WHERE role = 'anggota' ORDER BY nama ASC");

// Ambil data buku yang stoknya > 0 untuk checkbox
$bukuList = $conn->query("
    SELECT b.*, k.nama_kategori 
    FROM buku b 
    JOIN kategori k ON b.kategori_id = k.id 
    WHERE b.stok > 0 
    ORDER BY b.judul ASC
");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Tambah Peminjaman</h1>
    <a href="peminjaman.php" class="btn btn-outline-secondary shadow-sm">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 800px;">
    <div class="card-body p-4">
        <form action="" method="POST">
            <div class="mb-4">
                <label for="user_id" class="form-label fw-medium"><i class="fas fa-user text-primary me-2"></i>Pilih Anggota</label>
                <select id="user_id" name="user_id" class="form-select" required>
                    <option value="">-- Pilih Anggota --</option>
                    <?php while ($anggota = $anggotaList->fetch_assoc()): ?>
                    <option value="<?php echo $anggota['id']; ?>">
                        <?php echo htmlspecialchars($anggota['nama']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-medium"><i class="fas fa-book text-primary me-2"></i>Pilih Buku <span class="text-muted fw-normal small">(bisa lebih dari satu)</span></label>
                <?php if ($bukuList->num_rows > 0): ?>
                <div class="row g-3">
                    <?php while ($buku = $bukuList->fetch_assoc()): ?>
                    <div class="col-md-6">
                        <div class="form-check custom-checkbox-card p-3 border rounded">
                            <input class="form-check-input ms-1 me-3 mt-2" type="checkbox" name="buku_id[]" value="<?php echo $buku['id']; ?>" id="buku_<?php echo $buku['id']; ?>">
                            <label class="form-check-label w-100" for="buku_<?php echo $buku['id']; ?>" style="cursor:pointer;">
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($buku['judul']); ?></div>
                                <div class="small text-muted mt-1">
                                    <span class="badge bg-info text-dark me-1"><?php echo htmlspecialchars($buku['nama_kategori']); ?></span>
                                    Stok: <?php echo $buku['stok']; ?>
                                </div>
                            </label>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                <?php else: ?>
                <div class="alert alert-warning py-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>Semua buku sedang tidak tersedia / stok habis.
                </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-save me-2"></i> Simpan Peminjaman
            </button>
        </form>
    </div>
</div>

<style>
    .custom-checkbox-card {
        transition: all 0.2s;
    }
    .custom-checkbox-card:hover {
        background-color: var(--bs-light);
        border-color: var(--bs-primary) !important;
    }
    .form-check-input:checked + .form-check-label .fw-bold {
        color: var(--bs-primary) !important;
    }
</style>

<?php require_once 'includes/footer.php'; ?>
