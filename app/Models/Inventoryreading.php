<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryReading extends Model
{
    use HasFactory;

    // This table only tracks when a reading was recorded, not "updated".
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'event_id',
        'weight',
        'quantity',
        'stock_status',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}