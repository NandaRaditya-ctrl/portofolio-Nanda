<?php
require_once __DIR__.'/../shared/native.php';
require_once 'includes/header.php';
requireLogin();

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $productId = (int)($_GET['id'] ?? 0);
    $cart = $_SESSION['cart'] ?? [];

    if ($action === 'add' && $productId > 0) {
        $productStmt = $conn->prepare("SELECT id, nama_produk, harga_jual, stok FROM produk WHERE id = ?");
        $productStmt->bind_param('i', $productId);
        $productStmt->execute();
        $productResult = $productStmt->get_result();
        if ($productResult && $productResult->num_rows > 0) {
            $product = $productResult->fetch_assoc();
            if (!isset($cart[$productId])) {
                $cart[$productId] = ['id' => $product['id'], 'nama' => $product['nama_produk'], 'harga' => $product['harga_jual'], 'qty' => 1];
            } else {
                $cart[$productId]['qty'] += 1;
            }
            if ($cart[$productId]['qty'] > $product['stok']) {
                $cart[$productId]['qty'] = $product['stok'];
                $_SESSION['error'] = 'Stok tidak mencukupi untuk produk ini.';
            } else {
                $_SESSION['success'] = 'Produk ditambahkan ke keranjang.';
            }
        }
        $productStmt->close();
        $_SESSION['cart'] = $cart;
        header('Location: transaksi.php');
        exit;
    }

    if ($action === 'inc' && $productId > 0) {
        $productStmt = $conn->prepare("SELECT stok FROM produk WHERE id = ?");
        $productStmt->bind_param('i', $productId);
        $productStmt->execute();
        $productResult = $productStmt->get_result();
        if ($productResult && $productResult->num_rows > 0) {
            $product = $productResult->fetch_assoc();
            if (isset($cart[$productId])) {
                $cart[$productId]['qty'] += 1;
                if ($cart[$productId]['qty'] > $product['stok']) {
                    $cart[$productId]['qty'] = $product['stok'];
                    $_SESSION['error'] = 'Stok tidak mencukupi.';
                }
            }
        }
        $productStmt->close();
        $_SESSION['cart'] = $cart;
        header('Location: transaksi.php');
        exit;
    }

    if ($action === 'dec' && $productId > 0) {
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] -= 1;
            if ($cart[$productId]['qty'] <= 0) {
                unset($cart[$productId]);
            }
        }
        $_SESSION['cart'] = $cart;
        header('Location: transaksi.php');
        exit;
    }

    if ($action === 'remove' && $productId > 0) {
        unset($cart[$productId]);
        $_SESSION['cart'] = $cart;
        header('Location: transaksi.php');
        exit;
    }

    if ($action === 'clear') {
        unset($_SESSION['cart']);
        header('Location: transaksi.php');
        exit;
    }
}

require_once __DIR__.'/../shared/transactions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    try {
        $paid = filter_var($_POST['uang_dibayar'] ?? null, FILTER_VALIDATE_INT);
        if ($paid === false) throw new DomainException('Pembayaran harus berupa bilangan bulat.');
        $id = journey_checkout($conn, (int)$_SESSION['user']['id'], $_SESSION['cart'] ?? [], $paid, trim((string)($_POST['catatan'] ?? '')));
        unset($_SESSION['cart']);
        $_SESSION['success_transaksi'] = $id;
        $_SESSION['success'] = 'Transaksi berhasil disimpan dengan harga terbaru.';
    } catch (Throwable $e) { $_SESSION['error'] = $e instanceof DomainException ? $e->getMessage() : 'Transaksi gagal. Stok dan penjualan tidak berubah.'; }
    header('Location: transaksi.php'); exit;
}

$cart = $_SESSION['cart'] ?? [];
$products = $conn->query("SELECT id, nama_produk, harga_jual, stok, satuan FROM produk WHERE stok > 0 ORDER BY nama_produk ASC");
?>

