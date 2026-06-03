<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // tambahkan ini

class ProductController extends Controller
{
    // Dipakai karyawan & owner untuk lihat stok
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('karyawan.stok-produk', compact('products'));
    }

    // ========== TAMBAHAN UNTUK PELANGGAN (KATALOG) ==========
    
    // Halaman katalog pelanggan
    public function katalog()
    {
        $products = Product::with('category')->where('stok', '>', 0)->get();
        $categories = Category::all();
        return view('pelanggan.katalog', compact('products', 'categories'));
    }

    // Detail produk untuk pelanggan
    public function detail($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pelanggan.detailproduk', compact('product'));
    }

    // API untuk mengambil produk (dipakai halaman transaksi kasir)
    public function getProducts()
    {
        $products = Product::where('stok', '>', 0)->get();
        return response()->json($products);
    }

    // ========== END TAMBAHAN ==========

    // API untuk mengambil semua produk (dipakai halaman manajemen produk owner)
    public function getProductsJson()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    // Nanti dipakai owner untuk halaman daftar produk CRUD
    public function ownerIndex()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();

        return view('owner.manajemenproduk', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('owner.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'ukuran' => 'nullable|string',
            'warna' => 'nullable|string',
            'gender' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_discount' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        Product::create($data);

        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('owner.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'ukuran' => 'nullable|string',
            'warna' => 'nullable|string',
            'gender' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_discount' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $product->update($data);

        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil diupdate');
    }

    public function destroy(Product $product)
    {
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        
        $product->delete();

        return redirect()->route('owner.products.index')->with('success', 'Produk berhasil dihapus');
    }
}