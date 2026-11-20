<?php

namespace OrderService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'order_sqlite';

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'total_amount' => 'float',
    ];

    /**
     * Danh sách các mặt hàng trong đơn
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
