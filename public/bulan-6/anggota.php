<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin(); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Kelola Anggota</h1>
    <a href="anggota_form.php" class="btn btn-primary shadow-sm"><i class="fas fa-user-plus me-2"></i>Tambah Anggota</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <?php
        $result = $conn->query("SELECT * FROM users WHERE role = 'anggota' ORDER BY id DESC");
        ?>
        <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="5%">No</th>
                        <th width="30%">Nama</th>
                        <th width="30%">Email</th>
                        <th width="20%">Terdaftar</th>
                        <th class="text-center" width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                                    <?php echo strtoupper(substr($row['nama'], 0, 1)); ?>
                                </div>
                                <span class="fw-bold text-dark"><?php echo htmlspecialchars($row['nama']); ?></span>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><i class="far fa-calendar-alt text-muted me-2"></i><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="anggota_form.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus" onclick="showDeleteModal('anggota_hapus.php?id=<?php echo $row['id']; ?>', 'anggota ini')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">Belum ada anggota.</p>
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
