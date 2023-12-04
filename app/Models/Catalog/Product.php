<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Competition\Product as CompanyProduct;
class Product extends Model
{
    use HasFactory;

    public function companyProducts(){
        return $this->hasMany(CompanyProduct::class);
    }
}
