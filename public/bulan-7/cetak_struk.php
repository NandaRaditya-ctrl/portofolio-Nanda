<?php
require_once __DIR__.'/../shared/native.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die("ID Transaksi tidak valid.");
}

// Fetch transaction detail
$transaksiQuery = $conn->prepare("SELECT t.*, u.nama as kasir FROM transaksi t JOIN users u ON u.id = t.kasir_id WHERE t.id = ?");
$transaksiQuery->bind_param("i", $id);
$transaksiQuery->execute();
$transaksi = $transaksiQuery->get_result()->fetch_assoc();
$transaksiQuery->close();

if (!$transaksi) {
    die("Transaksi tidak ditemukan.");
}

// Fetch detail items
$itemsQuery = $conn->prepare("SELECT dt.*, p.nama_produk, p.satuan FROM detail_transaksi dt JOIN produk p ON p.id = dt.produk_id WHERE dt.transaksi_id = ?");
$itemsQuery->bind_param("i", $id);
$itemsQuery->execute();
$items = $itemsQuery->get_result();
$itemsQuery->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Belanja #<?php echo $id; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .receipt {
            background: #fff;
            width: 300px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-radius: 4px;
            align-self: flex-start;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        .header {
            margin-bottom: 15px;
        }
        .header h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 0;
            font-size: 11px;
            color: #555;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .info-table, .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 2px 0;
            font-size: 11px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            font-size: 11px;
        }
        .items-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 11px;
        }
        .total-section {
            margin-top: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
        }
        .btn-print-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .btn {
            font-family: inherit;
            padding: 6px 12px;
            border: 1px solid #000;
            background: #fff;
            cursor: pointer;
            font-size: 11px;
        }
        .btn:hover {
            background: #000;
            color: #fff;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
                display: block;
            }
            .receipt {
                width: 100%;
                box-shadow: none;
                padding: 0;
            }
            .btn-print-container {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <div class="text-center header">
            <h3>TOKO SEMBAKO</h3>
            <p>Bulan 7 - POS Cashier</p>
            <p>Jl. Raya Toko Sembako No. 123</p>
        </div>

        <table class="info-table">
            <tr>
                <td>No. Struk: #<?php echo $transaksi['id']; ?></td>
                <td class="text-right"><?php echo date('d/m/Y H:i', strtotime($transaksi['created_at'])); ?></td>
            </tr>
            <tr>
                <td>Kasir: <?php echo htmlspecialchars($transaksi['kasir']); ?></td>
                <td class="text-right">Mode: Tunai</td>
            </tr>
        </table>

        <div class="divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="50%">Item</th>
                    <th width="20%" class="text-center">Qty</th>
                    <th width="30%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = $items->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($item['nama_produk']); ?><br>
                        <small>@Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?></small>
                    </td>
                    <td class="text-center"><?php echo $item['qty']; ?> <?php echo htmlspecialchars($item['satuan']); ?></td>
                    <td class="text-right">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="total-section">
            <div class="total-row">
                <span>Total Belanja:</span>
                <span class="bold">Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span>Bayar:</span>
                <span>Rp <?php echo number_format($transaksi['uang_dibayar'], 0, ',', '.'); ?></span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp <?php echo number_format($transaksi['kembalian'], 0, ',', '.'); ?></span>
            </div>
        </div>

        <?php if ($transaksi['catatan']): ?>
        <div class="divider"></div>
        <div style="font-size: 11px;">
            <strong>Catatan:</strong><br>
            <?php echo htmlspecialchars($transaksi['catatan']); ?>
        </div>
        <?php endif; ?>

        <div class="divider"></div>

        <div class="text-center footer">
            <p>Terima Kasih atas Kunjungan Anda</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        </div>

        <div class="btn-print-container">
            <button class="btn" onclick="window.print()">Cetak</button>
            <button class="btn" onclick="window.close()">Tutup</button>
        </div>
    </div>
</body>
</html>
