<?php

namespace App\Http\Controllers\Competition;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catalog\Product;
use App\Models\Competition\Product as CompanyProduct;
use App\Models\Competition\ProductUrl;

class ProductUrlController extends Controller
{
    public function updateStatus($id, Request $request){
        $response = [];
        $productUrl = ProductUrl::findOrFail($id);
        $productUrl->status = $request->input('status');
        $productUrl->save();
        return response()->json($response);
    }

    public function massUpdateStatus($id, Request $request){
        $response = [];
        ProductUrl::where('product_company_id', '=', $id)->update(['status' => $request->input('status', 'pending')]);
        return response()->json($response);
    }

    public function accept($id, Request $request){
        $response = [];
        $productUrl = ProductUrl::findOrFail($id);
        $response['url'] = $productUrl->url;
        $response['companyProduct'] = $productUrl->product->id;
        $companyProduct = CompanyProduct::findOrFail($productUrl->product->id);
        $companyProduct->url = $productUrl->url;
        $companyProduct->save();
        ProductUrl::where('url','=', $productUrl->url)->delete();
        ProductUrl::where('product_company_id','=', $productUrl->product->id)->delete();

        return response()->json($response);
    }
}
