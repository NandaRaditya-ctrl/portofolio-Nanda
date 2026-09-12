<?php
require_once __DIR__.'/../shared/native.php';
require_once 'includes/header.php';
requireLogin();

$from = $conn->real_escape_string(trim($_GET['from'] ?? ''));
$to = $conn->real_escape_string(trim($_GET['to'] ?? ''));
$q = $conn->real_escape_string(trim($_GET['q'] ?? ''));

$where = [];
if ($from !== '') {
    $where[] = "DATE(t.created_at) >= '$from'";
}
if ($to !== '') {
    $where[] = "DATE(t.created_at) <= '$to'";
}
if ($q !== '') {
    $where[] = "(t.id LIKE '%$q%' OR u.nama LIKE '%$q%')";
}

$query = "SELECT t.*, u.nama as kasir FROM transaksi t JOIN users u ON u.id = t.kasir_id";
if (!empty($where)) {
    $query .= " WHERE " . implode(' AND ', $where);
}
$query .= " ORDER BY t.id DESC";

$transactionsResult = $conn->query($query);
$transactions = [];
$totalRevenue = 0;
if ($transactionsResult) {
    while ($row = $transactionsResult->fetch_assoc()) {
        $transactions[] = $row;
        $totalRevenue += (int)$row['total_harga'];
    }
}

$detailId = (int)($_GET['detail_id'] ?? 0);
$detailItems = [];
if ($detailId > 0) {
    $detailQuery = $conn->query("SELECT dt.qty, dt.harga_satuan, dt.subtotal, p.nama_produk FROM detail_transaksi dt JOIN produk p ON p.id = dt.produk_id WHERE dt.transaksi_id = $detailId");
    if ($detailQuery) {
        while ($row = $detailQuery->fetch_assoc()) {
            $detailItems[] = $row;
        }
    }
}
?>

<div class="page-shell">
    <div class="hero-panel p-4 mb-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
            <div>
                <p class="text-uppercase small text-success fw-bold mb-2">Riwayat Penjualan</p>
                <h1 class="h3 mb-2">Pantau semua transaksi toko dengan lebih jelas</h1>
                <p class="text-muted mb-0">Lihat daftar penjualan, total pembayaran, dan catatan transaksi dari satu halaman.</p>
            </div>
            <div class="d-flex align-items-center gap-2 text-success fw-semibold">
                <i class="fas fa-history"></i>
                <span>Catatan penjualan harian</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 kpi-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success"><i class="fas fa-receipt fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">Total Transaksi</h6>
                            <h3 class="mb-0"><?php echo count($transactions); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 kpi-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary"><i class="fas fa-money-bill-wave fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">Pendapatan Terfilter</h6>
                            <h3 class="mb-0">Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 kpi-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning"><i class="fas fa-filter fa-lg"></i></div>
                        <div>
                            <h6 class="mb-1">Filter Aktif</h6>
                            <h3 class="mb-0"><?php echo $from || $to || $q ? 'Ya' : 'Tidak'; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 kpi-card mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" name="from" value="<?php echo htmlspecialchars($from); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" name="to" value="<?php echo htmlspecialchars($to); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari Transaksi/Kasir</label>
                    <input type="text" class="form-control" name="q" placeholder="Contoh: 12 atau Ana" value="<?php echo htmlspecialchars($q); ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success w-100">Terapkan</button>
                    <a href="riwayat.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <?php if ($detailId > 0 && !empty($detailItems)): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="card-title mb-1">Detail Transaksi #<?php echo $detailId; ?></h5>
                    <p class="text-muted mb-0">Daftar produk yang dibeli dalam transaksi ini.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="cetak_struk.php?id=<?php echo $detailId; ?>" target="_blank" class="btn btn-sm btn-success"><i class="fas fa-print me-1"></i> Cetak Struk</a>
                    <a href="riwayat.php" class="btn btn-sm btn-outline-secondary">Tutup</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detailItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                            <td><?php echo (int)$item['qty']; ?></td>
                            <td>Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card border-0 kpi-card">
        <div class="card-body p-4">
            <div class="table-responsive table-card">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Transaksi</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th class="d-none d-md-table-cell">Bayar</th>
                            <th class="d-none d-md-table-cell">Kembalian</th>
                            <th>Waktu</th>
                            <th class="d-none d-lg-table-cell">Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transactions)): ?>
                            <?php foreach ($transactions as $row): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['kasir']); ?></td>
                                <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td class="d-none d-md-table-cell">Rp <?php echo number_format($row['uang_dibayar'], 0, ',', '.'); ?></td>
                                <td class="d-none d-md-table-cell">Rp <?php echo number_format($row['kembalian'], 0, ',', '.'); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></td>
                                <td class="d-none d-lg-table-cell"><?php echo htmlspecialchars($row['catatan'] ?: '-'); ?></td>
                                <td>
                                    <a href="riwayat.php?detail_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat transaksi.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
