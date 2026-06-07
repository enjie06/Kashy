<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        $discounts = Discount::with('categories')->latest()->get();
        
        $discountsArray = $discounts->map(function($d) {
            return [
                'id'              => $d->id,
                'nama_promosi'    => $d->nama_promosi ?? '',
                'semua_produk'    => (bool) $d->semua_produk,
                'category_ids'    => $d->categories->pluck('id')->toArray(),
                'category_names'  => $d->categories->pluck('nama_kategori')->toArray(),
                'tipe_diskon'     => $d->tipe_diskon,
                'nilai_diskon'    => $d->nilai_diskon,
                'tanggal_mulai'   => $d->tanggal_mulai ? $d->tanggal_mulai->format('Y-m-d') : '',
                'tanggal_selesai' => $d->tanggal_selesai ? $d->tanggal_selesai->format('Y-m-d') : '',
                'gambar'          => $d->gambar,
                'active'          => $d->tanggal_selesai ? ($d->tanggal_selesai->gte(now()) ? true : false) : true,
            ];
        });
        
        return view('owner.manajemendiskon', compact('products', 'categories', 'discounts', 'discountsArray'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_promosi' => 'nullable|string|max:255',
                'tipe_diskon' => 'required|in:persen,fixed',
                'nilai_diskon' => 'required|numeric|min:1',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'semua_produk' => 'required|in:0,1',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            // Jika tidak semua produk, wajib pilih minimal 1 kategori
            if ($request->semua_produk == '0' && empty($request->category_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal 1 kategori atau aktifkan Semua Produk'
                ], 422);
            }

            $data = $request->only([
                'nama_promosi', 'tipe_diskon', 'nilai_diskon',
                'tanggal_mulai', 'tanggal_selesai', 'semua_produk'
            ]);

            // Upload gambar
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('images/discounts'), $filename);
                $data['gambar'] = 'images/discounts/' . $filename;
            }

            DB::beginTransaction();
            
            $discount = Discount::create($data);
            
            // Attach categories jika tidak semua produk
            if ($request->semua_produk == '0' && !empty($request->category_ids)) {
                $discount->categories()->attach($request->category_ids);
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Diskon berhasil ditambahkan',
                'discount' => $discount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Store discount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);

            $request->validate([
                'nama_promosi' => 'nullable|string|max:255',
                'tipe_diskon' => 'required|in:persen,fixed',
                'nilai_diskon' => 'required|numeric|min:1',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'semua_produk' => 'required|in:0,1',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            if ($request->semua_produk == '0' && empty($request->category_ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal 1 kategori atau aktifkan Semua Produk'
                ], 422);
            }

            $data = $request->only([
                'nama_promosi', 'tipe_diskon', 'nilai_diskon',
                'tanggal_mulai', 'tanggal_selesai', 'semua_produk'
            ]);

            if ($request->hasFile('gambar')) {
                if ($discount->gambar && file_exists(public_path($discount->gambar))) {
                    unlink(public_path($discount->gambar));
                }
                $file = $request->file('gambar');
                $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('images/discounts'), $filename);
                $data['gambar'] = 'images/discounts/' . $filename;
            }

            DB::beginTransaction();
            
            $discount->update($data);
            
            // Sync categories
            if ($request->semua_produk == '0' && !empty($request->category_ids)) {
                $discount->categories()->sync($request->category_ids);
            } else {
                $discount->categories()->detach();
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Diskon berhasil diupdate',
                'discount' => $discount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update discount error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            
            // Hapus relasi many-to-many
            $discount->categories()->detach();
            
            // Hapus file gambar
            if ($discount->gambar && file_exists(public_path($discount->gambar))) {
                unlink(public_path($discount->gambar));
            }
            
            $discount->delete();

            return response()->json([
                'success' => true,
                'message' => 'Diskon berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}