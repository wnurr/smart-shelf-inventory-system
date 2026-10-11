<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\Searchable;

class Item extends Model
{
use Searchable;    


    protected $fillable = [
        'item_name',
        'item_code',
        'date_added',
        'min_stock',
        'location_id',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'date',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * The single load cell weighing this item (1-to-1).
     */
    public function sensor(): HasOne
    {
        return $this->hasOne(Sensor::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function inventoryReadings(): HasMany
    {
        return $this->hasMany(InventoryReading::class);
    }

    public function calibrations(): HasMany
    {
        return $this->hasMany(Calibration::class);
    }

    public function calibrationCommands(): HasMany
    {
        return $this->hasMany(CalibrationCommand::class);
    }

    /**
     * The most recent weight/quantity reading for this item, if any.
     */
    public function latestReading(): HasOne
    {
        return $this->hasOne(InventoryReading::class)->latestOfMany('recorded_at');
    }

    /**
     * The most recent calibration for this item, if any.
     */
    public function latestCalibration(): HasOne
    {
        return $this->hasOne(Calibration::class)->latestOfMany('calibrated_at');
    }

public function currentQuantity(): ?int
{
    return $this->latestReading
        ? (int) $this->latestReading->quantity
        : null;
}

public function stockStatus(): string
{
    $quantity = $this->currentQuantity();

    if ($quantity === null) {
        return 'no_data';
    }

    if ($quantity === 0) {
        return 'out_of_stock';
    }

    if ($quantity <= (int) $this->min_stock) {
        return 'low_stock';
    }

    return 'in_stock';
}

public function isLowStock(): bool
{
    return in_array(
        $this->stockStatus(),
        ['low_stock', 'out_of_stock'],
        true
    );
}
}