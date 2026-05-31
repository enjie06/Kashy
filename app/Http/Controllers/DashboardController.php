<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total pendapatan dari semua transaksi
        $totalPendapatan = DB::table('transactions')->sum('grand_total');

        // Total pesanan (jumlah transaksi)
        $totalPesanan = DB::table('transactions')->count();

        // Diskon aktif (tanggal_selesai >= hari ini atau null)
        $diskonAktif = DB::table('discounts')
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now()->toDateString());
            })
            ->where(function($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now()->toDateString());
            })
            ->count();

        return view('owner.dashboard', compact(
            'totalPendapatan',
            'totalPesanan',
            'diskonAktif'
        ));
    }
}