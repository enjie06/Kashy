<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Member;
use Illuminate\Http\Request;

class KasirTransactionController extends Controller
{
    public function create()
    {
        $products = Product::select('id', 'nama_produk', 'harga', 'gambar', 'stok')->get();

        $members = Member::select('id', 'nama', 'no_hp')->get();

        return view('kasir.transaksi', compact('products', 'members'));
    }

    public function storeMember(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20|unique:members,no_hp',
        ]);

        $member = Member::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
        ]);

        return response()->json([
            'success' => true,
            'member' => $member
        ]);
    }

    public function pembayaran()
    {
        $transaction = session('kasir_transaction');

        if (!$transaction) {
            return redirect()
                ->route('kasir.transaksi')
                ->with('error', 'Keranjang masih kosong.');
        }

        return view('kasir.pembayaran', compact('transaction'));
    }

    public function saveTransactionSession(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'customer_name' => 'required|string',
            'member_id' => 'nullable',
            'discount_percent' => 'nullable|numeric',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        session([
            'kasir_transaction' => $request->all()
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('kasir.pembayaran')
        ]);
    }
}