<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with('product')->latest()->get();
        $products = Product::all();
        return view('owner.manajemendiskon', compact('discounts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'tipe_diskon' => 'required|in:persen,fixed',
            'nilai_diskon' => 'required|numeric|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('discounts', 'public');
            $data['gambar'] = $path;
        }

        $discount = Discount::create($data);
        Product::where('id', $request->product_id)->update(['is_discount' => 1]);

        return redirect()->route('manajemen.diskon')->with('success', 'Diskon berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $request->validate([
            'tipe_diskon' => 'required|in:persen,fixed',
            'nilai_diskon' => 'required|numeric|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            if ($discount->gambar) {
                Storage::disk('public')->delete($discount->gambar);
            }
            $path = $request->file('gambar')->store('discounts', 'public');
            $data['gambar'] = $path;
        }

        $discount->update($data);

        return redirect()->route('manajemen.diskon')->with('success', 'Diskon berhasil diupdate');
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $productId = $discount->product_id;
        
        if ($discount->gambar) {
            Storage::disk('public')->delete($discount->gambar);
        }
        
        $discount->delete();

        $remaining = Discount::where('product_id', $productId)->count();
        if ($remaining == 0) {
            Product::where('id', $productId)->update(['is_discount' => 0]);
        }

        return redirect()->route('manajemen.diskon')->with('success', 'Diskon berhasil dihapus');
    }
}