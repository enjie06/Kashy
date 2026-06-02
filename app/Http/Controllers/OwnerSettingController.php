<?php

namespace App\Http\Controllers;

use App\Models\OwnerSetting;
use Illuminate\Http\Request;

class OwnerSettingController extends Controller
{
    // public function index()
    // {
    //     $setting = OwnerSetting::firstOrCreate([]);

    //     return view('owner.pengaturan-tambahan', compact('setting'));
    // }

    public function index()
    {
        $setting = \App\Models\OwnerSetting::firstOrCreate([]);

        return view('owner.pengaturantransaksi', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = OwnerSetting::firstOrCreate([]);

        $setting->update([
            'tunai' => $request->has('tunai'),
            'qris' => $request->has('qris'),
            'debit' => $request->has('debit'),
            'nama_toko' => $request->nama_toko,
            'alamat_toko' => $request->alamat_toko,
            'printer' => $request->printer,
            'ukuran_kertas' => $request->ukuran_kertas ?? '80mm',
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan');
    }
}
