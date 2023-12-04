<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog\Product as CatalogProduct;
use App\Models\Competition\Company;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products_companies';


    public function product(){
        return $this->belongsTo(CatalogProduct::class, 'product_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
