<?php

namespace App\Models\Competition;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog\Category as CatalogCategory;
use App\Models\Competition\Company;

class Category extends Model
{
    use HasFactory;
    protected $table = 'company_categories';

    public function scopeFilter($query, $params)
    {

        if (isset($params['name']) && trim($params['name'] !== '')) {
            $query->where('name', 'LIKE', '%'.trim($params['name']).'%');
        }

        return $query;
    }

    public function catalogCategory()
    {
        return $this->belongsTo(CatalogCategory::class, 'category_id', 'id', 'categories');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id', 'companies');
    }
}
