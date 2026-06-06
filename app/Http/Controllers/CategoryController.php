<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CategoryLog;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->with(['products' => function($query) {
                $query->latest()->limit(1);
            }])
            ->get();
        return view('owner.manajemenkategori', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:100|unique:categories'
    ]);

    Category::create([
        'nama_kategori' => $request->nama_kategori
    ]);

    return redirect()->route('manajemen.kategori')->with('success', 'Kategori berhasil ditambahkan');
}

    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);
    
    $request->validate([
        'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori,' . $id
    ]);

    $category->update([
        'nama_kategori' => $request->nama_kategori
    ]);

    // GANTI redirect (bukan response JSON)
    return redirect()->route('manajemen.kategori')->with('success', 'Kategori berhasil diupdate');
}

public function destroy($id)
{
    $category = Category::findOrFail($id);
    
    if ($category->products()->count() > 0) {
        return redirect()->route('manajemen.kategori')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki produk');
    }
    
    $category->delete();

    // GANTI redirect (bukan response JSON)
    return redirect()->route('manajemen.kategori')->with('success', 'Kategori berhasil dihapus');
}
}