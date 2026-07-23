<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'product_code',
        'unit_weight',
        'min_stock',
        'sensor_id',
    ];
}
