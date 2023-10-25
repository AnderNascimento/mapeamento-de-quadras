<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Square extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'total_units',
        'polygon',
        'starting_point'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
