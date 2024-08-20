<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/cart', [ProductController::class, 'indexGuest'])->name('cart.index.pelanggan');



Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::post('destroyProduct', [ProductController::class,'destroyProduct']);
    // Route::delete('/destroyProduct/{id}', 'ProductController@destroyProduct')->name('data.delete');

    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);

    Route::get('admin/api/products', [OrderController::class,'apiproduct']);
    Route::get('laporanHarian', [OrderController::class,'indexLaporan']);
    Route::get('laporanBulanan', [OrderController::class,'indexLaporanBulanan']);

    Route::get('cariLaporan', [OrderController::class,'cariLaporan']);
    Route::get('cariLaporanBulanan', [OrderController::class,'cariLaporanBulanan']);

    Route::get('stok-masuk', [OrderController::class,'showStokMasuk']);
    Route::get('return-masuk', [OrderController::class,'showReturnMasuk']);


    // Route::get('generate-pdf', [OrderController::class, 'indexprint'])->name('generate-pdf');
    Route::get('generatestok-pdf', [OrderController::class, 'indexPrintStok'])->name('generatestok.pdf');
    Route::get('generate-pdf', [OrderController::class, 'indexPrint'])->name('generate.pdf');
    Route::get('generate-pdfreturn', [OrderController::class, 'indexPrintReturn'])->name('generatereturn.pdf');
    Route::get('generate-pdfbulanan', [OrderController::class, 'indexPrintBulanan'])->name('generatebulanan.pdf');



 
    

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/indexcart', [CartController::class, 'indexstok'])->name('cart.indexstok');
    Route::get('/indexreturn', [CartController::class, 'indexreturn'])->name('cart.indexreturn');


    Route::post('/addstok', [OrderController::class, 'storestok'])->name('addstok.index');
    Route::post('/addreturn', [OrderController::class, 'storereturn'])->name('addreturn.index');

    
    Route::get('/stok', [CartController::class, 'addstok'])->name('stok.index');
    
    Route::post('/return', [CartController::class, 'storereturn'])->name('return.store');
    Route::get('/return', [CartController::class, 'addreturn'])->name('return.index');

    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/stok', [CartController::class, 'storestok'])->name('stok.store');



    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::post('/stok/change-qty', [CartController::class, 'changeQtystok']);
    Route::post('/return/change-qty', [CartController::class, 'changeQtyreturn']);


    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/stok/delete', [CartController::class, 'deletestok']);
    Route::delete('/return/delete', [CartController::class, 'deletereturn']);


    Route::delete('/cart/empty', [CartController::class, 'empty']);

    // Transaltions route for React component
    Route::get('/locale/{type}', function ($type) {
        $translations = trans($type);
        return response()->json($translations);
    });
});
