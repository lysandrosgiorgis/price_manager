<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Catalog\ProductController;
use App\Http\Controllers\Catalog\CategoryController;
use App\Http\Controllers\Competition\CompanyController;
use App\Http\Controllers\Competition\ProductController as CompetitionProductController;
use App\Http\Controllers\Competition\CategoryController as CompetitionCategoryController;
use App\Http\Controllers\Competition\ProductUrlController;
use App\Http\Controllers\Dnd\DebugController;
use App\Http\Controllers\Api\EnterSoftController;

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

Auth::routes(['register' => false]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::controller(ProductController::class)->group(function(){
    Route::get('/catalog/product', 'productList')->name('catalog.product');
    Route::get('/catalog/product/create', 'create')->name('catalog.product.create');
    Route::post('/catalog/product/create', 'store')->name('catalog.product.create');
    Route::get('/catalog/product/update/{id}', 'edit')->name('catalog.product.update');
    Route::post('/catalog/product/update/{id}', 'update')->name('catalog.product.update');
    Route::get('/catalog/product/info/{id}', 'info')->name('catalog.product.info');
    Route::get('/catalog/product/delete/{id}', 'delete')->name('catalog.product.delete');
    Route::post('/catalog/product/import', 'importProducts')->name('catalog.product.import');
    Route::any('/catalog/product/autocomplete', 'autocomplete')->name('catalog.product.autocomplete');
    Route::any('/catalog/product/get_matches', 'getMatchingProducts')->name('catalog.product.getMatchingProducts');
    Route::any('/catalog/product/connect_product_with_competition', 'connectCompanyProductToProduct')->name('catalog.product.connectCompanyProductToProduct');
});

Route::controller(CategoryController::class)->group(function(){
    Route::get('/catalog/category', 'categoryList')->name('catalog.category');
    Route::get('/catalog/category/create', 'create')->name('catalog.category.create');
    Route::post('/catalog/category/create', 'store')->name('catalog.category.create');
    Route::get('/catalog/category/update/{id}', 'edit')->name('catalog.category.update');
    Route::post('/catalog/category/update/{id}', 'update')->name('catalog.category.update');
    Route::get('/catalog/category/delete/{id}', 'delete')->name('catalog.category.delete');
    Route::any('/catalog/category/autocomplete', 'autocomplete')->name('catalog.category.autocomplete');
});

Route::controller(CompanyController::class)->group(function(){
    Route::get('/competition/company', 'companyList')->name('competition.company');
    Route::get('/competition/company/create', 'create')->name('competition.company.create');
    Route::post('/competition/company/create', 'store')->name('competition.company.create');
    Route::get('/competition/company/update/{id}', 'edit')->name('competition.company.update');
    Route::post('/competition/company/update/{id}', 'update')->name('competition.company.update');
    Route::get('/competition/company/delete/{id}', 'delete')->name('competition.company.delete');
    Route::any('/competition/company/autocomplete', 'autocomplete')->name('competition.company.autocomplete');
});

Route::controller(CompetitionProductController::class)->group(function(){
    Route::get('/competition/product', 'productList')->name('competition.product');
    Route::get('/competition/product/create', 'create')->name('competition.product.create');
    Route::post('/competition/product/create', 'store')->name('competition.product.create');
    Route::get('/competition/product/update/{id}', 'edit')->name('competition.product.update');
    Route::any('/competition/product/info/{id}', 'info')->name('competition.product.info');
    Route::post('/competition/product/update/{id}', 'update')->name('competition.product.update');
    Route::get('/competition/product/delete/{id}', 'delete')->name('competition.product.delete');
});

Route::controller(CompetitionCategoryController::class)->group(function(){
    Route::get('/competition/category', 'categoryList')->name('competition.category');
    Route::get('/competition/category/create', 'create')->name('competition.category.create');
    Route::post('/competition/category/create', 'store')->name('competition.category.create');
    Route::get('/competition/category/update/{id}', 'edit')->name('competition.category.update');
    Route::post('/competition/category/update/{id}', 'update')->name('competition.category.update');
    Route::get('/competition/category/delete/{id}', 'delete')->name('competition.category.delete');
});

Route::controller(ProductUrlController::class)->group(function(){
    Route::get('/competition/product-url/update-status/{id}', 'updateStatus')->name('competition.producturl.updatestatus');
    Route::get('/competition/product-url/mass-update-status/{id}', 'massUpdateStatus')->name('competition.producturl.massUpdateStatus');
    Route::get('/competition/product-url/accept/{id}', 'accept')->name('competition.producturl.accept');
});

Route::controller(DebugController::class)->group(function(){
    Route::get('/dnd/debug/scrape', 'scrape')->name('scrape');
    Route::get('/dnd/debug/scrape_talos', 'scrapeTalos')->name('scrapeTalos');
    Route::get('/dnd/debug/scrape-search', 'scrapeSearch')->name('scrape.search');
});

Route::controller(EnterSoftController::class)->group(function(){
    Route::get('/api/entersoft/debug', 'debug')->name('entersoft.debug');
});
