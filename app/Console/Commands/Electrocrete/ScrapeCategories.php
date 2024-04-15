<?php

namespace App\Console\Commands\Electrocrete;

use App\Console\Commands\ReservationDay;
use App\Console\Commands\Source;
use App\Models\Competition\Category;
use App\Models\Competition\Company;
use App\Models\Competition\Product;
use App\Models\Competition\ProductPrice;
use App\Models\Competition\ProductUrl;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

use App\Models\Scrapers\Proxy;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeCategories extends Command{

    protected $signature = 'electrocrete:scrape-categories';
    protected $description = 'Update Electrocrete category products';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $categories = Category::where([
            ['sync', '=', 1],
            ['company_id', '=', 1],
        ])->get();
        $company = Company::find(1);

        foreach($categories as $category){
            $this->line('');
            $this->line('Category: '.$category->name);
            $this->line('url: '.$category->url);
            $limitParam = $company->limit_param;
            $limitValue = $company->limit_value;
            $pageParam = $company->page_param;
            $currentPage = 1;
            $maxPage = 20;
            do{
//                $this->line('Page: '.$currentPage);
                $proxy = Proxy::where('status', '=', '1')->orderBy('lastUsed', 'asc')->first();
                if(!$proxy){
                    $this->error('No active proxy found');
                    exit();
                }
                $categoryUrl = $category->url;
                $categoryUrl.= '?'.$limitParam.'='.$limitValue;
                if($currentPage > 1){
                    $categoryUrl.= '&'.$pageParam.'='.$currentPage;
                }

                $client = new Client([]);
                try {
//                    $this->line('$categoryUrl: '.$categoryUrl);
                    $body = $client->get($categoryUrl)->getBody();
                    $proxy->lastUsed = date('Y-m-d H:i:s');
                    $proxy->save();
                } catch (\Exception $e) {
                    $proxy->status = 0;
                    $proxy->save();
                    $this->error($e->getMessage());
                    continue;
                }
                $crawler = new Crawler($body->getContents());
                $maxPageNode = $crawler->filter('.pagination li')->last()->text();
                $maxPage = intval($maxPageNode);
                if($currentPage == 1){
                    $this->output->progressStart($maxPage);
                }
                $products = $crawler->filter('.product-teaser');
                foreach($products as $product){
                    $element = new Crawler($product);
                    $name = $element->attr('data-title');
                    $sku = $element->attr('data-sku');
                    $brand = $element->attr('data-brand');
                    $mainCategory = $element->attr('data-item_category');
                    $secondCategory = $element->attr('data-item_category_2');
                    $thirdCategory = $element->attr('data-item_category_3');

                    $cartBenefitValue = 0;
                    $cartBenefit = $element->filter('.bg-gradient-benefit.font-secondary');
                    if($cartBenefit->count() > 0){
                        $cartBenefitValue = 1 * str_replace('€','',$cartBenefit->text());
                    }
                    $url = '';
                    $imageContainer = $element->filter('.image');
                    if($imageContainer->count() > 0){
                        $url = $imageContainer->attr('href');
                    }
                    $imageUrl = '';
                    $image = $element->filter('[data-main-image]');
                    if($image->count() > 0){
                        $imageUrl = $image->attr('src');
                    }
                    $finalPrice = str_replace('€','',$element->filter('.text-primary.text-small.sm\:text-h5.font-bold')->text());
                    $finalPrice = (float)str_replace(',','.',$finalPrice);
                    $startingPriceValue = $finalPrice;
                    $startingPrice = $element->filter('.text-gray.line-through.mr-5');
                    if($startingPrice->count() > 0){
                        $startingPriceValue = str_replace('€','',$startingPrice->text());
                        $startingPriceValue = (float)str_replace(',','.',$startingPriceValue);
                    }

                    $product = Product::where('url', '=', $url)->first();
                    if(!$product) {
                        $product = new Product();
                        $product->name = $name;
                        $product->company_id = 1;
                        $product->sku = $sku;
                        $product->brand = $brand;
//                        $product->main_category = $mainCategory;
//                        $product->second_category = $secondCategory;
//                        $product->third_category = $thirdCategory;
                        $product->url = $url;
                        $product->image = $imageUrl;
                        $product->save();
                    }
                    // download image from scraper
                    $ext = pathinfo($product->image, PATHINFO_EXTENSION);
                    $imagePath = storage_path('app/public/photos/shares/electrocrete/products/'.$sku.'.'.$ext);
                    if(!file_exists($imagePath)){
                        $imageContent = file_get_contents($product->image);
                        Storage::disk('public')->put('photos/shares/electrocrete/products/'.$sku.'.'.$ext, $imageContent);
                    }
                    $product->image = 'photos/shares/electrocrete/products/'.$sku.'.'.$ext;
                    $product->save();
                    $oldProductPrice = ProductPrice::where('product_id', '=', $product->id)->orderBy('date','desc')->first();
                    if(
                        !$oldProductPrice ||
                        date('Y-m-d',strtotime($oldProductPrice->date)) != date('Y-m-d')
                    ){
                        $productPrice = new ProductPrice();
                        $productPrice->company_id = 1;
                        $productPrice->product_id = $product->id;
                        $productPrice->price = $startingPriceValue;
                        $productPrice->final_price = $finalPrice - $cartBenefitValue;
                        $productPrice->date = date('Y-m-d H:i:s');
                        $productPrice->save();
                    }
                }
                $this->output->progressAdvance();
                $currentPage++;
            }while($currentPage <= $maxPage);
            $this->output->progressFinish();
        }
	}
}
