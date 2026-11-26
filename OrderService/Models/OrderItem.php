<?php

namespace OrderService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $connection = 'order_sqlite';

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'fruit_id',
        'quantity_kg',
        'unit_price',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'fruit_id' => 'integer',
        'quantity_kg' => 'float',
        'unit_price' => 'float',
    ];

    /**
     * Đơn hàng chứa sản phẩm này
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
