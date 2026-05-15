<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendances';
    
    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'status',
        'shift_status'
    ];
    
   protected $casts = [
    'check_in' => 'datetime',
    'check_out' => 'datetime',
];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}