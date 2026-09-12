<?php
require_once __DIR__.'/../shared/native.php';
require_once 'includes/header.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_produk'])) {
    $id = (int)($_POST['produk_id'] ?? 0);
    $namaProduk = trim($_POST['nama_produk']);
    $kategoriId = (int)$_POST['kategori_id'];
    $satuan = trim($_POST['satuan']);
    $hargaBeli = (int)$_POST['harga_beli'];
    $hargaJual = (int)$_POST['harga_jual'];
    $stok = (int)$_POST['stok'];

    if ($namaProduk === '' || $satuan === '' || $kategoriId <= 0 || $hargaBeli < 0 || $hargaJual < 0 || $stok < 0) {
        $_SESSION['error'] = 'Nama, satuan, kategori wajib diisi; harga dan stok tidak boleh negatif.';
    } else {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE produk SET kategori_id = ?, nama_produk = ?, satuan = ?, harga_beli = ?, harga_jual = ?, stok = ? WHERE id = ?");
            $stmt->bind_param('issiiii', $kategoriId, $namaProduk, $satuan, $hargaBeli, $hargaJual, $stok, $id);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success'] = 'Produk berhasil diperbarui.';
        } else {
            $stmt = $conn->prepare("INSERT INTO produk (kategori_id, nama_produk, satuan, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('issiii', $kategoriId, $namaProduk, $satuan, $hargaBeli, $hargaJual, $stok);
            $stmt->execute();
            $stmt->close();
            $_SESSION['success'] = 'Produk berhasil ditambahkan.';
        }
        header('Location: produk.php');
        exit;
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success'] = 'Produk berhasil dihapus.';
    header('Location: produk.php');
    exit;
}

$editProduct = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $editStmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
    $editStmt->bind_param('i', $editId);
    $editStmt->execute();
    $editResult = $editStmt->get_result();
    if ($editResult->num_rows > 0) {
        $editProduct = $editResult->fetch_assoc();
    }
    $editStmt->close();
}

$categories = $conn->query("SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori");
$products = $conn->query("SELECT p.*, k.nama_kategori FROM produk p JOIN kategori k ON k.id = p.kategori_id ORDER BY p.nama_produk ASC");
?>

<div class="page-shell">
    <div class="hero-panel p-4 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <p class="text-uppercase small text-success fw-bold mb-2">Manajemen Produk</p>
                <h1 class="h3 mb-2">Kelola stok dan harga barang sembako</h1>
                <p class="text-muted mb-0">Tambah, edit, dan hapus produk dengan tampilan yang lebih rapi dan responsif.</p>
            </div>
            <div class="d-flex align-items-center gap-2 text-success fw-semibold">
                <i class="fas fa-boxes"></i>
                <span>Catat stok dengan lebih mudah</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4 order-2 order-lg-1">
            <div class="card border-0 kpi-card h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><?php echo $editProduct ? 'Edit Produk' : 'Tambah Produk'; ?></h5>
                    <div class="d-flex align-items-center gap-2 text-success mb-3">
                        <i class="fas fa-tag"></i>
                        <span class="small fw-semibold">Kelola barang dengan lebih rapi</span>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="produk_id" value="<?php echo $editProduct ? (int)$editProduct['id'] : 0; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" name="nama_produk" value="<?php echo htmlspecialchars($editProduct['nama_produk'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori_id" required>
                                <option value="">Pilih kategori</option>
                                <?php while ($category = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo (($editProduct['kategori_id'] ?? 0) == $category['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['nama_kategori']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <input type="text" class="form-control" name="satuan" value="<?php echo htmlspecialchars($editProduct['satuan'] ?? ''); ?>" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Harga Beli</label>
                                <input type="number" class="form-control" name="harga_beli" min="0" value="<?php echo (int)($editProduct['harga_beli'] ?? 0); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" class="form-control" name="harga_jual" min="0" value="<?php echo (int)($editProduct['harga_jual'] ?? 0); ?>" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" name="stok" min="0" value="<?php echo (int)($editProduct['stok'] ?? 0); ?>" required>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" name="save_produk" class="btn btn-success flex-grow-1">
                                <i class="fas fa-save me-2"></i><?php echo $editProduct ? 'Simpan Perubahan' : 'Simpan Produk'; ?>
                            </button>
                            <?php if ($editProduct): ?>
                            <a href="produk.php" class="btn btn-outline-secondary">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-1 order-lg-2">
            <div class="card border-0 kpi-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-1">Daftar Produk</h5>
                            <p class="text-muted mb-0">Tampilan responsif untuk memudahkan monitoring stok.</p>
                        </div>
                        <span class="badge bg-success-subtle text-success px-3 py-2"><?php echo $products->num_rows; ?> item</span>
                    </div>
                    <!-- Tampilan Desktop (Tabel) -->
                    <div class="d-none d-md-block table-responsive table-card">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="d-none d-sm-table-cell">Kategori</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($products->num_rows > 0): ?>
                                    <?php while ($row = $products->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($row['nama_produk']); ?></div>
                                            <div class="small text-muted">Satuan: <?php echo htmlspecialchars($row['satuan']); ?></div>
                                        </td>
                                        <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                                        <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="badge rounded-pill <?php echo $row['stok'] <= 5 ? 'bg-warning text-dark' : 'bg-success'; ?>">
                                                <?php echo (int)$row['stok']; ?> unit
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="produk.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                <a href="produk.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus produk ini?')"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada produk.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tampilan Mobile (Kartu) -->
                    <div class="d-block d-md-none">
                        <?php if ($products->num_rows > 0): 
                            $products->data_seek(0); 
                        ?>
                            <div class="d-flex flex-column gap-3">
                                <?php while ($row = $products->fetch_assoc()): ?>
                                <div class="card p-3 shadow-sm" style="border: 1px solid rgba(0,0,0,0.08) !important;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row['nama_produk']); ?></h6>
                                            <span class="badge bg-success-subtle text-success small"><?php echo htmlspecialchars($row['nama_kategori']); ?></span>
                                            <div class="small text-muted mt-1">Satuan: <?php echo htmlspecialchars($row['satuan']); ?></div>
                                        </div>
                                        <span class="badge <?php echo $row['stok'] <= 5 ? 'bg-warning text-dark' : 'bg-success'; ?> rounded-pill">
                                            Stok: <?php echo (int)$row['stok']; ?>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top" style="border-top: 1px dashed rgba(0,0,0,0.08) !important;">
                                        <span class="fw-bold text-success">Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></span>
                                        <div class="d-flex gap-2">
                                            <a href="produk.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary px-3"><i class="fas fa-edit me-1"></i> Edit</a>
                                            <a href="produk.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Hapus produk ini?')"><i class="fas fa-trash me-1"></i> Hapus</a>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">Belum ada produk.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
