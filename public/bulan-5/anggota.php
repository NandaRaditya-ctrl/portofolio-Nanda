<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin(); ?>

<div class="page-header">
    <h1 class="page-title">Kelola Anggota</h1>
    <a href="anggota_form.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Tambah Anggota</a>
</div>

<div class="glass-card">
    <?php
    $result = $conn->query("SELECT * FROM users WHERE role = 'anggota' ORDER BY id DESC");
    ?>
    <?php if ($result->num_rows > 0): ?>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Terdaftar</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <div class="actions">
                            <a href="anggota_form.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                            <a href="anggota_hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Hapus anggota ini?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-users"></i><p>Belum ada anggota.</p></div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
