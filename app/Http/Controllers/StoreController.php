<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Ambil data toko (dipakai saat halaman load).
     */
    public function show()
    {
        $store = DB::table('store_settings')->first();

        return response()->json($store);
    }

    /**
     * Simpan / update data toko.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'jalan'     => 'nullable|string|max:255',
            'kota'      => 'nullable|string|max:100',
            'kode_pos'  => 'nullable|string|max:10',
            'negara'    => 'nullable|string|max:100',
        ]);

        $store = DB::table('store_settings')->first();

        if ($store) {
            DB::table('store_settings')
                ->where('id', $store->id)
                ->update(array_merge($validated, ['updated_at' => now()]));
        } else {
            DB::table('store_settings')->insert(
                array_merge($validated, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil toko berhasil disimpan.',
        ]);
    }
}