<style>
    @media (min-width: 992px) {
        .tab-content-desktop.row {
            display: flex !important;
        }
        .tab-pane-desktop.tab-pane {
            display: block !important;
            opacity: 1 !important;
        }
    }

    @media (max-width: 576px) {
        .product-grid {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.75rem !important;
        }
        .product-card {
            margin-bottom: 0 !important;
            box-shadow: 0 4px 10px rgba(15,23,42,0.04) !important;
        }
        .product-card .card-body {
            padding: 0.8rem 1rem !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 12px !important;
        }
        .product-card .card-body > div:first-child {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            justify-content: center !important;
            gap: 2px !important;
            margin-bottom: 0 !important;
            flex-grow: 1 !important;
            text-align: left !important;
        }
        .product-card .card-body > div:first-child > div {
            text-align: left !important;
        }
        .product-card .card-body h6 {
            margin-bottom: 2px !important;
            font-size: 0.95rem !important;
            line-height: 1.2 !important;
        }
        .product-card .card-body > div:first-child .badge {
            display: none !important; /* Sembunyikan badge 'Ready' yang redundan di mobile */
        }
        .product-card .card-body h5 {
            margin-bottom: 0 !important;
            font-size: 0.95rem !important;
            white-space: nowrap !important;
            font-weight: 700 !important;
            min-width: 75px !important;
            text-align: right !important;
        }
        .product-card .card-body .btn {
            width: auto !important;
            padding: 0.35rem 0.6rem !important;
            font-size: 0.75rem !important;
            white-space: nowrap !important;
        }
    }
</style>

