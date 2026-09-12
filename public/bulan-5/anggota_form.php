<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; requireAdmin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$isEdit = false;
$data = ['nama' => '', 'email' => ''];

if ($id > 0) {
    $isEdit = true;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND role = 'anggota'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Anggota tidak ditemukan.";
        header("Location: anggota.php");
        exit;
    }
    $data = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($nama) || empty($email)) {
        $_SESSION['error'] = "Nama dan email harus diisi.";
    } elseif (!$isEdit && empty($password)) {
        $_SESSION['error'] = "Password harus diisi untuk anggota baru.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid.";
    } else {
        if ($isEdit) {
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET nama=?, email=?, password=? WHERE id=?");
                $stmt->bind_param("sssi", $nama, $email, $hash, $id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET nama=?, email=? WHERE id=?");
                $stmt->bind_param("ssi", $nama, $email, $id);
            }
            $stmt->execute();
            $_SESSION['success'] = "Anggota berhasil diperbarui.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'anggota')");
            $stmt->bind_param("sss", $nama, $email, $hash);
            $stmt->execute();
            $_SESSION['success'] = "Anggota berhasil ditambahkan.";
        }
        $stmt->close();
        header("Location: anggota.php");
        exit;
    }
}
?>

<div class="page-header">
    <h1 class="page-title"><?php echo $isEdit ? 'Edit Anggota' : 'Tambah Anggota'; ?></h1>
    <a href="anggota.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="glass-card" style="max-width: 500px;">
    <form action="" method="POST">
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password <?php echo $isEdit ? '(kosongkan jika tidak diubah)' : ''; ?></label>
            <input type="password" id="password" name="password" class="form-control" <?php echo $isEdit ? '' : 'required'; ?>>
        </div>
        <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> <?php echo $isEdit ? 'Perbarui' : 'Simpan'; ?>
        </button>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
