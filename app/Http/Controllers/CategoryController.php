<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->with(['products' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderBy('id', 'desc')
            ->get();

        return view('owner.manajemenkategori', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('kategori', 'public');
        }

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'gambar' => $gambarPath,
        ]);

        return redirect()
            ->route('manajemen.kategori')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori,' . $id,
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = [
            'nama_kategori' => $request->nama_kategori,
        ];

        if ($request->hasFile('gambar')) {
            if ($category->gambar && Storage::disk('public')->exists($category->gambar)) {
                Storage::disk('public')->delete($category->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('kategori', 'public');
        }

        $category->update($data);

        return redirect()
            ->route('manajemen.kategori')
            ->with('success', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return redirect()
                ->route('manajemen.kategori')
                ->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk');
        }

        if ($category->gambar && Storage::disk('public')->exists($category->gambar)) {
            Storage::disk('public')->delete($category->gambar);
        }

        $category->delete();

        return redirect()
            ->route('manajemen.kategori')
            ->with('success', 'Kategori berhasil dihapus');
    }
}