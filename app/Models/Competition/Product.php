<?php

namespace App\Models\Competition;

use App\Models\Competition\ProductUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog\Product as CatalogProduct;
use App\Models\Competition\Company;
use App\Models\Competition\ProductPrice;

class Product extends Model
{
    use HasFactory;
    protected $table = 'company_products';


    public function scopeFilter($query, $params)
    {

        if (isset($params['name']) && trim($params['name'] !== '')) {
            $query->where('name', 'LIKE', '%'.trim($params['name']).'%');
        }

        if (isset($params['barcode']) && trim($params['barcode'] !== '')) {
            $query->where('barcode', '=', trim($params['barcode']));
        }

        if (isset($params['latest_update']) && trim($params['latest_update'] !== '')) {
            $query->whereDate('updated_at', '=', date('Y-m-d'));
        }

        if (isset($params['position']) && trim($params['position'] !== '')) {
            $query->where('position', '=', trim($params['position']));
        }

        return $query;
    }

    public function product(){
        return $this->belongsTo(CatalogProduct::class, 'product_id', 'id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function urls(){
        return $this->hasMany(ProductUrl::class, 'product_company_id', 'id');
    }

    public function prices(){
        return $this->hasMany(ProductPrice::class, 'product_id', 'id', 'company_products');
    }

    public function latestPrice()
    {
        $prices = $this->prices()->orderBy('date', 'desc')->first();
        return $prices;
    }
}
