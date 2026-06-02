<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerLaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode   = $request->get('periode', 'harian');
        $startDate = $request->get('start');
        $endDate   = $request->get('end');

        // Tentukan range tanggal
        if ($startDate && $endDate) {
            $periode = 'custom';
            $from    = Carbon::parse($startDate)->startOfDay();
            $to      = Carbon::parse($endDate)->endOfDay();
        } elseif ($periode === 'bulanan') {
            $from = Carbon::now()->startOfYear();
            $to   = Carbon::now()->endOfDay();
        } elseif ($periode === 'tahunan') {
            $from = Carbon::now()->subYears(5)->startOfYear();
            $to   = Carbon::now()->endOfDay();
        } else {
            // harian default: 7 hari terakhir
            $periode = 'harian';
            $from    = Carbon::now()->subDays(6)->startOfDay();
            $to      = Carbon::now()->endOfDay();
        }

        // ── STAT CARDS ──────────────────────────────────────────────────────
        $pendapatanTotal = Transaction::whereBetween('created_at', [$from, $to])
            ->sum('grand_total');

        $transaksiTotal = Transaction::whereBetween('created_at', [$from, $to])
            ->count();

        $produkTerjual = TransactionDetail::whereHas('transaction', function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        })->sum('qty');

        // ── GRAFIK ──────────────────────────────────────────────────────────
        [$labels, $pendapatanChart] = $this->buildChartData($periode, $from, $to);

        // ── METODE PEMBAYARAN ───────────────────────────────────────────────
        $metodeCounts = Transaction::whereBetween('created_at', [$from, $to])
            ->select('metode_pembayaran', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('metode_pembayaran')
            ->get();

        $totalTrx = $metodeCounts->sum('jumlah') ?: 1;

        $metode = $metodeCounts->map(function ($m) use ($totalTrx) {
            return [
                'name'  => match ($m->metode_pembayaran) {
                    'cash'     => 'Tunai',
                    'qris'     => 'QRIS',
                    'transfer' => 'Transfer',
                    default    => ucfirst($m->metode_pembayaran),
                },
                'pct'   => round(($m->jumlah / $totalTrx) * 100),
                'total' => (int) $m->total,
            ];
        })->values();

        // Placeholder jika belum ada transaksi
        if ($metode->isEmpty()) {
            $metode = collect([
                ['name' => 'QRIS',     'pct' => 0, 'total' => 0],
                ['name' => 'Tunai',    'pct' => 0, 'total' => 0],
                ['name' => 'Transfer', 'pct' => 0, 'total' => 0],
            ]);
        }

        return view('owner.laporankeuangan', compact(
            'periode',
            'pendapatanTotal',
            'transaksiTotal',
            'produkTerjual',
            'labels',
            'pendapatanChart',
            'metode',
            'startDate',
            'endDate',
        ));
    }

    // ── EXPORT CSV ────────────────────────────────────────────────────────────
    public function exportCSV(Request $request)
    {
        $periode   = $request->get('periode', 'harian');
        $startDate = $request->get('start');
        $endDate   = $request->get('end');

        if ($startDate && $endDate) {
            $from = Carbon::parse($startDate)->startOfDay();
            $to   = Carbon::parse($endDate)->endOfDay();
        } elseif ($periode === 'bulanan') {
            $from = Carbon::now()->startOfYear();
            $to   = Carbon::now()->endOfDay();
        } elseif ($periode === 'tahunan') {
            $from = Carbon::now()->subYears(5)->startOfYear();
            $to   = Carbon::now()->endOfDay();
        } else {
            $from = Carbon::now()->subDays(6)->startOfDay();
            $to   = Carbon::now()->endOfDay();
        }

        $transactions = Transaction::with(['details', 'kasir'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-keuangan-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Invoice', 'Tanggal', 'Kasir', 'Pelanggan', 'Total', 'Diskon', 'Grand Total', 'Metode']);
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->invoice_number,
                    $t->created_at->format('d/m/Y H:i'),
                    optional($t->kasir)->name ?? '-',
                    $t->customer_name ?? '-',
                    $t->total,
                    $t->diskon,
                    $t->grand_total,
                    match ($t->metode_pembayaran) {
                        'cash'     => 'Tunai',
                        'qris'     => 'QRIS',
                        'transfer' => 'Transfer',
                        default    => $t->metode_pembayaran,
                    },
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── HELPER CHART ─────────────────────────────────────────────────────────
    private function buildChartData(string $periode, Carbon $from, Carbon $to): array
    {
        $labels = [];
        $data   = [];

        if ($periode === 'harian' || $periode === 'custom') {
            $current = $from->copy()->startOfDay();
            while ($current->lte($to)) {
                $labels[] = $current->format('d/m');
                $data[]   = (int) Transaction::whereDate('created_at', $current->toDateString())
                    ->sum('grand_total');
                $current->addDay();
            }
        } elseif ($periode === 'bulanan') {
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::create(null, $m)->translatedFormat('M');
                $data[]   = (int) Transaction::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $m)
                    ->sum('grand_total');
            }
        } elseif ($periode === 'tahunan') {
            for ($i = 5; $i >= 0; $i--) {
                $year     = now()->year - $i;
                $labels[] = (string) $year;
                $data[]   = (int) Transaction::whereYear('created_at', $year)->sum('grand_total');
            }
        }

        return [$labels, $data];
    }
}