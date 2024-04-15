<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competition\Product as CompanyProduct;
use App\Models\Competition\ProductPrice as CompanyProductPrice;

class Product extends Model
{
    use HasFactory;

    public function scopeFilter($query, $params)
    {
        if (isset($params['has_competitor']) && trim($params['has_competitor'] !== '')) {
            $query->leftJoin('company_products', 'products.id', '=', 'company_products.product_id');
            if($params['has_competitor'] == 1) {
                $query->where('company_products.url','!=',NULL);
            } else {
                $query->where('company_products.url','=',NULL);
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

    public function prices(){
        return $this->hasMany(ProductPrice::class);
    }

    public function getCompetitionPriceRange(){
        $companyProducts = $this->companyProducts->pluck('id')->toArray();
        $prices = CompanyProductPrice::selectRaw('DATE(`date`) as `date`, MIN(price) as min, MAX(price) as max')->whereIn('product_id', $companyProducts)->groupBy('date')->get();

        return $prices->map(function($price){
            return [
                'date' => $price->date,
                'min' => $price->min,
                'max' => $price->max
            ];
        });
    }
}
