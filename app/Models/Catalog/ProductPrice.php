<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog\Product;

class ProductPrice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'product_prices';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id', 'company_products');
    }
}
