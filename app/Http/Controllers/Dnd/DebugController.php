<?php

namespace App\Http\Controllers\Dnd;

use App\Http\Controllers\Controller;
use App\Models\Scrapers\Proxy;
use App\Models\catalog\Product;
use App\Models\Competition\Product as CompanyProduct;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Illuminate\Support\Facades\Storage;

class DebugController extends Controller
{
    public function scrape(){

        # make request to
        $targetUrl = 'https://menfashion.gr';
        $targetUrl = 'https://www.menfashion.gr/νεες-αφιξεις/vice-andrika-rouxa-papoutsia-mauro-088-00112-black_';
//        $targetUrl = 'https://www.electrocrete.gr/en/search?query=LQ63006LA';
        $targetUrl = 'https://www.electrocrete.gr/en/tv-lg-led-32lq63006la-32-smart-fhd';
//        $targetUrl = 'https://www.electrocrete.gr/en/keyboard-razer-gaming-huntsman-mini-opto-mechanical-us';

        # Proxy
        $hasProxies = 1;
        $parsed = 0;
        while($hasProxies && !$parsed){
            $proxy = Proxy::where('status', '=', '1')->orderBy('lastUsed', 'asc')->first();
            if($proxy !== null){
                $client = new Client([
//                    RequestOptions::PROXY => $proxy->port,
//                    RequestOptions::VERIFY => false, # disable SSL certificate validation
//                    RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
                ]);

                try {
                    $body = $client->get($targetUrl)->getBody();
                } catch (\Exception $e) {
                    $proxy->status = 0;
                    $proxy->save();
                    echo $e->getMessage().'<br />';
                    continue;
                }
                $crawler = new Crawler($body->getContents());

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
                } catch (\Exception $e) {
                    $parsed = 1;
                    echo $e->getMessage().'<br />';
                }
                $parsed = 1;
            }else{
                $hasProxies = 0;
                echo 'NO proxy remainng<br />';
            }
        }

    }

    public function scrapeSearch()
    {
        $products = Product::all();
        foreach ($products as $product){
            echo $product->name.'<br />';
            foreach ($product->companyProducts as $companyProduct){
                echo $companyProduct->id.'<br />';
                echo $companyProduct->product_id.'<br />';
                echo $companyProduct->company_id.'<br />';
            }
        }
        die();
        $products = CompanyProduct::where('url','=','')->get();
        foreach($products as $product){
            echo $product->product->name.'<br />';
        }
        die();
        # make request to
//        $targetUrl = 'https://menfashion.gr';
//        $targetUrl = 'https://www.menfashion.gr/νεες-αφιξεις/vice-andrika-rouxa-papoutsia-mauro-088-00112-black_';
        $targetUrl = 'https://www.electrocrete.gr/en/search?query=LQ63006LA';
        $targetUrl = 'https://www.electrocrete.gr/en/search?query=keyboards';
//        $targetUrl = 'https://www.electrocrete.gr/en/tv-lg-led-32lq63006la-32-smart-fhd';
//        $targetUrl = 'https://www.electrocrete.gr/en/keyboard-razer-gaming-huntsman-mini-opto-mechanical-us';

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
}
