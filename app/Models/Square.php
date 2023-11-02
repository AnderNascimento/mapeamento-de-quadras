<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $user_id
 * @property string name
 * @property int    total_units
 * @property array  polygon
 * @property array  starting_point
 */
class Square extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'total_units',
        'polygon',
        'starting_point',
    ];

    protected $casts = [
        'starting_point' => 'array',
        'polygon' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
