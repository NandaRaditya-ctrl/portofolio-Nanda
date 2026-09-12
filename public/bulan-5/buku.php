<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>

<div class="page-header">
    <h1 class="page-title">Daftar Buku</h1>
    <?php if (isAdmin()): ?>
    <a href="buku_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Buku</a>
    <?php endif; ?>
</div>

<div class="glass-card">
    <?php
    // JOIN ke tabel kategori untuk menampilkan nama kategori (relasi one-to-many)
    $result = $conn->query("
        SELECT b.*, k.nama_kategori 
        FROM buku b 
        JOIN kategori k ON b.kategori_id = k.id 
        ORDER BY b.id DESC
    ");
    ?>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No</th><th>Judul</th><th>Pengarang</th><th>Kategori</th><th>Penerbit</th><th>Tahun</th><th>Stok</th><?php if (isAdmin()): ?><th>Aksi</th><?php endif; ?></tr></thead>
            <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo htmlspecialchars($row['judul']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['pengarang']); ?></td>
                    <td><span class="badge badge-anggota"><?php echo htmlspecialchars($row['nama_kategori']); ?></span></td>
                    <td><?php echo htmlspecialchars($row['penerbit']); ?></td>
                    <td><?php echo $row['tahun']; ?></td>
                    <td><?php echo $row['stok']; ?></td>
                    <?php if (isAdmin()): ?>
                    <td>
                        <div class="actions">
                            <a href="buku_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="buku_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Hapus buku ini?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-book"></i><p>Belum ada buku.</p></div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
