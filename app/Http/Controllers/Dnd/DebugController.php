<?php

namespace App\Http\Controllers\Dnd;

use App\Http\Controllers\Controller;
use App\Models\Competition\ProductUrl;
use App\Models\Scrapers\Proxy;
use App\Models\catalog\Product;
use App\Models\Competition\Product as CompanyProduct;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder;

class DebugController extends Controller
{
//    public function scrape(){
//
//        # make request to
//        $targetUrl = 'https://menfashion.gr';
//        $targetUrl = 'https://www.menfashion.gr/νεες-αφιξεις/vice-andrika-rouxa-papoutsia-mauro-088-00112-black_';
////        $targetUrl = 'https://www.electrocrete.gr/en/search?query=LQ63006LA';
//        $targetUrl = 'https://www.electrocrete.gr/en/tv-lg-led-32lq63006la-32-smart-fhd';
////        $targetUrl = 'https://www.electrocrete.gr/en/keyboard-razer-gaming-huntsman-mini-opto-mechanical-us';
//
//        # Proxy
//        $hasProxies = 1;
//        $parsed = 0;
//        while($hasProxies && !$parsed){
//            $proxy = Proxy::where('status', '=', '1')->orderBy('lastUsed', 'asc')->first();
//            if($proxy !== null){
//                $client = new Client([
////                    RequestOptions::PROXY => $proxy->port,
////                    RequestOptions::VERIFY => false, # disable SSL certificate validation
////                    RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
//                ]);
//
//                try {
//                    $body = $client->get($targetUrl)->getBody();
//                } catch (\Exception $e) {
//                    $proxy->status = 0;
//                    $proxy->save();
//                    echo $e->getMessage().'<br />';
//                    continue;
//                }
//                $crawler = new Crawler($body->getContents());
//
//                try {
//                    $name = $crawler->filter('#basic-features h1')->text(); // Name
//                    $description = $crawler->filter('#basic-features .mt-15.text-small.text-gray-dark.leading-25')->text(); // Description
//                    if($crawler->filter('.product-scroll button .line-through')->count() > 0){
//                        $finalPrice = $crawler->filter('.product-scroll button .mt-2.text-h4')->text(); // Final Price
//                        $initialPrice = $crawler->filter('.product-scroll button .line-through')->text(); // Starting Price
//                    }else{
//                        $finalPrice = $crawler->filter('.product-scroll button .mt-2.text-h4')->text(); // Final Price
//                        $initialPrice = $finalPrice; // Starting Price
//                    }
//                } catch (\Exception $e) {
//                    $parsed = 1;
//                    echo $e->getMessage().'<br />';
//                }
//                $parsed = 1;
//            }else{
//                $hasProxies = 0;
//                echo 'NO proxy remainng<br />';
//            }
//        }
//
//    }

