<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competition\Product;

class ProductUrl extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class, 'product_company_id', 'id');
    }
}
