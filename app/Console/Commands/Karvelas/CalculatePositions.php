<?php

namespace App\Console\Commands\Karvelas;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductPrice;
use App\Models\Competition\ProductPrice as CompanyProductPrice;
use Illuminate\Console\Command;

class CalculatePositions extends Command{

    protected $signature = 'karvelas:calculate-positions';
    protected $description = 'Update Karvelas products';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $date = date('Y-m-d');
//        $date = '2024-04-17';
        $products = Product::all();
        foreach ($products as $product){
            if($product->companyProducts()->count() == 0) continue;
            $prices = [];
            $price = $product->getPrice($date);
            if($price) {
                $prices[] = [
                    'price' => $product->getPrice($date)->final_price,
                    'type' => 'product',
                    'id' => $product->id
                ];
            }
            foreach($product->companyProducts as $companyProduct){
                $price = $companyProduct->getPrice($date);
                if(!$price) continue;
                $prices[] = [
                    'price' => $price->final_price ,
                    'type' => 'companyProduct',
                    'id' => $companyProduct->id
                ];
            }

            // order prices array by price value
            usort($prices, function($a, $b) {
                return $a['price'] <=> $b['price'];
            });

            print_r($prices);

            foreach($prices as $index => $price){
                if($price['type'] == 'product'){
                    $product->last_position = $product->position;
                    $product->position = $index + 1;
                    if($product->position == 1){
                        $product->has_lowest_price = 1;
                    }else{
                        $product->has_lowest_price = 0;
                    }
                    if($product->position == count($prices)){
                        $product->has_highest_price = 1;
                    }else{
                        $product->has_highest_price = 0;
                    }
                    $lastPrice = ProductPrice::where('product_id', '=', $product->id)->whereDate('date', '<', $date)->orderBy('date', 'desc')->first();
                    if(!$lastPrice || ($lastPrice->final_price != $price['price'])){
                        $product->last_price_change = $date .' 00:00:10';
                    }
                    $product->save();
                } else {
                    $companyProduct = $product->companyProducts()->where('id', $price['id'])->first();
                    $companyProduct->last_position = $companyProduct->position;
                    $companyProduct->position = $index + 1;
                    if($companyProduct->position == 1){
                        $companyProduct->has_lowest_price = 1;
                    }else{
                        $companyProduct->has_lowest_price = 0;
                    }
                    if($companyProduct->position == count($prices)){
                        $companyProduct->has_highest_price = 1;
                    }else{
                        $companyProduct->has_highest_price = 0;
                    }

                    $lastPrice = CompanyProductPrice::where('product_id', '=', $companyProduct->id)->whereDate('date', '<', $date)->orderBy('date', 'desc')->first();
                    if(!$lastPrice || ($lastPrice->final_price != $price['price'])){
                        $companyProduct->last_price_change = $date .' 00:00:10';
                    }
                    $companyProduct->save();
                }
            }

        }
    }
}
