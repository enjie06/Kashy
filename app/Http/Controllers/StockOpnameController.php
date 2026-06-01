<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use App\Models\Category;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
   public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $stockOpnames = StockOpname::whereDate('tanggal', $tanggal)
            ->latest()
            ->get();

        $categories = Category::orderBy('nama_kategori', 'asc')->get();

        return view('owner.stokopname', compact('stockOpnames', 'tanggal', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'harga' => 'nullable|string',
            'stok_sistem' => 'required|integer|min:0',
            'stok_fisik' => 'required|integer|min:0',
            'catatan' => 'nullable|string',
        ]);

        $harga = (int) str_replace(['.', ',', 'Rp', ' '], '', $data['harga'] ?? 0);
        $selisih = $data['stok_fisik'] - $data['stok_sistem'];

        $status = 'match';

        if ($selisih < 0) {
            $status = 'short';
        } elseif ($selisih > 0) {
            $status = 'excess';
        }

        StockOpname::create([
            'tanggal' => $data['tanggal'],
            'nama_produk' => $data['nama_produk'],
            'kategori' => $data['kategori'],
            'harga' => $harga,
            'stok_sistem' => $data['stok_sistem'],
            'stok_fisik' => $data['stok_fisik'],
            'selisih' => $selisih,
            'status' => $status,
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect()
        ->route('stokopname', ['tanggal' => $data['tanggal']])
        ->with('success', 'Stok opname berhasil disimpan');
    }

    public function destroy(StockOpname $stockOpname)
    {
        $tanggal = $stockOpname->tanggal;

        $stockOpname->delete();

        return redirect()
        ->route('stokopname', ['tanggal' => $tanggal])
        ->with('success', 'Entri berhasil dihapus');
    }
}