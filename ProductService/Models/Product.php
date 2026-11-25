<?php

namespace ProductService\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'product_sqlite';
    protected $table = 'fruits';

   protected $fillable = [
    'name',
    'price_per_kg',
    'origin',
    'description',
    'image_url', // <-- Thêm dòng này
];
}