    public function scrape()
    {
        $companyProducts = CompanyProduct::where('url','<>','')->get();
        foreach($companyProducts as $companyProduct){

            echo $targetUrl = $companyProduct->url;

            $hasProxies = 1;
            $parsed = 0;
            while($hasProxies && !$parsed){
                $proxy = Proxy::where('status', '=', '1')->orderBy('lastUsed', 'asc')->first();
                if($proxy !== null){
                    $client = new Client([]);
                    try {
                        $body = $client->get($targetUrl)->getBody();
                        $proxy->lastUsed = date('Y-m-d H:i:s');
                        $proxy->save();
                    } catch (\Exception $e) {
                        $proxy->status = 0;
                        $proxy->save();
                        echo $e->getMessage().'<br />';
                        continue;
                    }
                    $crawler = new Crawler($body->getContents());
//                    $crawler = new Crawler(Storage::disk('public')->get('electrocretesearch1'));
                    try {
                        try {
                            $name = $crawler->filter('#basic-features h1')->text(); // Name
                            $description = $crawler->filter('#basic-features .mt-15.text-small.text-gray-dark.leading-25')->text(); // Description
                            if($crawler->filter('.product-scroll button .line-through')->count() > 0){
                                $finalPrice = $crawler->filter('.product-scroll button .mt-2.text-h4')->text(); // Final Price
                                $initialPrice = $crawler->filter('.product-scroll button .line-through')->text(); // Starting Price
                            }else{
                                $finalPrice = $crawler->filter('.product-scroll button .mt-2.text-h4')->text(); // Final Price
                                $initialPrice = $finalPrice; // Starting Price
                            }
                            echo $name.'<br />';
                            echo $finalPrice.'<br />';
                            echo $initialPrice.'<br />';
                        } catch (\Exception $e) {
                            $parsed = 1;
                            echo $e->getMessage().'<br />';
                        }
                    } catch (\Exception $e) {
                        echo $e->getMessage().'<br />';
                    }
                    //*/
                    $parsed = 1;
                }else{
                    $hasProxies = 0;
                }
            }
        }
        die();
        # make request to
//        $targetUrl = 'https://menfashion.gr';
//        $targetUrl = 'https://www.menfashion.gr/νεες-αφιξεις/vice-andrika-rouxa-papoutsia-mauro-088-00112-black_';
//        $targetUrl = 'https://www.electrocrete.gr/en/search?query=LQ63006LA';
//        $targetUrl = 'https://www.electrocrete.gr/en/search?query=keyboards';
//        $targetUrl = 'https://www.electrocrete.gr/en/tv-lg-led-32lq63006la-32-smart-fhd';
//        $targetUrl = 'https://www.electrocrete.gr/en/keyboard-razer-gaming-huntsman-mini-opto-mechanical-us';
//        $targetUrl = 'https://www.electrocrete.gr/en/search?query=keyboards';

        # Proxy
        $hasProxies = 1;
        $parsed = 0;
        while($hasProxies && !$parsed){
            $proxy = Proxy::where('status', '=', '1')->orderBy('lastUsed', 'asc')->first();
            if($proxy !== null){
//                $client = new Client([]);
//                try {
//                    $body = $client->get($targetUrl)->getBody();
//                    Storage::disk('public')->put('electrocretesearch1', $body->getContents());
//                } catch (\Exception $e) {
//                    $proxy->status = 0;
//                    $proxy->save();
//                    echo $e->getMessage().'<br />';
//                    continue;
//                }
//                $crawler = new Crawler($body->getContents());
                //*/
                $crawler = new Crawler(Storage::disk('public')->get('electrocretesearch1'));
                try {
                    $total = $crawler->filter('[data-findastic-total]');
                    $total->text();
                    if((int)$total->text() == 0){
                        $parsed = 1;
                        continue;
                    }
                    $products = $crawler->filter('.product-teaser .image'); // Products
                    if($total == 1){
                        $main_url = $products->eq(0)->attr('href');
                        echo $main_url.'<br />';
                    }else{
                        if($products->count() > 0){
                            $products->each(function (Crawler $node, $i) {
                                echo $node->attr('href').'<br />';
                            });
                        }
                    }
                } catch (\Exception $e) {
                    $parsed = 1;
                    echo $e->getMessage().'<br />';
                }
                //*/
                $parsed = 1;
            }else{
                $hasProxies = 0;
                echo 'NO proxy remainng<br />';
            }
        }

    }

