<?php
function journey_query(mysqli $db, string $sql, array $values = []): mysqli_stmt {
    $stmt = $db->prepare($sql);
    if (!$stmt || !$stmt->execute($values)) throw new RuntimeException('Operasi database gagal.');
    return $stmt;
}
function journey_borrow(mysqli $db, int $member, array $books): int {
    $ids = array_values(array_unique(array_map('intval', $books))); sort($ids);
    if ($member < 1 || !$ids || count($ids) > 20 || min($ids) < 1) throw new DomainException('Pilih anggota dan 1–20 buku yang valid.');
    $db->begin_transaction();
    try {
        if (!journey_query($db,"SELECT id FROM users WHERE id=? AND role='anggota'",[$member])->get_result()->fetch_assoc()) throw new DomainException('Anggota tidak ditemukan.');
        foreach ($ids as $id) {
            $book = journey_query($db,'SELECT stok FROM buku WHERE id=? FOR UPDATE',[$id])->get_result()->fetch_assoc();
            if (!$book || $book['stok'] < 1) throw new DomainException('Salah satu buku sudah tidak tersedia. Pilih ulang buku.');
        }
        journey_query($db,'INSERT INTO peminjaman (user_id,tgl_pinjam) VALUES (?,?)',[$member,date('Y-m-d')]);
        $loan = (int)$db->insert_id;
        foreach ($ids as $id) {
            journey_query($db,'UPDATE buku SET stok=stok-1 WHERE id=?',[$id]);
            journey_query($db,'INSERT INTO detail_peminjaman (peminjaman_id,buku_id) VALUES (?,?)',[$loan,$id]);
        }
        $db->commit(); return $loan;
    } catch (Throwable $e) { $db->rollback(); throw $e; }
}
function journey_return(mysqli $db, int $loan): bool {
    $db->begin_transaction();
    try {
        $row=journey_query($db,'SELECT status FROM peminjaman WHERE id=? FOR UPDATE',[$loan])->get_result()->fetch_assoc();
        if (!$row || $row['status'] !== 'dipinjam') { $db->rollback(); return false; }
        $books=journey_query($db,'SELECT buku_id FROM detail_peminjaman WHERE peminjaman_id=? ORDER BY buku_id',[$loan])->get_result()->fetch_all(MYSQLI_ASSOC);
        foreach($books as $book) journey_query($db,'UPDATE buku SET stok=stok+1 WHERE id=?',[$book['buku_id']]);
        journey_query($db,"UPDATE peminjaman SET status='dikembalikan',tgl_kembali=? WHERE id=?",[date('Y-m-d'),$loan]);
        $db->commit(); return true;
    } catch (Throwable $e) { $db->rollback(); throw $e; }
}
function journey_checkout(mysqli $db, int $cashier, array $cart, int $paid, string $note): int {
    if (!$cart || count($cart)>100 || $paid<0 || $paid>2000000000 || mb_strlen($note)>500) throw new DomainException('Periksa keranjang, pembayaran, dan panjang catatan.');
    ksort($cart); $db->begin_transaction();
    try {
        $total=0; $lines=[];
        foreach($cart as $item) {
            $id=filter_var($item['id'] ?? null,FILTER_VALIDATE_INT); $qty=filter_var($item['qty'] ?? null,FILTER_VALIDATE_INT);
            if (!$id || !$qty || $qty<1 || $qty>10000) throw new DomainException('Jumlah produk tidak valid.');
            $product=journey_query($db,'SELECT nama_produk,harga_jual,stok FROM produk WHERE id=? FOR UPDATE',[$id])->get_result()->fetch_assoc();
            if (!$product || $product['stok']<$qty) throw new DomainException('Stok berubah atau produk tidak tersedia. Periksa keranjang kembali.');
            $price=(int)$product['harga_jual'];
            if ($price<0) throw new DomainException('Harga produk tidak valid.');
            $total += $price*$qty;
            if($total>2000000000) throw new DomainException('Nilai transaksi terlalu besar.');
            $lines[]=[$id,$qty,$price,$price*$qty];
        }
        if($paid<$total) throw new DomainException('Pembayaran kurang. Total terkini Rp '.number_format($total,0,',','.').'.');
        journey_query($db,'INSERT INTO transaksi (kasir_id,total_harga,uang_dibayar,kembalian,catatan) VALUES (?,?,?,?,?)',[$cashier,$total,$paid,$paid-$total,$note]);
        $transaction=(int)$db->insert_id;
        foreach($lines as [$id,$qty,$price,$subtotal]) {
            journey_query($db,'INSERT INTO detail_transaksi (transaksi_id,produk_id,qty,harga_satuan,subtotal) VALUES (?,?,?,?,?)',[$transaction,$id,$qty,$price,$subtotal]);
            journey_query($db,'UPDATE produk SET stok=stok-? WHERE id=?',[$qty,$id]);
        }
        $db->commit(); return $transaction;
    } catch(Throwable $e) { $db->rollback(); throw $e; }
}
