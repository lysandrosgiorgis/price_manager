<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competition\Product;
use App\Models\Competition\Company;

class ProductPrice extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'company_product_prices';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id', 'company_products');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id', 'companies');
    }
}
