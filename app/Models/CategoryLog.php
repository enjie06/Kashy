<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryLog extends Model
{
    protected $fillable = ['activity', 'category_name', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}