<?php

namespace App\Console\Commands\Karvelas;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductPrice;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

use App\Models\Api\Entersoft;

class UpdateProducts extends Command{

    protected $signature = 'karvelas:update-products';
    protected $description = 'Update Karvelas products';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $enterSoftApi = new EnterSoft;
        $this->line('Call get Products');
        $response = $enterSoftApi->getProducts(date('Y-m-d H:i:s', strtotime('-24 months')));
//        $response = $enterSoftApi->getProducts(date('Y-m-d H:i:s', strtotime('-1 days')));
//        echo '<pre>';
//        print_r($response);
//        echo '</pre>';
//        die();
        $this->line('Got them');

        $bar = $this->output->createProgressBar($response['Count']);
        $bar->start();
        foreach($response['Rows'] as $result){
            print_r($result);
//            $this->line(PHP_EOL.'Search for : '.$result['fCatalogueItemGID']);
            $product = Product::where('system_id', '=', $result['fCatalogueItemGID'])->first();
            if(!$product){
//                $this->warn('Product not found');
                $product = new Product;
                $product->system_id  = $result['fCatalogueItemGID'];
                $product->status = 'active';
                $product->sync = 'active';
                $product->image = 'active';
                $product->has_lowest_price = 0;
                $product->has_highest_price = 0;
            }
            $product->name = $result['Name_GR'];
            $product->name2 = $result['Skroutz_Code'];
            $this->info($product->name);
//            $product->mpn = $result['mpn'];
            $product->barcode = $result['BarCode'];
            $product->sku = $result['SKU'];
//            $product->model = $result['model'];
//            $product->model_2 = $result['model_2'];
            $product->description = $result['Description_GR'];
            $product->brand = $result['Brand'];

            $product->starting_price = $result['B2CPrice'];
            $product->final_price = $result['B2CPrice'];
            if(
                $result['Sale_Price'] &&
                (!$result['Offer_Start_Date'] || time() >= strtotime($result['Offer_Start_Date']) ) &&
                (!$result['Offer_End_Date'] || time() <=> strtotime($result['Offer_End_Date']) )
            ){
                $product->final_price = $result['Sale_Price'];
            }
            $images = explode(',', $result['Photos']);
            if(!empty(trim($images[0]))){
                $product->image = trim($images[0]);
                $ext = pathinfo($product->image, PATHINFO_EXTENSION);
                $imagePath = storage_path('app/public/photos/shares/karvelas/products/'.$result['fCatalogueItemGID'].'.'.$ext);
                if(!file_exists($imagePath)){
                    try{
                        $imageContent = file_get_contents('https://guruelectrics.gr/'.$product->image);
                        Storage::disk('public')->put('photos/shares/karvelas/products/'.$result['fCatalogueItemGID'].'.'.$ext, $imageContent);
                    }catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                }else{
                    $product->image = 'photos/shares/karvelas/products/'.$result['fCatalogueItemGID'].'.'.$ext;
                }
            }

            $product->sku = $result['SKU'];
            $product->system_last_update = date('Y-m-d H:i:s', strtotime($result['Last_Update']));
//            $this->line('before save product');
            $product->save();
//            $this->info('after save product');

            $oldProductPrice = ProductPrice::where('product_id', '=', $product->id)->orderBy('date','desc')->first();
            if(
                !$oldProductPrice ||
                date('Y-m-d',strtotime($oldProductPrice->date)) != date('Y-m-d')
            ){
                $productPrice = new ProductPrice();
                $productPrice->product_id = $product->id;
                $productPrice->price = $product->starting_price;
                $productPrice->final_price = $product->final_price;
                $productPrice->date = date('Y-m-d H:i:s');
                $productPrice->save();
            }

            $bar->advance();
        }

        $bar->finish();

        // add prices for all products
        $products = Product::all();
        foreach($products as $product){
            $oldProductPrice = ProductPrice::where('product_id', '=', $product->id)->orderBy('date','desc')->first();
            if(
                !$oldProductPrice ||
                date('Y-m-d',strtotime($oldProductPrice->date)) != date('Y-m-d')
            ){
                $productPrice = new ProductPrice();
                $productPrice->product_id = $product->id;
                $productPrice->price = $oldProductPrice->price;
                $productPrice->final_price = $oldProductPrice->final_price;
                $productPrice->date = date('Y-m-d H:i:s');
                $productPrice->save();
            }
        }
	}
}
