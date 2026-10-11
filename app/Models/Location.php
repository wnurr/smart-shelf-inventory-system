<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'aisle',
        'bay',
        'shelf',
        'bin',
    ];

    /**
     * One location can only be assigned to one item.
     */
    public function item(): HasOne
    {
        return $this->hasOne(Item::class);
    }

    /**
     * Generate readable location name.
     * Example: A-A-1-1
     */
    public function label(): string
    {
        return "{$this->aisle}-{$this->bay}-{$this->shelf}-{$this->bin}";
    }

    /**
     * Check whether the location is already occupied.
     */
    public function isOccupied(): bool
    {
        return $this->item()->exists();
    }
}