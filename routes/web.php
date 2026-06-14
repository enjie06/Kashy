<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\KasirShiftController;
use App\Http\Controllers\KasirTransactionController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\OwnerSettingController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OwnerLaporanController;
use App\Http\Controllers\KasirRiwayatController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| LANDING
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


/*
|--------------------------------------------------------------------------
| OWNER
|--------------------------------------------------------------------------
*/

// Manajemen Diskon
    Route::get('/owner/manajemendiskon', [DiscountController::class, 'index'])
    ->name('manajemen.diskon');

    Route::post('/owner/diskon', [DiscountController::class, 'store'])
    ->name('diskon.store');

    Route::get('/owner/diskon/{id}/edit', [DiscountController::class, 'edit'])
    ->name('diskon.edit');

    Route::put('/owner/diskon/{id}', [DiscountController::class, 'update'])
    ->name('diskon.update');
    
    Route::delete('/owner/diskon/{id}', [DiscountController::class, 'destroy'])
    ->name('diskon.destroy');

Route::middleware(['auth'])->group(function () {

    Route::get("/owner/dashboard", [DashboardController::class, "index"])
        ->name("owner.dashboard");

    Route::get('/owner/manajemendiskon', 'App\Http\Controllers\DiscountController@index')
        ->middleware('auth')
        ->name('manajemen.diskon');

    // ── LAPORAN KEUANGAN ──
    Route::get('/owner/laporan-keuangan', [OwnerLaporanController::class, 'index'])
        ->name('owner.laporan.keuangan');

    Route::get('/owner/laporan-keuangan/export', [OwnerLaporanController::class, 'exportCSV'])
        ->name('owner.laporan.export');
    // ──────────────────────────────────────────────────────────────

    Route::get('/owner/stokopname', [StockOpnameController::class, 'index'])
        ->middleware('auth')
        ->name('stokopname');

    Route::post('/owner/stokopname', [StockOpnameController::class, 'store'])
        ->middleware('auth')
        ->name('stok-opname.store');

    Route::delete('/owner/stokopname/{stockOpname}', [StockOpnameController::class, 'destroy'])
        ->middleware('auth')
        ->name('stok-opname.destroy');

    Route::view('/owner/manajemenstaff', 'owner.manajemenstaff')
        ->middleware('auth')
        ->name('manajemen.staff');

    Route::get('/owner/manajemenproduk', [ProductController::class, 'ownerIndex'])
        ->middleware('auth')
        ->name('manajemen.produk');

    Route::post('/owner/produk', [ProductController::class, 'store'])
        ->name('produk.store');
    Route::put('/owner/produk/{product}', [ProductController::class, 'update'])
        ->name('produk.update');
    Route::delete('/owner/produk/{product}', [ProductController::class, 'destroy'])
        ->name('produk.destroy');

    Route::middleware(['auth'])->group(function () {
        Route::get('/owner/pengaturantransaksi', [OwnerSettingController::class, 'index'])
            ->name('pengaturan.transaksi');

        Route::post('/owner/pengaturantransaksi', [OwnerSettingController::class, 'update'])
            ->name('owner.pengaturan-tambahan.update');
    });

    Route::view('/owner/kustomisasitemplatstruk', 'owner.kustomisasitemplatstruk')
        ->name('kustomisasi.template.struk');

    Route::view('/owner/konfigurasi-printer', 'owner.konfigurasiprinter')
        ->name('konfigurasi.printer');

    Route::view('/owner/pusat-keamanan-data', 'owner.pusatkeamanandata')
        ->name('pusat.keamanan.data');

    Route::delete('/owner/products/{product}', [ProductController::class, 'destroy'])
        ->name('owner.products.destroy');

    // Route::get('/owner/manajemenkategori', [CategoryController::class, 'index'])
    //     ->middleware('auth')
    //     ->name('manajemen.kategori');

    Route::view('/owner/manajementoko', 'owner.manajementoko')
        ->middleware('auth')
        ->name('manajemen.toko');

    Route::middleware(['auth'])->group(function () {
        Route::get('/owner/pengaturan-tambahan', [OwnerSettingController::class, 'index'])
            ->name('owner.pengaturan-tambahan');
    });

    Route::prefix('owner')->middleware(['auth'])->group(function () {
    Route::post('/printer/scan', [OwnerSettingController::class, 'scanPrinter'])
        ->name('owner.printer.scan');

    Route::post('/printer/test-print', [OwnerSettingController::class, 'testPrint'])
        ->name('owner.printer.testPrint');

    Route::post('/printer/nonaktif', [OwnerSettingController::class, 'nonaktifPrinter'])
        ->name('owner.printer.nonaktif');
    });

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN STAFF
    |--------------------------------------------------------------------------
    */

    Route::get('/owner/staff', [StaffController::class, 'index'])
        ->name('owner.staff.index');

    Route::post('/owner/staff', [StaffController::class, 'store'])
        ->name('owner.staff.store');

    Route::patch('/owner/staff/{user}/toggle', [StaffController::class, 'toggleStatus'])
        ->name('owner.staff.toggle');

    Route::delete('/owner/staff/{user}', [StaffController::class, 'destroy'])
        ->name('owner.staff.destroy');

    /*
    |--------------------------------------------------------------------------
    | MANAJEMEN TOKO
    |--------------------------------------------------------------------------
    */

    Route::get('/owner/store-settings', [StoreController::class, 'show'])
        ->name('owner.store.show');

    Route::post('/owner/store-settings', [StoreController::class, 'update'])
        ->name('owner.store.update');

    /*
    |--------------------------------------------------------------------------
    | PROFILE OWNER
    |--------------------------------------------------------------------------
    */

    Route::get('/owner/profile', [ProfileController::class, 'ownerProfile'])
        ->middleware('auth')
        ->name('owner.profile');


    // API untuk kategori
    Route::get('/owner/manajemenkategori', [CategoryController::class, 'index'])
        ->middleware('auth')
        ->name('manajemen.kategori');

    Route::post('/owner/kategori', [CategoryController::class, 'store'])
        ->middleware('auth')
        ->name('kategori.store');

    Route::put('/owner/kategori/{id}', [CategoryController::class, 'update'])
        ->middleware('auth')
        ->name('kategori.update');

    Route::delete('/owner/kategori/{id}', [CategoryController::class, 'destroy'])
        ->middleware('auth')
        ->name('kategori.destroy');  
    // API untuk diskon
    Route::post('/owner/diskon', [DiscountController::class, 'store'])->name('diskon.store');
    Route::put('/owner/diskon/{id}', [DiscountController::class, 'update'])->name('diskon.update');
    Route::delete('/owner/diskon/{id}', [DiscountController::class, 'destroy'])->name('diskon.destroy');
});


