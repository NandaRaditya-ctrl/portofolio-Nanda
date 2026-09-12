<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Daftar Buku</h1>
    <?php if (isAdmin()): ?>
    <a href="buku_form.php" class="btn btn-primary shadow-sm"><i class="fas fa-plus me-2"></i>Tambah Buku</a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $result = $conn->query("
            SELECT b.*, k.nama_kategori 
            FROM buku b 
            JOIN kategori k ON b.kategori_id = k.id 
            ORDER BY b.id DESC
        ");
        ?>
        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="25%">Judul</th>
                        <th width="20%">Pengarang</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Penerbit</th>
                        <th width="5%">Tahun</th>
                        <th class="text-center" width="5%">Stok</th>
                        <?php if (isAdmin()): ?>
                        <th class="text-center" width="10%">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td><?php echo htmlspecialchars($row['pengarang']); ?></td>
                        <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['nama_kategori']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['penerbit']); ?></td>
                        <td><?php echo $row['tahun']; ?></td>
                        <td class="text-center">
                            <span class="badge <?php echo $row['stok'] > 0 ? 'bg-success' : 'bg-danger'; ?> rounded-pill">
                                <?php echo $row['stok']; ?>
                            </span>
                        </td>
                        <?php if (isAdmin()): ?>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="buku_form.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="showDeleteModal('buku_hapus.php?id=<?php echo $row['id']; ?>', 'buku ini')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-book fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada buku.</p>
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
