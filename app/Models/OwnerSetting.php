<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerSetting extends Model
{
    protected $fillable = [
        'tunai',
        'qris',
        'debit',
        'nama_toko',
        'alamat_toko',
        'printer',
        'ukuran_kertas',
    ];
}