/*
|--------------------------------------------------------------------------
| KASIR
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/kasir/dashboard', function () {
        return view('kasir.dashboard');
    })->name('dashboard-kasir');

    Route::get('/kasir/absensikasir', function () {
        return view('kasir.absensikasir');
    })->name('kasir.absensikasir');

    Route::get('/kasir/shiftkasir', function () {
        return view('kasir.shiftkasir');
    })->name('kasir.shiftkasir');

    Route::get('/kasir/historyabsensi', function () {
        return view('kasir.historyabsensikasir');
    })->name('kasir.historyabsensi');

    Route::get('/kasir/profil', [ProfileController::class, 'kasirProfile'])
        ->name('kasir.profil');

    Route::put('/kasir/profil/update', [ProfileController::class, 'updateProfile'])
        ->name('kasir.profile.update');

    Route::put('/kasir/profil/password', [ProfileController::class, 'updatePassword'])
        ->name('kasir.password.update');

    Route::get('/kasir/riwayattransaksi', [KasirRiwayatController::class, 'index'])
    ->name('kasir.riwayattransaksi');

    Route::get('/kasir/laporantransaksi', function () {
        return view('kasir.laporantransaksi');
    })->name('kasir.laporantransaksi');

    Route::get('/kasir/transaksi', [KasirTransactionController::class, 'create'])
        ->name('kasir.transaksi');

    Route::get('/kasir/pembayaran', [KasirTransactionController::class, 'pembayaran'])
        ->name('kasir.pembayaran');

    Route::post('/kasir/finalize-payment', [KasirTransactionController::class, 'finalizePayment'])
        ->name('kasir.finalize-payment');

    Route::post('/kasir/member/store', [KasirTransactionController::class, 'storeMember'])
        ->name('kasir.member.store');

    Route::post('/kasir/transaksi/session', [KasirTransactionController::class, 'saveTransactionSession'])
        ->name('kasir.transaksi.session');
   

    // ── Absensi / Shift Handle ──
Route::post('/shift/handle', [App\Http\Controllers\ShiftController::class, 'handleAbsensi'])->name('shift.handle');
Route::get('/shift/status', [App\Http\Controllers\ShiftController::class, 'cekStatusAbsensi'])->name('shift.status');
Route::get('/shift/full-history', [App\Http\Controllers\ShiftController::class, 'getFullHistory'])->name('shift.full-history');

    // ── Shift Kas (buka/tutup) ──
    Route::get('/kasir/shift/status',    [KasirShiftController::class, 'cekStatus'])   ->name('kasir.shift.status');
    Route::get('/kasir/shift/min-saldo', [KasirShiftController::class, 'getMinSaldo']) ->name('kasir.shift.minSaldo');
    Route::post('/kasir/shift/buka',     [KasirShiftController::class, 'bukaShift'])   ->name('kasir.shift.buka');
    Route::post('/kasir/shift/tutup',    [KasirShiftController::class, 'tutupShift'])  ->name('kasir.shift.tutup');

    // ── Recent transactions ──
    Route::get('/kasir/transaksi/recent', function () {
        $user         = Auth::user();
        $transactions = App\Models\Transaction::with('details')
            ->where('kasir_id', $user->id)
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($trx) {
                return [
                    'invoice_number'    => $trx->invoice_number,
                    'grand_total'       => $trx->grand_total,
                    'metode_pembayaran' => $trx->metode_pembayaran == 'cash' ? 'Tunai' : ($trx->metode_pembayaran == 'qris' ? 'QRIS' : 'Transfer'),
                    'time'              => $trx->created_at->format('H:i'),
                    'total_items'       => $trx->details->sum('qty'),
                ];
            });
        return response()->json($transactions);
    })->name('kasir.transaksi.recent');
    Route::get('/kasir/pusatbantuan', function () {
    return view('kasir.pusatbantuankasir');
})->name('kasir.pusatbantuan');

    // ── Laporan kasir ──
    Route::get('/kasir/laporan/hari-ini',   [App\Http\Controllers\LaporanController::class, 'getLaporanHariIni'])->name('kasir.laporan.hari-ini');
    Route::get('/kasir/laporan/export-pdf', [App\Http\Controllers\LaporanController::class, 'exportPDF'])        ->name('kasir.laporan.export-pdf');

});


/*
|--------------------------------------------------------------------------
| KARYAWAN
|--------------------------------------------------------------------------
*/

