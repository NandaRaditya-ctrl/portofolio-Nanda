<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin(); ?>

<div class="page-header">
    <h1 class="page-title">Kelola Kategori</h1>
    <a href="kategori_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kategori</a>
</div>

<div class="glass-card">
    <?php
    $result = $conn->query("SELECT k.*, (SELECT COUNT(*) FROM buku WHERE kategori_id = k.id) as jumlah_buku FROM kategori k ORDER BY k.id DESC");
    ?>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No</th><th>Nama Kategori</th><th>Jumlah Buku</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?php echo $row['jumlah_buku']; ?> buku</td>
                    <td>
                        <div class="actions">
                            <a href="kategori_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="kategori_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Hapus kategori ini?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-tags"></i><p>Belum ada kategori.</p></div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
