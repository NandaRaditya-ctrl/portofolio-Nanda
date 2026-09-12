<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Daftar Peminjaman</h1>
    <?php if (isAdmin()): ?>
    <a href="peminjaman_form.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Tambah Peminjaman</a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        // Query JOIN: peminjaman -> users (one-to-many)
        // + subquery ke detail_peminjaman untuk hitung jumlah buku
        $query = "
            SELECT p.*, u.nama as nama_peminjam,
                (SELECT COUNT(*) FROM detail_peminjaman WHERE peminjaman_id = p.id) as jumlah_buku
            FROM peminjaman p
            JOIN users u ON p.user_id = u.id
        ";

        // Jika anggota, hanya tampilkan peminjaman miliknya sendiri
        if (!isAdmin()) {
            $query .= " WHERE p.user_id = " . intval($_SESSION['user']['id']);
        }

        $query .= " ORDER BY p.id DESC";
        $result = $conn->query($query);
        ?>

        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="20%">Peminjam</th>
                        <th width="15%">Jumlah Buku</th>
                        <th width="15%">Tgl Pinjam</th>
                        <th width="15%">Tgl Kembali</th>
                        <th width="10%">Status</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_peminjam']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo $row['jumlah_buku']; ?> buku</span></td>
                        <td><i class="far fa-calendar-alt text-muted me-2"></i><?php echo date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                        <td>
                            <?php if ($row['tgl_kembali']): ?>
                                <i class="far fa-calendar-check text-muted me-2"></i><?php echo date('d M Y', strtotime($row['tgl_kembali'])); ?>
                            <?php else: ?>
                                <span class="text-muted">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] == 'dipinjam'): ?>
                                <span class="badge bg-danger">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge bg-success">Dikembalikan</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="peminjaman_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-info" title="Detail"><i class="fas fa-eye"></i> Detail</a>
                                <?php if (isAdmin() && $row['status'] == 'dipinjam'): ?>
                                <button type="button" class="btn btn-outline-success" title="Kembalikan" onclick="showReturnModal('peminjaman_kembali.php?id=<?php echo $row['id']; ?>')">
                                    <i class="fas fa-undo"></i> Kembalikan
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-exchange-alt fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada peminjaman.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Konfirmasi Pengembalian -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title"><i class="fas fa-info-circle text-success me-2"></i>Konfirmasi Pengembalian</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0 fs-5">Apakah buku ini sudah dikembalikan?</p>
        <p class="text-muted small mt-2">Status akan diubah menjadi 'Dikembalikan' dan stok buku akan bertambah.</p>
      </div>
      <div class="modal-footer border-0 pt-0 justify-content-center">
        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
        <a href="#" id="confirmReturnBtn" class="btn btn-success px-4">Ya, Kembalikan</a>
      </div>
    </div>
  </div>
</div>

<script>
function showReturnModal(url) {
    document.getElementById('confirmReturnBtn').href = url;
    var myModal = new bootstrap.Modal(document.getElementById('returnModal'));
    myModal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
