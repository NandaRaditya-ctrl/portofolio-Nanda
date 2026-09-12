<?php
require_once __DIR__.'/../shared/native.php'; require_once 'includes/header.php'; ?>

<h1 class="page-title">Buku Tamu Digital</h1>

<div class="layout-grid">
    <!-- Kolom Kiri: Form Input -->
    <div>
        <div class="glass-card">
            <h2 style="margin-bottom: 20px; font-family: var(--font-heading);">Tinggalkan Pesan</h2>

            <?php
            // Menampilkan pesan sukses jika ada di Session
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            // Menampilkan pesan error validasi jika ada di Session
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <div>' . $_SESSION['error'] . '</div></div>';
                unset($_SESSION['error']);
            }
            ?>

            <form action="process.php" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                </div>
                
                <div class="form-group">
                    <label for="pesan">Pesan Anda</label>
                    <textarea id="pesan" name="pesan" class="form-control" placeholder="Tulis pesan atau kesan Anda di sini..." required></textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    Kirim Pesan <i class="fas fa-paper-plane" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Kolom Kanan: Daftar Tamu -->
    <div>
        <div class="glass-card" style="height: 100%;">
            <h2 class="messages-header">Daftar Tamu</h2>
            
            <div class="messages-list">
                <?php
                // Melakukan perulangan jika ada data buku tamu di Session
                if (isset($_SESSION['guestbook']) && count($_SESSION['guestbook']) > 0) {
                    foreach ($_SESSION['guestbook'] as $msg) {
                        echo '<div class="message-card">';
                        echo '    <div class="message-head">';
                        echo '        <span class="message-name">' . $msg['nama'] . '</span>';
                        echo '        <span class="message-time">' . $msg['waktu'] . '</span>';
                        echo '    </div>';
                        echo '    <span class="message-email"><i class="fas fa-envelope"></i> ' . $msg['email'] . '</span>';
                        echo '    <div class="message-body">' . nl2br($msg['pesan']) . '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="empty-state">';
                    echo '    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i><br>';
                    echo '    Belum ada pesan. Jadilah yang pertama!';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
