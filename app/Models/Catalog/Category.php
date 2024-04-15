<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function scopeFilter($query, $params)
    {

        if (isset($params['name']) && trim($params['name'] !== '')) {
            $query->where('name', 'LIKE', '%'.trim($params['name']).'%');
        }

        return $query;
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }
}