<div class="page-shell">
    <div class="hero-panel p-4 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <p class="text-uppercase small text-success fw-bold mb-2">Point of Sale</p>
                <h1 class="h3 mb-2">Halaman transaksi kasir yang cepat dan rapi</h1>
                <p class="text-muted mb-0">Pilih produk, atur jumlah, lalu bayarkan dengan tampilan yang lebih modern.</p>
            </div>
            <div class="d-flex align-items-center gap-2 text-success fw-semibold">
                <i class="fas fa-cash-register"></i>
                <span>Mode penjualan aktif</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs for Mobile -->
    <ul class="nav nav-pills nav-fill gap-2 mb-4 d-lg-none" id="cashierTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active py-2.5 rounded-3" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-pane" type="button" role="tab" aria-controls="products-pane" aria-selected="true">
                <i class="fas fa-boxes me-2"></i>Produk
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link position-relative py-2.5 rounded-3" id="cart-tab" data-bs-toggle="tab" data-bs-target="#cart-pane" type="button" role="tab" aria-controls="cart-pane" aria-selected="false">
                <i class="fas fa-shopping-cart me-2"></i>Keranjang
                <?php if (!empty($cart)): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.75rem; transform: translate(-50%, -30%) !important;">
                    <?php echo count($cart); ?>
                </span>
                <?php endif; ?>
            </button>
        </li>
    </ul>

    <div class="row g-4 tab-content tab-content-desktop" id="cashierTabContent">
        <!-- Products Pane -->
        <div class="tab-pane fade show active tab-pane-desktop col-lg-8" id="products-pane" role="tabpanel" aria-labelledby="products-tab">
            <div class="card border-0 kpi-card p-3 mb-4">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <h5 class="card-title mb-1">Cari Produk</h5>
                            <p class="text-muted mb-0">Temukan barang yang Anda cari lebih cepat.</p>
                        </div>
                        <input type="text" class="form-control w-auto" id="searchProduct" placeholder="Cari nama produk..." style="max-width: 280px;">
                    </div>
                </div>
            </div>
            <div class="card border-0 kpi-card p-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-1">Daftar Produk</h5>
                            <p class="text-muted mb-0">Klik produk untuk menambah ke keranjang.</p>
                        </div>
                        <span class="badge bg-success-subtle text-success px-3 py-2">Tersedia</span>
                    </div>
                    <div class="product-grid">
                        <?php while ($product = $products->fetch_assoc()): ?>
                        <div class="card product-card" data-name="<?php echo htmlspecialchars(strtolower($product['nama_produk'])); ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($product['nama_produk']); ?></h6>
                                        <p class="text-muted small mb-0">Stok: <?php echo (int)$product['stok']; ?> <?php echo htmlspecialchars($product['satuan']); ?></p>
                                    </div>
                                    <span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 0.75rem;"><i class="fas fa-check-circle me-1"></i> Ready</span>
                                </div>
                                <h5 class="fw-bold text-success mb-3">Rp <?php echo number_format($product['harga_jual'], 0, ',', '.'); ?></h5>
                                <a href="transaksi.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i> Tambah
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Pane -->
        <div class="tab-pane fade tab-pane-desktop col-lg-4" id="cart-pane" role="tabpanel" aria-labelledby="cart-tab">
            <div class="card border-0 cart-panel">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="card-title mb-1">Keranjang</h5>
                            <p class="text-muted mb-0">Belanjaan saat ini</p>
                        </div>
                        <?php if (!empty($cart)): ?>
                        <a href="transaksi.php?action=clear" class="btn btn-sm btn-outline-danger">Kosongkan</a>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($cart)): ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-shopping-cart fa-3x mb-3 opacity-50"></i>
                            <p class="mb-0">Keranjang masih kosong.</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush mb-3">
                            <?php $total = 0; foreach ($cart as $item): $subtotal = $item['harga'] * $item['qty']; $total += $subtotal; ?>
                            <div class="list-group-item px-0 py-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($item['nama']); ?></div>
                                        <div class="small text-muted">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?> / item</div>
                                    </div>
                                    <a href="transaksi.php?action=remove&id=<?php echo $item['id']; ?>" class="text-danger small"><i class="fas fa-times"></i></a>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="transaksi.php?action=dec&id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary">-</a>
                                        <span class="btn btn-outline-secondary disabled"><?php echo $item['qty']; ?></span>
                                        <a href="transaksi.php?action=inc&id=<?php echo $item['id']; ?>" class="btn btn-outline-secondary">+</a>
                                    </div>
                                    <div class="fw-semibold">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total</span>
                                <strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                            </div>
                            <div class="alert alert-light border mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted">Kembalian</span>
                                    <strong id="changePreview">Rp 0</strong>
                                </div>
                                <div class="small text-muted mt-1" id="paymentStatus">Masukkan nominal pembayaran untuk memeriksa status.</div>
                            </div>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Uang Dibayar</label>
                                    <input type="number" class="form-control" name="uang_dibayar" min="0" required id="cashInput" data-total="<?php echo $total; ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan</label>
                                    <input type="text" class="form-control" name="catatan" placeholder="Opsional">
                                </div>
                                <button type="submit" name="checkout" class="btn btn-success w-100" id="payButton">
                                    <i class="fas fa-check me-2"></i> Proses Bayar
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchProduct');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                document.querySelectorAll('.product-card').forEach(function (card) {
                    const name = card.getAttribute('data-name') || '';
                    card.style.display = name.includes(query) ? '' : 'none';
                });
            });
        }

        const cashInput = document.getElementById('cashInput');
        const changePreview = document.getElementById('changePreview');
        const paymentStatus = document.getElementById('paymentStatus');
        const payButton = document.getElementById('payButton');

        function formatRupiah(value) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
        }

        if (cashInput) {
            const total = parseInt(cashInput.getAttribute('data-total') || '0', 10);
            const updatePreview = function () {
                const paid = parseInt(cashInput.value || '0', 10);
                const change = paid - total;
                changePreview.textContent = formatRupiah(change > 0 ? change : 0);

                if (paid < total) {
                    paymentStatus.textContent = 'Nominal pembayaran masih kurang.';
                    paymentStatus.className = 'small text-danger mt-1';
                    payButton.disabled = true;
                } else if (paid >= total) {
                    paymentStatus.textContent = 'Pembayaran cukup. Transaksi siap diproses.';
                    paymentStatus.className = 'small text-success mt-1';
                    payButton.disabled = false;
                }
            };

            cashInput.addEventListener('input', updatePreview);
            updatePreview();
        }

        // Tab state preservation in sessionStorage
        const cartEmpty = <?php echo empty($cart) ? 'true' : 'false'; ?>;
        if (cartEmpty) {
            sessionStorage.removeItem('cashierActiveTab');
        } else {
            const activeTabId = sessionStorage.getItem('cashierActiveTab');
            if (activeTabId) {
                const tabEl = document.getElementById(activeTabId);
                if (tabEl) {
                    const tab = new bootstrap.Tab(tabEl);
                    tab.show();
                }
            }
        }

        const tabLinks = document.querySelectorAll('#cashierTab button');
        tabLinks.forEach(tabLink => {
            tabLink.addEventListener('shown.bs.tab', function (e) {
                sessionStorage.setItem('cashierActiveTab', e.target.id);
            });
        });
    });
</script>

<?php require_once 'includes/footer.php'; ?>
