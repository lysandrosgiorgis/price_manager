<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Competition\Product as CompanyProduct;
use App\Models\Competition\Company as Company;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $total_products_count         = Product::count();
        $lowest_product_count         = Product::where('has_lowest_price',1)->count();
        $highest_product_count        = Product::where('has_highest_price',1)->count();
        $latest_update_product_count  = Product::whereDate('updated_at','=',date('Y-m-d'))->count();
        $competitor_product_count     = CompanyProduct::where('url','!=',NULL)->distinct('product_id')->count();
        $no_competitor_products_count = $total_products_count-$competitor_product_count;

        $lowest_products              = Product::leftJoin('products_companies', 'products.id', '=', 'products_companies.product_id')
            ->select([
                'products.id',
                'products.name',
                'products.image',
                'products.final_price as price',
                'products.updated_at',
                'products_companies.company_id',
            ])
            ->selectRaw('(SELECT final_price FROM competitor_product_prices cpp WHERE cpp.product_id = products.id ORDER BY cpp.date DESC, cpp.final_price ASC LIMIT 0,1) as competitor_price')
            ->where('products.has_lowest_price',1)->limit(5)->get();

        $highest_products              = Product::leftJoin('products_companies', 'products.id', '=', 'products_companies.product_id')
            ->select([
                'products.id',
                'products.name',
                'products.image',
                'products.final_price as price',
                'products.updated_at',
                'products_companies.company_id',
            ])
            ->selectRaw('(SELECT final_price FROM competitor_product_prices cpp WHERE cpp.product_id = products.id ORDER BY cpp.date DESC, cpp.final_price ASC LIMIT 0,1) as competitor_price')
            ->where('products.has_highest_price',1)->limit(5)->get();

        $no_competitor_products              = Product::leftJoin('products_companies', 'products.id', '=', 'products_companies.product_id')
            ->select([
                'products.id',
                'products.name',
                'products.image',
                'products.final_price as price',
                'products.updated_at',
                'products_companies.company_id',
            ])
            ->where('products_companies.url','=',NULL)->limit(5)->get();

        $latest_update_products              = Product::leftJoin('products_companies', 'products.id', '=', 'products_companies.product_id')
            ->select([
                'products.id',
                'products.name',
                'products.image',
                'products.final_price as price',
                'products.updated_at',
                'products_companies.company_id',
            ])
            ->selectRaw('(SELECT final_price FROM competitor_product_prices cpp WHERE cpp.product_id = products.id ORDER BY cpp.date DESC, cpp.final_price ASC LIMIT 0,1) as competitor_price')
            ->whereDate('products.updated_at','=',date('Y-m-d'))->limit(5)->get();

        $data['lowest_products'] = [];
        foreach($lowest_products as $product) {
            $company = Company::where('id','=',$product->company_id)->first();
            $data['lowest_products'][$product->id] = [
                'image'            => ($product->image) ? $product->image : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
                'link'             => 'catalog/product/info/'.$product->id,
                'name'             => $product->name,
                'competitors'      => $company ? $company->name : '',
                'update_date'      => date('Y-m-d',strtotime($product->updated_at)),
                'price'            => number_format($product->price,2,',','.').'€',
                'competitor_price' => number_format($product->competitor_price,2,',','.').'€'
            ];
        }

        $data['highest_products'] = [];
        foreach($highest_products as $product) {
            $company = Company::where('id','=',$product->company_id)->first();
            $data['highest_products'][$product->id] = [
                'image'            => ($product->image) ? $product->image : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
                'link'             => 'catalog/product/info/'.$product->id,
                'name'             => $product->name,
                'competitors'      => $company ? $company->name : '',
                'update_date'      => date('Y-m-d',strtotime($product->updated_at)),
                'price'            => number_format($product->price,2,',','.').'€',
                'competitor_price' => number_format($product->competitor_price,2,',','.').'€'
            ];
        }

        $data['no_competitor_products'] = [];
        foreach($no_competitor_products as $product) {
            $company = Company::findOrFail($product->company_id);
            $data['no_competitor_products'][$product->id] = [
                'image'            => ($product->image) ? $product->image : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
                'link'             => 'catalog/product/info/'.$product->id,
                'name'             => $product->name,
                'competitors'      => $company ? $company->name : '',
                'update_date'      => date('Y-m-d',strtotime($product->updated_at)),
                'price'            => number_format($product->price,2,',','.').'€',
                'competitor_price' => ''
            ];
        }

        $data['latest_update_products'] = [];
        foreach($latest_update_products as $product) {
            $company = Company::findOrFail($product->company_id);
            $data['latest_update_products'][$product->id] = [
                'image'            => ($product->image) ? $product->image : 'https://place-hold.it/200?fbclid=IwAR2x7A8JE71lW1uDy5G-Q2J23DKTPetr8p-4S-64Hwl3tDtPb5eWg19Y2n0',
                'link'             => 'catalog/product/info/'.$product->id,
                'name'             => $product->name,
                'competitors'      => $company ? $company->name : '',
                'update_date'      => date('Y-m-d',strtotime($product->updated_at)),
                'price'            => ($product->price > $product->competitor_price) ? '<td class="minus-price fw-bold">'.number_format($product->price,2,',','.').'€</td>' : '<td class="plus-price fw-bold">'.number_format($product->price,2,',','.').'€</td>',
                'competitor_price' => ($product->competitor_price > $product->price) ? '<td class="minus-price fw-bold">'.number_format($product->competitor_price,2,',','.').'€</td>' : '<td class="plus-price fw-bold">'.number_format($product->competitor_price,2,',','.').'€</td>',
            ];
        }

        $data['total_products_count']              = number_format($total_products_count,0,',','.');
        $data['no_competitor_products_count']      = number_format($no_competitor_products_count,0,',','.');
        $data['no_competitor_products_percentage'] = number_format(($no_competitor_products_count/$total_products_count)*100,0,',','.').'%';
        $data['lowest_product_percentage']         = number_format(($lowest_product_count/$total_products_count)*100,2,',','.').'%';
        $data['highest_product_percentage']        = number_format(($highest_product_count/$total_products_count)*100,2,',','.').'%';
        $data['latest_update_product_percentage']  = number_format(($latest_update_product_count/$total_products_count)*100,2,',','.').'%';
        $data['lowest_product_count']              = number_format($lowest_product_count,0,',','.');
        $data['highest_product_count']             = number_format($highest_product_count,0,',','.');
        $data['latest_update_product_count']       = number_format($latest_update_product_count,0,',','.');
        $data['lowest_link']                       = 'catalog/product?has_lowest_price=1';
        $data['highest_link']                      = 'catalog/product?has_highest_price=1';
        $data['competitor_link']                   = 'catalog/product?has_competitor=0';
        $data['latest_update_link']                = 'catalog/product?latest_update=1';
        return view('home', $data);
    }
}
