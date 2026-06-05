<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirRiwayatController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('details')
            ->where('kasir_id', Auth::id());

        // Search invoice / nama pelanggan
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        }

        // Filter tanggal
        $tanggal = $request->tanggal ?? 'hariini';

        if ($tanggal === 'hariini') {
            $query->whereDate('created_at', now()->toDateString());
        } elseif ($tanggal === 'minggu') {
            $query->whereDate('created_at', '>=', now()->subDays(7)->toDateString());
        } elseif ($tanggal === 'bulan') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        // Filter metode pembayaran
        if ($request->filled('metode') && $request->metode !== 'semua') {
            $metode = strtolower($request->metode);

            if ($metode === 'tunai') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'tunai')
                      ->orWhere('metode_pembayaran', 'cash')
                      ->orWhere('payment_method', 'tunai')
                      ->orWhere('payment_method', 'cash');
                });
            } elseif ($metode === 'qris') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'qris')
                      ->orWhere('payment_method', 'qris');
                });
            } elseif ($metode === 'debit') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'debit')
                      ->orWhere('payment_method', 'debit');
                });
            }
        }

        $transactions = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($transaction) {
                return $transaction->created_at->translatedFormat('d F Y');
            });

        return view('kasir.riwayattransaksi', compact('transactions'));
    }
}