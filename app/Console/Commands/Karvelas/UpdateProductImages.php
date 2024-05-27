<?php

namespace App\Console\Commands\Karvelas;

use App\Models\Catalog\Product;
use App\Models\Catalog\ProductPrice;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

use App\Models\Api\Entersoft;

class UpdateProductImages extends Command{

    protected $signature = 'karvelas:update-product-images';
    protected $description = 'Update Karvelas product images';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $products = Product::all();
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();
        foreach($products as $product){
            $bar->advance();
            // Check if product image has the word photos in it
            if(strpos($product->image, 'photos') !== false){
                continue;
            }
            $ext = pathinfo($product->image, PATHINFO_EXTENSION);
            $imagePath = storage_path('app/public/photos/shares/karvelas/products/'.$product->system_id.'.'.$ext);
            if(!file_exists($imagePath)){
                try{
                    $imageContent = file_get_contents('https://guruelectrics.gr/'.$product->image);
                    Storage::disk('public')->put('photos/shares/karvelas/products/'.$product->system_id.'.'.$ext, $imageContent);
                    $product->image = 'photos/shares/karvelas/products/'.$product->system_id.'.'.$ext;
                }catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
            }else{
                $product->image = 'photos/shares/karvelas/products/'.$product->system_id.'.'.$ext;
            }
            $product->save();
        }
	}
}
