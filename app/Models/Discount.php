<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    
    protected $fillable = [
        'product_id',
        'tipe_diskon',
        'nilai_diskon',
        'tanggal_mulai',
        'tanggal_selesai',
        'gambar'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // ========== RELASI KE PRODUCT ==========
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    // ======================================
}