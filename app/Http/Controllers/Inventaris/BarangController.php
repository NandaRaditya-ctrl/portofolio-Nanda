<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\InventarisBarang;
use App\Models\InventarisKategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = InventarisBarang::with('kategori');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Filter by kategori
        if ($kategoriId = $request->input('kategori')) {
            $query->where('kategori_id', $kategoriId);
        }

        // Filter by kondisi
        if ($kondisi = $request->input('kondisi')) {
            $query->where('kondisi', $kondisi);
        }

        $barangs = $query->orderByDesc('id')->paginate(10)->withQueryString();
        $kategoris = InventarisKategori::orderBy('nama_kategori')->get();

        return view('inventaris.barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $kategoris = InventarisKategori::orderBy('nama_kategori')->get();

        return view('inventaris.barang.form', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:inventaris_kategoris,id',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $kode = InventarisBarang::generateKode($request->kategori_id);

        InventarisBarang::create([
            'kode_barang' => $kode,
            'nama_barang' => $request->nama_barang,
            'kategori_id' => $request->kategori_id,
            'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'lokasi' => $request->lokasi,
            'tanggal_masuk' => $request->tanggal_masuk,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('inventaris.barang.index')
            ->with('success', 'Barang berhasil ditambahkan dengan kode: ' . $kode);
    }

    public function show(InventarisBarang $barang)
    {
        $barang->load('kategori');

        return view('inventaris.barang.show', compact('barang'));
    }

    public function edit(InventarisBarang $barang)
    {
        $kategoris = InventarisKategori::orderBy('nama_kategori')->get();

        return view('inventaris.barang.form', compact('barang', 'kategoris'));
    }

    public function update(Request $request, InventarisBarang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:inventaris_kategoris,id',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $barang->update($request->only(
            'nama_barang', 'kategori_id', 'jumlah', 'kondisi',
            'lokasi', 'tanggal_masuk', 'keterangan'
        ));

        return redirect()->route('inventaris.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(InventarisBarang $barang)
    {
        $barang->delete();

        return redirect()->route('inventaris.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
