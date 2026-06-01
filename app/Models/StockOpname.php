<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = [
        'tanggal',
        'nama_produk',
        'kategori',
        'harga',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'status',
        'catatan',
    ];
}