Route::get('/karyawan/dashboard', function () {
    return view('karyawan.dashboard');
})->middleware('auth')->name('dashboard-karyawan');

Route::get('/karyawan/absensi', function () {
    return view('karyawan.absensi');
})->middleware('auth')->name('absensi');

Route::get('/karyawan/historyabsensi', function () {
    return view('karyawan.historyabsensi');
})->middleware('auth')->name('historyabsensi');

Route::middleware(['auth'])->group(function () {

    Route::get('/karyawan/stok-produk', [ProductController::class, 'index'])
        ->name('stok-produk');

    Route::get('/karyawan/pusatbantuan', function () {
        return view('karyawan.pusatbantuan');
    })->name('pusatbantuan');

    /*
    |--------------------------------------------------------------------------
    | PROFILE KARYAWAN
    |--------------------------------------------------------------------------
    */

    Route::get('/karyawan/profile', [ProfileController::class, 'index'])
        ->name('karyawan.profile');

    Route::put('/karyawan/profile/update', [ProfileController::class, 'updateProfile'])
        ->name('karyawan.profile.update');

    Route::put('/karyawan/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('karyawan.password.update');

    /*
    |--------------------------------------------------------------------------
    | PROFILE UNIVERSAL UPDATE
    |--------------------------------------------------------------------------
    */

    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])
        ->name('profile.updateProfile');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');

    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.updatePhoto');

    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])
        ->name('profile.deletePhoto');

});


/*
|--------------------------------------------------------------------------
| SHIFT / ABSENSI
| (1 definisi per route — tidak ada duplikat)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Absensi masuk/pulang (karyawan & kasir pakai method yang sama,
    // controller bedakan berdasarkan $user->role)
    Route::post('/shift/handle', [ShiftController::class, 'handleAbsensi'])
        ->name('shift.handle');

    // Cek status absensi hari ini
    Route::get('/shift/status', [ShiftController::class, 'cekStatus'])
        ->name('shift.status');

    // Riwayat singkat (untuk widget di halaman absensi)
    Route::get('/shift/recent-history', [ShiftController::class, 'getRecentHistory'])
        ->name('shift.recent-history');

    // Riwayat lengkap + stats (untuk halaman history)
    Route::get('/shift/full-history', [ShiftController::class, 'getFullHistory'])
        ->name('shift.full-history');

});


/*
|--------------------------------------------------------------------------
| SERVER TIME
|--------------------------------------------------------------------------
*/

Route::get('/server-time', function () {
    return response()->json([
        'server_time' => now()->toIso8601String(),
        'timezone'    => config('app.timezone'),
    ]);
})->name('server.time');

Route::get('/test-time', function () {
    return [
        'server_time'    => now()->format('Y-m-d H:i:s'),
        'timezone'       => config('app.timezone'),
        'php_timezone'   => date_default_timezone_get(),
    ];
});


/*
|--------------------------------------------------------------------------
| PELANGGAN
|--------------------------------------------------------------------------
*/

Route::get('/api/products-all', [ProductController::class, 'getProductsJson'])
    ->name('api.products.all');

Route::get('/katalog', [ProductController::class, 'katalog'])
    ->name('katalog');

Route::get('/daftarproduk', [ProductController::class, 'daftarProduk'])
->name('daftar-produk');

Route::get('/detail-produk/{id}', [ProductController::class, 'detail'])
    ->name('detail-produk');


/*
|--------------------------------------------------------------------------
| DASHBOARD DEFAULT
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| PROFILE BAWAAN BREEZE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


require __DIR__.'/auth.php';