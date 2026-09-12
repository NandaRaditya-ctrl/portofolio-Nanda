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

<div class="page-header">
    <h1 class="page-title">Tambah Peminjaman</h1>
    <a href="peminjaman.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="glass-card" style="max-width: 800px;">
    <form action="" method="POST">
        <div class="form-group">
            <label for="user_id"><i class="fas fa-user"></i> Pilih Anggota</label>
            <select id="user_id" name="user_id" class="form-control" required>
                <option value="">-- Pilih Anggota --</option>
                <?php while ($anggota = $anggotaList->fetch_assoc()): ?>
                <option value="<?php echo $anggota['id']; ?>">
                    <?php echo htmlspecialchars($anggota['nama']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label><i class="fas fa-book"></i> Pilih Buku (bisa lebih dari satu)</label>
            <?php if ($bukuList->num_rows > 0): ?>
            <div class="checkbox-grid">
                <?php while ($buku = $bukuList->fetch_assoc()): ?>
                <label class="checkbox-item">
                    <input type="checkbox" name="buku_id[]" value="<?php echo $buku['id']; ?>">
                    <div>
                        <div class="book-title"><?php echo htmlspecialchars($buku['judul']); ?></div>
                        <div class="book-stock"><?php echo htmlspecialchars($buku['nama_kategori']); ?> &middot; Stok: <?php echo $buku['stok']; ?></div>
                    </div>
                </label>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="empty-state" style="padding: 15px;"><p>Semua buku sedang tidak tersedia.</p></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> Simpan Peminjaman
        </button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
