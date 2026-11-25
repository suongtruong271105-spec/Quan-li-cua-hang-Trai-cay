<?php

namespace InventoryService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryBatch extends Model
{
    use HasFactory;

    protected $connection = 'inventory_sqlite';

    protected $table = 'inventory_batches';

    protected $fillable = [
        'fruit_id',
        'quantity_kg',
        'import_date',
        'expiry_date',
    ];

    protected $casts = [
        'fruit_id'    => 'integer',
        'quantity_kg' => 'float',
        'import_date' => 'date:Y-m-d',
        'expiry_date' => 'date:Y-m-d',
    ];
}
