<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competition\Product as CompanyProduct;
class Product extends Model
{
    use HasFactory;

    public function scopeFilter($query, $params)
    {
        if (isset($params['has_competitor']) && trim($params['has_competitor'] !== '')) {
            $query->leftJoin('products_companies', 'products.id', '=', 'products_companies.product_id');
            if($params['has_competitor'] == 1) {
                $query->where('products_companies.url','!=',NULL);
            } else {
                $query->where('products_companies.url','=',NULL);
            }
        }

        if (isset($params['name']) && trim($params['name'] !== '')) {
            $query->where('products.name', 'LIKE', '%'.trim($params['name']).'%');
        }

        if (isset($params['barcode']) && trim($params['barcode'] !== '')) {
            $query->where('products.barcode', '=', trim($params['barcode']));
        }

        if (isset($params['latest_update']) && trim($params['latest_update'] !== '')) {
            $query->whereDate('products.updated_at', '=', date('Y-m-d'));
        }

        if (isset($params['has_lowest_price']) && trim($params['has_lowest_price'] !== '')) {
            $query->where('products.has_lowest_price', '=', trim($params['has_lowest_price']));
        }

        if (isset($params['has_highest_price']) && trim($params['has_highest_price'] !== '')) {
            $query->where('products.has_highest_price', '=', trim($params['has_highest_price']));
        }

        if (isset($params['position']) && trim($params['position'] !== '')) {
            $query->where('products.position', '=', trim($params['position']));
        }

        return $query;
    }

    public function companyProducts(){
        return $this->hasMany(CompanyProduct::class);
    }
}
