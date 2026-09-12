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

<div class="page-header">
    <h1 class="page-title"><?php echo $isEdit ? 'Edit Buku' : 'Tambah Buku'; ?></h1>
    <a href="buku.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="glass-card" style="max-width: 700px;">
    <form action="" method="POST">
        <div class="form-group">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="judul" class="form-control" value="<?php echo htmlspecialchars($data['judul']); ?>" required>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="pengarang">Pengarang</label>
                <input type="text" id="pengarang" name="pengarang" class="form-control" value="<?php echo htmlspecialchars($data['pengarang']); ?>" required>
            </div>
            <div class="form-group">
                <label for="penerbit">Penerbit</label>
                <input type="text" id="penerbit" name="penerbit" class="form-control" value="<?php echo htmlspecialchars($data['penerbit']); ?>" required>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="tahun">Tahun Terbit</label>
                <input type="number" id="tahun" name="tahun" class="form-control" value="<?php echo $data['tahun']; ?>" min="1900" max="<?php echo date('Y'); ?>" required>
            </div>
            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" id="stok" name="stok" class="form-control" value="<?php echo $data['stok']; ?>" min="0" required>
            </div>
        </div>
        <div class="form-group">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($kat = $kategoriList->fetch_assoc()): ?>
                <option value="<?php echo $kat['id']; ?>" <?php echo ($data['kategori_id'] == $kat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Perbarui' : 'Simpan'; ?>
        </button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
