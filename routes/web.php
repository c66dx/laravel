<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;

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

//Route::get('/', function () {
    //return view('welcome');
//});
Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/delete-cart',[CartController::class,'deleteItem'])->name('front.deleteItem.cart');
Route::post('/checkout',[CartController::class,'checkout'])->name('front.checkout');


Route::get('/register',[AuthController::class, 'register'])->name('account.register');
Route::post('/process-register',[AuthController::class, 'processRegister'])->name('account.processRegister');

Route::get('/login',[AuthController::class, 'login'])->name('account.login');


Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => 'admin.guest'], function(){

        Route::get('/login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    
    });
    
    Route::group(['middleware' => 'admin.auth'],function(){

        Route::get('/dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout');

        // Category Routes
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.delete');
        
        // Sub category routes 
        Route::get('/sub-categories',[SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}',[SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

        // Brands Routes
        Route::get('/brands',[BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands',[BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}',[BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}',[BrandController::class, 'destroy'])->name('brands.delete');

        // Product Routes
        Route::get('/products',[ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/products',[ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.delete');
        Route::get('/get-products',[ProductController::class,'getProducts'])->name('products.getProducts');


        Route::get('/product-subcategories',[ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');

        Route::post('/product-images/update',[ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images',[ProductImageController::class, 'destroy'])->name('product-images.destroy');


        // temp-images.create
        Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');
        
        
        Route::get('/getSlug',function(Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
    });
});