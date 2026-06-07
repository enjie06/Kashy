<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'nama_promosi',
        'semua_produk',
        'tipe_diskon',
        'nilai_diskon',
        'tanggal_mulai',
        'tanggal_selesai',
        'gambar'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'semua_produk' => 'boolean',
    ];

    // Relasi many-to-many dengan Category
    public function categories()
{
    return $this->belongsToMany(Category::class, 'discount_categories', 'discount_id', 'category_id');
}


    // Di file app/Models/Discount.php
public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}
}