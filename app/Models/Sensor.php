<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_code',
        'item_id',
        'calibration_factor',
        'last_calibrated_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'calibration_factor' => 'float',
            'last_calibrated_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * The item this sensor weighs (1-to-1). Nullable until assigned.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}