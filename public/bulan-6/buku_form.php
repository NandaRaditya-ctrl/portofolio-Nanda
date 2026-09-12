<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = false;
$data = ['judul' => '', 'pengarang' => '', 'penerbit' => '', 'tahun' => date('Y'), 'stok' => 1, 'kategori_id' => 0];

if ($id > 0) {
    $isEdit = true;
    $stmt = $conn->prepare("SELECT * FROM buku WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Buku tidak ditemukan.";
        header("Location: buku.php");
        exit;
    }
    $data = $result->fetch_assoc();
    $stmt->close();
}

// Proses POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun = intval($_POST['tahun']);
    $stok = intval($_POST['stok']);
    $kategori_id = intval($_POST['kategori_id']);

    if (empty($judul) || empty($pengarang) || empty($penerbit) || $kategori_id == 0) {
        $_SESSION['error'] = "Semua field harus diisi.";
    } else {
        if ($isEdit) {
            $stmt = $conn->prepare("UPDATE buku SET judul=?, pengarang=?, penerbit=?, tahun=?, stok=?, kategori_id=? WHERE id=?");
            $stmt->bind_param("sssiiii", $judul, $pengarang, $penerbit, $tahun, $stok, $kategori_id, $id);
            $stmt->execute();
            $_SESSION['success'] = "Buku berhasil diperbarui.";
        } else {
            $stmt = $conn->prepare("INSERT INTO buku (judul, pengarang, penerbit, tahun, stok, kategori_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiii", $judul, $pengarang, $penerbit, $tahun, $stok, $kategori_id);
            $stmt->execute();
            $_SESSION['success'] = "Buku berhasil ditambahkan.";
        }
        $stmt->close();
        header("Location: buku.php");
        exit;
    }
}

// Ambil daftar kategori untuk dropdown
$kategoriList = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo $isEdit ? 'Edit Buku' : 'Tambah Buku'; ?></h1>
    <a href="buku.php" class="btn btn-outline-secondary shadow-sm">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 700px;">
    <div class="card-body p-4">
        <form action="" method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label fw-medium">Judul Buku</label>
                <input type="text" id="judul" name="judul" class="form-control" value="<?php echo htmlspecialchars($data['judul']); ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="pengarang" class="form-label fw-medium">Pengarang</label>
                    <input type="text" id="pengarang" name="pengarang" class="form-control" value="<?php echo htmlspecialchars($data['pengarang']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="penerbit" class="form-label fw-medium">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" class="form-control" value="<?php echo htmlspecialchars($data['penerbit']); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tahun" class="form-label fw-medium">Tahun Terbit</label>
                    <input type="number" id="tahun" name="tahun" class="form-control" value="<?php echo $data['tahun']; ?>" min="1900" max="<?php echo date('Y'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="stok" class="form-label fw-medium">Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control" value="<?php echo $data['stok']; ?>" min="0" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="kategori_id" class="form-label fw-medium">Kategori</label>
                <select id="kategori_id" name="kategori_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kat = $kategoriList->fetch_assoc()): ?>
                    <option value="<?php echo $kat['id']; ?>" <?php echo ($data['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-save me-2"></i> <?php echo $isEdit ? 'Perbarui Buku' : 'Simpan Buku'; ?>
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
