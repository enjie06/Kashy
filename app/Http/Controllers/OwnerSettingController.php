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

    public function scanPrinter()
    {
        $printers = [
            [
                'name' => 'Epson TM-T82X',
                'type' => 'Bluetooth',
                'status' => 'Tersedia'
            ],
            [
                'name' => 'Rongta RP326',
                'type' => 'USB',
                'status' => 'Tersedia'
            ],
            [
                'name' => 'POS-58 Printer',
                'type' => 'Jaringan',
                'status' => 'Tersedia'
            ]
        ];

        return response()->json([
            'success' => true,
            'message' => 'Pemindaian printer berhasil',
            'printers' => $printers
        ]);
    }

    public function testPrint(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Tes print berhasil dikirim ke '.$request->printer
        ]);
    }

    public function nonaktifPrinter()
    {
        return response()->json([
            'success' => true,
            'message' => 'Printer berhasil dinonaktifkan'
        ]);
    }
    }
