<?php

namespace App\Console\Commands\Electrocrete;

use App\Console\Commands\ReservationDay;
use App\Console\Commands\Source;
use App\Models\Competition\Product as CompanyProduct;
use App\Models\Competition\ProductUrl;
use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

use App\Models\Scrapers\Proxy;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\DomCrawler\Crawler;

class UpdateProducts extends Command{

    protected $signature = 'electrocrete:update-products';
    protected $description = 'Update Electrocrete products';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $steps = 5000;
        $this->output->progressStart($steps);

        $companyProducts = CompanyProduct::where('searched', '=', 0)
            ->where(function (Builder $query) {
                $query->where('url','=','')
                    ->orWhereNull('url');
            })
            ->take(5000)->get();
        foreach($companyProducts as $companyProduct){
            $this->output->progressAdvance();
            $found_urls = [];
            $this->newLine();
            $this->line('name:'. $companyProduct->product->name);
            $this->line('URL count:'.$companyProduct->urls->count());
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
            $hasProxies = 1;
            $parsed = 0;
            $found = 0;
            foreach($targetUrls as $targetUrl){
                $parsed = 0;
                if($found) break;
                $this->line($targetUrl);
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
                            $this->error($e->getMessage());
                            continue;
                        }
                        $crawler = new Crawler($body->getContents());
//                    $crawler = new Crawler(Storage::disk('public')->get('electrocretesearch1'));
                        try {
                            $total = $crawler->filter('[data-findastic-total]');
                            $total->text();
                            if((int)$total->text() == 0){
                                $this->warn('EMPTY SEARCH RESULT');
                                $parsed = 1;
                                $companyProduct->searched = 1;
                                $companyProduct->save();
                                continue;
                            }
                            $siteProducts = $crawler->filter('.product-teaser .image'); // Products
                            if($total->text() == 1){
                                $this->info('FOUND ONE PRODUCT');
                                $found = 1;
                                $companyProduct->url = $siteProducts->eq(0)->attr('href');
                                $companyProduct->save();
                            }else{
                                if($siteProducts->count() > 0){
                                    $this->question('FOUND MANY PRODUCT');
                                    foreach($siteProducts as $siteProduct){
                                        $element = new Crawler($siteProduct);
                                        if(isset($found_urls[$element->attr('href')])) {
                                            $this->warn('URL IN LIST');
                                            continue;
                                        }
                                        $productUrl = new ProductUrl;
                                        $productUrl->product_company_id = $companyProduct->id;
                                        $productUrl->name = $element->attr('title');
                                        $productUrl->url = $element->attr('href');
                                        $productUrl->save();
                                        $found_urls[$element->attr('href')] = 1;
                                        $this->question($element->attr('title'));
                                        $this->question($element->attr('href'));
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            $this->error($e->getMessage());
                        }
                        //*/
                        $parsed = 1;
                        $companyProduct->searched = 1;
                        $companyProduct->save();
                    }else{
                        $hasProxies = 0;
                    }
                }
            }
        }

        $this->output->progressFinish();
	}
}
