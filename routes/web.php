<?php

use App\Events\TestBroadcast;
use App\Http\Controllers\admin\categoryController;
use App\Http\Controllers\admin\dashboardController;
use App\Http\Controllers\admin\detailProductController;
use App\Http\Controllers\admin\inStockController;
use App\Http\Controllers\admin\OrderController as adminOrderController;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\admin\storeLocationController;
use App\Http\Controllers\admin\userController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\checkoutController;
use App\Http\Controllers\messageController;
use App\Http\Controllers\orderController;
use App\Http\Controllers\pageController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ratingController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\userInfoController;
use App\Http\Middleware\roleMiddleware;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::get('welcome', function () {
    return view('welcome');
})->name('wellcome');
Route::get('/', [pageController::class, 'home'])->name('home');
Route::get('/detail/{slug}', [pageController::class, 'detail'])->name('detail');
Route::post('/detail/{slug}', [pageController::class, 'detail'])->name('detail');
Route::get('/search', [searchController::class, 'index'])->name('search');
Route::get('/iphone', [pageController::class, 'products'])->name('iphone');
Route::get('/macbook', [pageController::class, 'products'])->name('macbook');
Route::get('/ipad', [pageController::class, 'products'])->name('ipad');
Route::get('/watch', [pageController::class, 'products'])->name('watch');

Route::get('/test-broadcast', function () {
    broadcast(new TestBroadcast('Hello from backend!'));
    return "Broadcast event sent!";
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // user infor
    Route::put('/user-info/{id}', [userInfoController::class, 'update'])->name('user-info.update');

    // cart
    Route::get('/cart', [cartController::class, 'index'])->name('cart');
    Route::post('/cart/store', [cartController::class, 'store'])->name('cart.store');
    Route::put('/cart/update/{id}', [cartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [cartController::class, 'destroy'])->name('cart.destroy');

    // checkout
    Route::get('/checkout', [checkoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/store', [checkoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/pay/{type}/{orderId}', [checkoutController::class, 'show'])->name('checkout.pay-online');
    Route::get('/checkout/payment/result', [paymentController::class, 'vnpay'])->name('vnpay');

    // orders
    Route::get('/orders', [orderController::class, 'index'])->name('orders');
    Route::post('/orders', [orderController::class, 'index'])->name('orders');
    Route::put('/orders/update/{id}', [orderController::class, 'update'])->name('orders.update');

    // rating
    Route::get('/rating/{deailOrderID}/{detailProductID}', [ratingController::class, 'index'])->name('rating');
    Route::post('/rating/store/{deailOrderID}/{detailProductID}', [ratingController::class, 'store'])->name('rating.store');
    Route::put('/rating/update/{id}', [ratingController::class, 'update'])->name('rating.update');
    Route::delete('/rating/delete/{id}', [ratingController::class, 'destroy'])->name('rating.destroy');

    // message
    Route::post("/message/store", [messageController::class, 'store'])->name('message.store');
    Route::get('/message/new', [messageController::class, 'update'])->name('message.update');
    Route::delete("/message/delete/{id}", [messageController::class, 'destroy'])->name('message.destroy');

    // thankyou
    Route::get('/thank-you', function () {

        return view('pages.thankyou',);
    })->name('thank-you');
});

Route::middleware(['auth', roleMiddleware::class])
    ->prefix('admin')->as('admin.')
    ->group(function () {
        Route::get('dashboard', [dashboardController::class, 'index'])->name('dashboard');
        // products
        Route::get('products', [productController::class, 'index'])->name('products.index');
        Route::post('products', [productController::class, 'index'])->name('products.index');

        Route::get('products/create', [productController::class, 'create'])->name('products.create');
        Route::post('products/store', [productController::class, 'store'])->name('products.store');
        Route::get('products/edit/{slug}', [productController::class, 'edit'])->name('products.edit');
        Route::put('products/update/{id}', [productController::class, 'update'])->name('products.update');
        Route::get('products/detail/{slug}', [productController::class, 'show'])->name('products.detail');
        Route::post('products/detail/{slug}', [productController::class, 'show'])->name('products.detail');
        Route::delete('products/delete/{id}', [productController::class, 'destroy'])->name('products.destroy');

        // inStock
        Route::get('in-stocks/{detailID}/{storeID}', [inStockController::class, 'index'])->name('in-stocks.index');
        Route::put('in-stocks/update/{detailID}/{storeID}', [inStockController::class, 'update'])->name('in-stocks.update');
        // detail product
        Route::get('detail-products/create/{slug}', [detailProductController::class, 'create'])->name('detail-products.create');
        Route::post('detail-products/store', [detailProductController::class, 'store'])->name('detail-products.store');
        Route::get('detail-products/edit/{id}', [detailProductController::class, 'edit'])->name('detail-products.edit');
        Route::put('detail-products/update/{id}', [detailProductController::class, 'update'])->name('detail-products.update');
        Route::delete('detail-products/delete/{id}', [detailProductController::class, 'destroy'])->name('detail-products.destroy');

        // categories
        Route::get('categories', [categoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [categoryController::class, 'create'])->name('categories.create');
        Route::post('categories/store', [categoryController::class, 'store'])->name('categories.store');
        Route::get('categories/edit/{id}', [categoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/update/{id}', [categoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/delete/{id}', [categoryController::class, 'destroy'])->name('categories.destroy');
        // user
        Route::get('users', [userController::class, 'index'])->name('users.index');
        Route::post('users', [userController::class, 'index'])->name('users.index');
        Route::get('users/create', [userController::class, 'create'])->name('users.create');
        Route::post('users/store', [userController::class, 'store'])->name('users.store');
        Route::get('users/edit/{id}', [userController::class, 'edit'])->name('users.edit');
        Route::put('users/update/{id}', [userController::class, 'update'])->name('users.update');
        Route::delete('users/delete/{id}', [userController::class, 'destroy'])->name('users.destroy');
        // store location
        Route::get('store-location', [storeLocationController::class, 'index'])->name('store-location.index');
        Route::get('store-location/create', [storeLocationController::class, 'create'])->name('store-location.create');
        Route::post('store-location/store', [storeLocationController::class, 'store'])->name('store-location.store');
        Route::get('store-location/edit/{id}', [storeLocationController::class, 'edit'])->name('store-location.edit');
        Route::put('store-location/update/{id}', [storeLocationController::class, 'update'])->name('store-location.update');
        Route::delete('store-location/delete/{id}', [storeLocationController::class, 'destroy'])->name('store-location.destroy');

        // orders
        Route::get('/orders', [adminOrderController::class, 'index'])->name('orders.index');
        Route::post('/orders', [adminOrderController::class, 'index'])->name('orders.index');
        Route::put('/orders/update/{id}', [adminOrderController::class, 'update'])->name('orders.update');

        // chat
        Route::get("/message", [messageController::class, 'index'])->name('message.index');
    });

require __DIR__ . '/auth.php';

require __DIR__ . '/api.php';
