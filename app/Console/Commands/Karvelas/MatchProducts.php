<?php

namespace App\Console\Commands\Karvelas;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductPrice;
use App\Models\Competition\Product as CompanyProduct;
use Illuminate\Console\Command;
use App\Models\Api\Entersoft;

class MatchProducts extends Command{

    protected $signature = 'karvelas:match-products';
    protected $description = 'Match Karvelas products';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {// get company products mathing with the products of the other companies
        $products = Product::all();
        foreach($products as $product){
//            if($product->id != 1242) continue;
            $this->line($product->name);
            $sql = "`product_id` is NULL AND (";
            $clauses = [];
            $explodedName = explode(' ', str_replace('\'','\\\'',$product->name));
            foreach ($explodedName as $key => $name){
                if(empty(trim($name))) unset($explodedName[$key]);
            }
            $clauses[] = "`model` IN ('".implode("', '",$explodedName)."') ";
            $clauses[] = "`mpn` IN ('".implode("', '",$explodedName)."') ";
            $clauses[] = "`sku` IN ('".implode("', '",$explodedName)."') ";
            $clauses[] = "`barcode` IN ('".implode("', '",$explodedName)."') ";
            if(!empty(trim($product->mpn))) {
                $clauses[] = "`model` = '".$product->mpn."' ";
                $clauses[] = "`mpn` = '".$product->mpn."' ";
                $clauses[] = "`sku` = '".$product->mpn."' ";
                $clauses[] = "`barcode` = '".$product->mpn."' ";
            }
            if(!empty(trim($product->barcode))) {
                $clauses[] =  "`model` = '".$product->barcode."' ";
                $clauses[] =  "`mpn` = '".$product->barcode."' ";
                $clauses[] =  "`sku` = '".$product->barcode."' ";
                $clauses[] =  "`barcode` = '".$product->barcode."' ";
            }
            if(!empty(trim($product->sku))) {
                $clauses[] = "`model` = '".$product->sku."' ";
                $clauses[] = "`mpn` = '".$product->sku."' ";
                $clauses[] = "`sku` = '".$product->sku."' ";
                $clauses[] = "`barcode` = '".$product->sku."' ";
            }
            $sql.= implode('OR ', $clauses);
            $sql.= ")";
            $companyProducts = CompanyProduct::whereRaw($sql)->get();
            $this->info($sql);
            if($companyProducts->count() > 0){
                $this->info($sql);
                $this->info('Matching products:');
                foreach($companyProducts as $companyProduct){
                    $this->line($companyProduct->id.': '.$companyProduct->name);
                }
                break;
            }

            $companyProducts = CompanyProduct::whereNull('product_id')->get();
            if(0){
                $this->info('similar_text');
                $matching = [];
                foreach ($companyProducts as $companyProduct) {
                    $similar = similar_text(mb_strtolower($product->name), mb_strtolower($companyProduct->name));
                    if($similar > 30){
                        $matching[] = [
                            'id' => $companyProduct->id,
                            'name' => mb_strtolower($product->name),
                            'name2' => mb_strtolower($companyProduct->name),
                            'similar' => $similar
                        ];
                    }
                }
                usort($matching, function($a, $b) {
                    return $a['similar'] <= $b['similar'];
                });
                foreach($matching as $index => $match){
                    $this->line($index.': '.$match['name'].' - '.$match['name2'].' - '.$match['similar']);
                    if($index > 10){
                        break;
                    }
                }
            }
            if(0){
                $this->info('levenshtein');
                $matching = [];
                foreach ($companyProducts as $companyProduct) {
                    $similar = levenshtein(mb_strtolower($product->name), mb_strtolower($companyProduct->name));
                    if($similar < 10){
                        $matching[] = [
                            'id' => $companyProduct->id,
                            'name' => mb_strtolower($product->name),
                            'name2' => mb_strtolower($companyProduct->name),
                            'similar' => $similar
                        ];
                    }
                }
                usort($matching, function($a, $b) {
                    return $a['similar'] >= $b['similar'];
                });
                foreach($matching as $index => $match){
                    $this->line($index.': '.$match['name'].' - '.$match['name2'].' - '.$match['similar']);
                    if($index > 10){
                        break;
                    }
                }
            }
            if(0){
                $this->info('similar_text skroutz');
                $companyProducts = CompanyProduct::whereNull('product_id')->get();
                $matching = [];
                foreach ($companyProducts as $companyProduct) {
                    $similar = similar_text(mb_strtolower($product->name2), mb_strtolower($companyProduct->name));
                    if($similar > 30) {
                        $matching[] = [
                            'id' => $companyProduct->id,
                            'name' => mb_strtolower($product->name2),
                            'name2' => mb_strtolower($companyProduct->name),
                            'similar' => $similar
                        ];
                    }
                }
                usort($matching, function($a, $b) {
                    return $a['similar'] <= $b['similar'];
                });

                foreach($matching as $index => $match){
                    $this->line($index.': '.$match['name'].' - '.$match['name2'].' - '.$match['similar']);
                    if($index > 10){
                        break;
                    }
                }
            }
            $this->info('levenshtein skroutz');
            $matching = [];
            foreach ($companyProducts as $companyProduct) {
                $similar = levenshtein(mb_strtolower($product->name2), mb_strtolower($companyProduct->name));
                if($similar < 10) {
                    $matching[] = [
                        'id' => $companyProduct->id,
                        'name' => mb_strtolower($product->name2),
                        'name2' => mb_strtolower($companyProduct->name),
                        'similar' => $similar
                    ];
                }
            }
            usort($matching, function($a, $b) {
                return $a['similar'] >= $b['similar'];
            });
            foreach($matching as $index => $match){
                $this->line($index.': '.$match['name'].' - '.$match['name2'].' - '.$match['similar']);
                if($index > 10){
                    break;
                }
            }
            sleep(1);
        }
    }
}
