<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\Catalog\ProductCategoryController;
use App\Http\Controllers\Competition\CompanyController;
use App\Http\Controllers\Competition\ProductUrlController;
use App\Http\Controllers\Dnd\DebugController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::controller(ProductController::class)->group(function(){
    Route::get('/catalog/product', 'productList')->name('catalog.product');
    Route::get('/catalog/product/create', 'create')->name('catalog.product.create');
    Route::post('/catalog/product/create', 'store')->name('catalog.product.create');
    Route::get('/catalog/product/update/{id}', 'edit')->name('catalog.product.update');
    Route::post('/catalog/product/update/{id}', 'update')->name('catalog.product.update');
    Route::get('/catalog/product/info/{id}', 'info')->name('catalog.product.info');
    Route::get('/catalog/product/delete/{id}', 'delete')->name('catalog.product.delete');
    Route::post('/catalog/product/import', 'importProducts')->name('catalog.product.import');
});

Route::controller(ProductCategoryController::class)->group(function(){
    Route::get('/catalog/category', 'productCategoryList')->name('catalog.category');
});

Route::controller(CompanyController::class)->group(function(){
    Route::get('/competition/company', 'companyList')->name('competition.company');
    Route::get('/competition/company/create', 'create')->name('competition.company.create');
    Route::post('/competition/company/create', 'store')->name('competition.company.create');
    Route::get('/competition/company/update/{id}', 'edit')->name('competition.company.update');
    Route::post('/competition/company/update/{id}', 'update')->name('competition.company.update');
    Route::get('/competition/company/delete/{id}', 'delete')->name('competition.company.delete');
});

Route::controller(App\Http\Controllers\Competition\ProductController::class)->group(function(){
    Route::get('/competition/product', 'companyProductList')->name('competition.product');
    Route::get('/competition/product/update/{id}', 'edit')->name('competition.product.update');
    Route::post('/competition/product/update/{id}', 'update')->name('competition.product.update');
    Route::get('/competition/product/delete/{id}', 'delete')->name('competition.product.delete');
    Route::get('/competition/product/delete/{id}', 'delete')->name('competition.product.delete');
});

Route::controller(ProductUrlController::class)->group(function(){
    Route::get('/competition/product-url/update-status/{id}', 'updateStatus')->name('competition.producturl.updatestatus');
    Route::get('/competition/product-url/mass-update-status/{id}', 'massUpdateStatus')->name('competition.producturl.massUpdateStatus');
    Route::get('/competition/product-url/accept/{id}', 'accept')->name('competition.producturl.accept');
});

Route::controller(DebugController::class)->group(function(){
    Route::get('/dnd/debug/scrape', 'scrape')->name('scrape');
    Route::get('/dnd/debug/scrape-search', 'scrapeSearch')->name('scrape.search');
});
