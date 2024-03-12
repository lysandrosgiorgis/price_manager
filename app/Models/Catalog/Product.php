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
        if (isset($params['name']) && trim($params['name'] !== '')) {
            $query->where('name', 'LIKE', '%'.trim($params['name']).'%');
        }

        if (isset($params['barcode']) && trim($params['barcode'] !== '')) {
            $query->where('barcode', '=', trim($params['barcode']));
        }

        if (isset($params['has_lowest_price']) && trim($params['has_lowest_price'] !== '')) {
            $query->where('has_lowest_price', '=', trim($params['has_lowest_price']));
        }

        if (isset($params['has_highest_price']) && trim($params['has_highest_price'] !== '')) {
            $query->where('has_highest_price', '=', trim($params['has_highest_price']));
        }

        if (isset($params['position']) && trim($params['position'] !== '')) {
            $query->where('position', '=', trim($params['position']));
        }

        return $query;
    }

    public function companyProducts(){
        return $this->hasMany(CompanyProduct::class);
    }
}