    public function scrapeSearch()
    {
        $companyProducts = CompanyProduct::where('searched', '=', 0)
                            ->where(function (Builder $query) {
                                $query->where('url','=','')
                                    ->orWhereNull('url');
                            })
                            ->take(1)->get();
        foreach($companyProducts as $companyProduct){
            $found_urls = [];
            echo 'name:'. $companyProduct->product->name.'<br />';
            echo 'URL count:'.$companyProduct->urls->count().'<br />';
            if($companyProduct->urls->count() > 0) continue;
            $targetUrls = [];
            if(!empty(trim($companyProduct->product->model))){
                $targetUrl = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->model;
                $targetUrls[$companyProduct->product->model] = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->model;
                $targetUrls[str_replace(' ','',$companyProduct->product->model)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(' ','',$companyProduct->product->model);
                $targetUrls[str_replace('-','',$companyProduct->product->model)] = 'https://www.electrocrete.gr/en/search?query='.str_replace('-','',$companyProduct->product->model);
                $targetUrls[str_replace(['-',' '],'',$companyProduct->product->model)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(['-',' '],'',$companyProduct->product->model);
            }
            if(!empty(trim($companyProduct->product->mpn))){
                $targetUrl = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->mpn;
                $targetUrls[$companyProduct->product->mpn] = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->mpn;
                $targetUrls[str_replace(' ','',$companyProduct->product->mpn)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(' ','',$companyProduct->product->mpn);
                $targetUrls[str_replace('-','',$companyProduct->product->mpn)] = 'https://www.electrocrete.gr/en/search?query='.str_replace('-','',$companyProduct->product->mpn);
                $targetUrls[str_replace(['-',' '],'',$companyProduct->product->mpn)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(['-',' '],'',$companyProduct->product->mpn);
            }
            if(!empty(trim($companyProduct->product->barcode))){
                $targetUrl = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->barcode;
                $targetUrls[$companyProduct->product->barcode] = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->barcode;
                $targetUrls[str_replace(' ','',$companyProduct->product->barcode)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(' ','',$companyProduct->product->barcode);
                $targetUrls[str_replace('-','',$companyProduct->product->barcode)] = 'https://www.electrocrete.gr/en/search?query='.str_replace('-','',$companyProduct->product->barcode);
                $targetUrls[str_replace(['-',' '],'',$companyProduct->product->barcode)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(['-',' '],'',$companyProduct->product->barcode);
            }
            if(!empty(trim($companyProduct->product->sku))){
                $targetUrl = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->sku;
                $targetUrls[$companyProduct->product->sku] = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->sku;
                $targetUrls[str_replace(' ','',$companyProduct->product->sku)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(' ','',$companyProduct->product->sku);
                $targetUrls[str_replace('-','',$companyProduct->product->sku)] = 'https://www.electrocrete.gr/en/search?query='.str_replace('-','',$companyProduct->product->sku);
                $targetUrls[str_replace(['-',' '],'',$companyProduct->product->sku)] = 'https://www.electrocrete.gr/en/search?query='.str_replace(['-',' '],'',$companyProduct->product->sku);
            }
            if(!empty(trim($companyProduct->product->name))){
                $targetUrl = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->name;
                $targetUrls[] = 'https://www.electrocrete.gr/en/search?query='.$companyProduct->product->name;
            }
            if(empty($targetUrls)) continue;
//            echo $targetUrl.'<br />';
            $hasProxies = 1;
            $parsed = 0;
            $found = 0;
            foreach($targetUrls as $targetUrl){
                $parsed = 0;
                if($found) break;
                echo 'TargetUrl: '.$targetUrl.'<br />';
                while($hasProxies && !$parsed && !$found){
                    $proxy = Proxy::where('status', '=', '1')->orderBy('lastUsed', 'asc')->first();
                    if($proxy !== null){
                        $client = new Client([]);
                        try {
                            $body = $client->get($targetUrl)->getBody();
                            $proxy->lastUsed = date('Y-m-d H:i:s');
                            $proxy->save();
                        } catch (\Exception $e) {
                            $proxy->status = 0;
                            $proxy->save();
                            echo $e->getMessage().'<br />';
                            continue;
                        }
                        $crawler = new Crawler($body->getContents());
//                    $crawler = new Crawler(Storage::disk('public')->get('electrocretesearch1'));
                        try {
                            $total = $crawler->filter('[data-findastic-total]');
                            $total->text();
                            if((int)$total->text() == 0){
                                echo '<div style="color:darkorange">EMPTY SEARCH RESULT</div>';
                                $parsed = 1;
                                $companyProduct->searched = 1;
                                $companyProduct->save();
                                continue;
                            }
                            $siteProducts = $crawler->filter('.product-teaser .image'); // Products
                            if($total->text() == 1){
                                echo '<div style="color: darkgreen;">FOUND ONE PRODUCT</div>';
                                $found = 1;
                                $companyProduct->url = $siteProducts->eq(0)->attr('href');
                                $companyProduct->save();
                            }else{
                                if($siteProducts->count() > 0){
                                    echo '<div style="color: darkblue;">FOUND MANY PRODUCT</div>';
                                    foreach($siteProducts as $siteProduct){
                                        $element = new Crawler($siteProduct);
                                        if(isset($found_urls[$element->attr('href')])) {
                                            echo '<div style="color: darkmagenta;">URL IN LIST</div>';
                                            continue;
                                        }
                                        $productUrl = new ProductUrl;
                                        $productUrl->product_company_id = $companyProduct->id;
                                        $productUrl->name = $element->attr('title');
                                        $productUrl->url = $element->attr('href');
                                        $productUrl->save();
                                        $found_urls[$element->attr('href')] = 1;
                                        echo $element->attr('title').'<br />';
                                        echo $element->attr('href').'<br />';
                                    }
                                }
                            }
                            echo __LINE__.'<br />';
                        } catch (\Exception $e) {
                            echo $e->getMessage().'<br />';
                        }
                        //*/
                        $parsed = 1;
                        $companyProduct->searched = 1;
                        $companyProduct->save();
                    }else{
                        echo __LINE__.'<br />';
                        $hasProxies = 0;
                    }
                }
            }
        }
    }
}
