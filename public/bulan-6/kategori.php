<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Kelola Kategori</h1>
    <a href="kategori_form.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Tambah Kategori</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $result = $conn->query("SELECT k.*, (SELECT COUNT(*) FROM buku WHERE kategori_id = k.id) as jumlah_buku FROM kategori k ORDER BY k.id DESC");
        ?>
        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="10%">No</th>
                        <th width="50%">Nama Kategori</th>
                        <th width="20%">Jumlah Buku</th>
                        <th class="text-center" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                        <td><span class="badge bg-secondary"><?php echo $row['jumlah_buku']; ?> buku</span></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="kategori_form.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="showDeleteModal('kategori_hapus.php?id=<?php echo $row['id']; ?>', 'kategori ini')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-tags fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada kategori.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-0 fs-5">Apakah Anda yakin ingin menghapus <strong id="deleteItemName"></strong>?</p>
        <p class="text-muted small mt-2">Tindakan ini tidak dapat dibatalkan.</p>
      </div>
      <div class="modal-footer border-0 pt-0 justify-content-center">
        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger px-4">Ya, Hapus</a>
      </div>
    </div>
  </div>
</div>

<script>
function showDeleteModal(url, itemName) {
    document.getElementById('deleteItemName').innerText = itemName;
    document.getElementById('confirmDeleteBtn').href = url;
    var myModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    myModal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
