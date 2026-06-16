<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $data = $request->except(['gambar', '_token', '_method']);
        $data['is_discount'] = $request->input('is_discount') == '1' ? 1 : 0;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $product = Product::create($data);

        // ===== CATAT LOG =====
        ProductActivityLog::create([
            'user_id'     => Auth::id(),
            'product_id'  => $product->id,
            'aksi'        => 'Tambah Produk',
            'nama_produk' => $product->nama_produk,
            'stok'        => $product->stok,
        ]);

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

        $data = $request->except(['gambar', '_token', '_method']);
        $data['is_discount'] = $request->input('is_discount') == '1' ? 1 : 0;

        if ($request->hasFile('gambar')) {
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $path = $request->file('gambar')->store('products', 'public');
            $data['gambar'] = $path;
        }

        $product->update($data);

        // ===== CATAT LOG =====
        ProductActivityLog::create([
            'user_id'     => Auth::id(),
            'product_id'  => $product->id,
            'aksi'        => 'Edit Produk',
            'nama_produk' => $product->nama_produk,
            'stok'        => $product->stok,
        ]);

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

        // ===== CATAT LOG SEBELUM DIHAPUS =====
        ProductActivityLog::create([
            'user_id'     => Auth::id(),
            'product_id'  => null, // produk akan dihapus
            'aksi'        => 'Hapus Produk',
            'nama_produk' => $product->nama_produk,
            'stok'        => $product->stok,
        ]);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }

    // ========== API LOG AKTIVITAS PRODUK ==========
    public function activityLogs()
    {
        $logs = ProductActivityLog::with('user')
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($log) {
                return [
                    'aksi'        => $log->aksi,
                    'nama_produk' => $log->nama_produk,
                    'stok'        => $log->stok,
                    'oleh'        => $log->user?->name ?? 'Owner',
                    'waktu'       => $log->created_at->format('H:i'),
                    'tanggal'     => $log->created_at->format('d M Y'),
                ];
            });

        return response()->json($logs);
    }
}