<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireLogin(); ?>

<div class="page-header">
    <h1 class="page-title">Daftar Peminjaman</h1>
    <?php if (isAdmin()): ?>
    <a href="peminjaman_form.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Peminjaman</a>
    <?php endif; ?>
</div>

<div class="glass-card">
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
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No</th><th>Peminjam</th><th>Jumlah Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($row['nama_peminjam']); ?></td>
                    <td><?php echo $row['jumlah_buku']; ?> buku</td>
                    <td><?php echo date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                    <td><?php echo $row['tgl_kembali'] ? date('d M Y', strtotime($row['tgl_kembali'])) : '—'; ?></td>
                    <td><span class="badge badge-<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                    <td>
                        <div class="actions">
                            <a href="peminjaman_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>
                            <?php if (isAdmin() && $row['status'] == 'dipinjam'): ?>
                            <a href="peminjaman_kembali.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pengembalian?')"><i class="fas fa-undo"></i> Kembalikan</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state"><i class="fas fa-exchange-alt"></i><p>Belum ada peminjaman.</p></div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
