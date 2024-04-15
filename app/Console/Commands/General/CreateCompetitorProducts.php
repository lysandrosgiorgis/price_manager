<?php

namespace App\Console\Commands\General;

use App\Models\Competition\Product as CompanyProduct;
use App\Models\Catalog\Product;
use Illuminate\Console\Command;

class CreateCompetitorProducts extends Command{

    protected $signature = 'general:create-competitor-products';
    protected $description = 'Populate competitor products_companies table with products from specific competitor';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $competitorId = $this->ask('Competitor ID: ');

        $products = Product::all();
        $this->output->progressStart($products->count());
        foreach($products as $product){
            $companyProduct = new CompanyProduct();
            $companyProduct->product_id = $product->id;
            $companyProduct->company_id = $competitorId;
            $companyProduct->save();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
	}
}
