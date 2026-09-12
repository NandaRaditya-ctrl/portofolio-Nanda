<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\InventarisBarang;
use App\Models\InventarisKategori;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = InventarisBarang::sum('jumlah');
        $totalKategori = InventarisKategori::count();
        $barangRusak = InventarisBarang::whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->sum('jumlah');
        $totalItem = InventarisBarang::count();

        // Distribusi per kategori for chart
        $distribusi = InventarisKategori::withCount('barangs')
            ->has('barangs')
            ->orderByDesc('barangs_count')
            ->get();

        // Barang terbaru
        $barangTerbaru = InventarisBarang::with('kategori')
            ->latest()
            ->take(5)
            ->get();

        // Distribusi kondisi
        $kondisiBaik = InventarisBarang::where('kondisi', 'baik')->sum('jumlah');
        $kondisiRusakRingan = InventarisBarang::where('kondisi', 'rusak_ringan')->sum('jumlah');
        $kondisiRusakBerat = InventarisBarang::where('kondisi', 'rusak_berat')->sum('jumlah');

        return view('inventaris.dashboard', compact(
            'totalBarang',
            'totalKategori',
            'barangRusak',
            'totalItem',
            'distribusi',
            'barangTerbaru',
            'kondisiBaik',
            'kondisiRusakRingan',
            'kondisiRusakBerat',
        ));
    }
}
