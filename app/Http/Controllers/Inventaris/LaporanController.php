<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\InventarisBarang;
use App\Models\InventarisKategori;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $kondisiFilter = $request->input('kondisi');

        // Ringkasan per kategori
        $kategoris = InventarisKategori::withCount('barangs')
            ->with(['barangs' => function ($q) use ($kondisiFilter) {
                if ($kondisiFilter) {
                    $q->where('kondisi', $kondisiFilter);
                }
            }])
            ->orderBy('nama_kategori')
            ->get()
            ->map(function ($kategori) {
                $kategori->total_jumlah = $kategori->barangs->sum('jumlah');
                $kategori->jumlah_baik = $kategori->barangs->where('kondisi', 'baik')->sum('jumlah');
                $kategori->jumlah_rusak_ringan = $kategori->barangs->where('kondisi', 'rusak_ringan')->sum('jumlah');
                $kategori->jumlah_rusak_berat = $kategori->barangs->where('kondisi', 'rusak_berat')->sum('jumlah');
                return $kategori;
            });

        // Summary totals
        $totalBarang = $kategoris->sum('total_jumlah');
        $totalBaik = $kategoris->sum('jumlah_baik');
        $totalRusakRingan = $kategoris->sum('jumlah_rusak_ringan');
        $totalRusakBerat = $kategoris->sum('jumlah_rusak_berat');

        return view('inventaris.laporan.index', compact(
            'kategoris',
            'totalBarang',
            'totalBaik',
            'totalRusakRingan',
            'totalRusakBerat',
            'kondisiFilter',
        ));
    }
}
