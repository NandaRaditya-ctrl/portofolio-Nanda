<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$nama_kategori = '';
$isEdit = false;

// Mode Edit: ambil data existing
if ($id > 0) {
    $isEdit = true;
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Kategori tidak ditemukan.";
        header("Location: kategori.php");
        exit;
    }
    $data = $result->fetch_assoc();
    $nama_kategori = $data['nama_kategori'];
    $stmt->close();
}

// Proses POST (Tambah / Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = trim($_POST['nama_kategori']);

    if (empty($nama_kategori)) {
        $_SESSION['error'] = "Nama kategori tidak boleh kosong.";
    } else {
        if ($isEdit) {
            $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = ? WHERE id = ?");
            $stmt->bind_param("si", $nama_kategori, $id);
            $stmt->execute();
            $_SESSION['success'] = "Kategori berhasil diperbarui.";
        } else {
            $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
            $stmt->bind_param("s", $nama_kategori);
            $stmt->execute();
            $_SESSION['success'] = "Kategori berhasil ditambahkan.";
        }
        $stmt->close();
        header("Location: kategori.php");
        exit;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo $isEdit ? 'Edit Kategori' : 'Tambah Kategori'; ?></h1>
    <a href="kategori.php" class="btn btn-outline-secondary shadow-sm">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="card border-0 shadow-sm" style="max-width: 500px;">
    <div class="card-body p-4">
        <form action="" method="POST">
            <div class="mb-4">
                <label for="nama_kategori" class="form-label fw-medium">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" value="<?php echo htmlspecialchars($nama_kategori); ?>" placeholder="Contoh: Novel, Sains, Sejarah" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fas fa-save me-2"></i> <?php echo $isEdit ? 'Perbarui Kategori' : 'Simpan Kategori'; ?>
            </button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
