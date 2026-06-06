<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ========== UNTUK KARYAWAN ==========
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('karyawan.stok-produk', compact('products'));
    }

    // ========== UNTUK PELANGGAN ==========
    public function katalog()
    {
        $products = Product::with('category')->where('stok', '>', 0)->latest()->get();
        $categories = Category::all();
        return view('pelanggan.katalog', compact('products', 'categories'));
    }

    public function daftarProduk()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('pelanggan.daftarproduk', compact('products', 'categories'));
    }

    public function detail($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pelanggan.detailproduk', compact('product'));
    }

    // ========== UNTUK API ==========
    public function getProducts()
    {
        $products = Product::where('stok', '>', 0)->get();
        return response()->json($products);
    }

    public function getProductsJson()
    {
        $products = Product::with('category')->get();
        return response()->json($products);
    }

    // ========== UNTUK OWNER (MANAJEMEN PRODUK) ==========
    public function ownerIndex()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('owner.manajemenproduk', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('owner.produk-create', compact('categories'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk'  => 'required|string|max:255',
            'brand'        => 'nullable|string|max:100',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'ukuran'       => 'nullable|string',
            'warna'        => 'nullable|string',
            'gender'       => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_discount'  => 'nullable',
        ]);

        // Kecualikan field gambar, _token, _method dari data agar tidak konflik
        $data = $request->except(['gambar', '_token', '_method']);

        // Pakai input() bukan has() karena field selalu ada di FormData
        $data['is_discount'] = $request->input('is_discount') == '1' ? 1 : 0;

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'product' => $product
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('owner.produk-edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nama_produk'  => 'required|string|max:255',
            'brand'        => 'nullable|string|max:100',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'ukuran'       => 'nullable|string',
            'warna'        => 'nullable|string',
            'gender'       => 'nullable|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_discount'  => 'nullable',
        ]);

        // Kecualikan field gambar, _token, _method dari data agar tidak konflik
        $data = $request->except(['gambar', '_token', '_method']);

        // Pakai input() bukan has() karena field selalu ada di FormData
        $data['is_discount'] = $request->input('is_discount') == '1' ? 1 : 0;

        // Upload gambar baru jika ada, hapus gambar lama
        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diupdate',
            'product' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}