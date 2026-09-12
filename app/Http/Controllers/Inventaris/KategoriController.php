<?php

namespace App\Http\Controllers\Inventaris;

use App\Http\Controllers\Controller;
use App\Models\InventarisKategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = InventarisKategori::withCount('barangs')
            ->orderByDesc('id')
            ->get();

        return view('inventaris.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('inventaris.kategori.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:inventaris_kategoris,nama_kategori',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        InventarisKategori::create($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('inventaris.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(InventarisKategori $kategori)
    {
        return view('inventaris.kategori.form', compact('kategori'));
    }

    public function update(Request $request, InventarisKategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:inventaris_kategoris,nama_kategori,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kategori->update($request->only('nama_kategori', 'deskripsi'));

        return redirect()->route('inventaris.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(InventarisKategori $kategori)
    {
        if ($kategori->barangs()->exists()) {
            return back()->with('error', 'Pindahkan atau hapus barang dalam kategori ini terlebih dahulu.');
        }
        $kategori->delete();

        return redirect()->route('inventaris.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
