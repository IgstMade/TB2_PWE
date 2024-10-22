<?php

use App\Http\Controllers\ContohController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produk', function () {
    return view('produk');
});

Route::get('/contoh', [ContohController::class, 'TampilContoh']);
Route::get('/produk', [ProdukController::class, 'ViewProduk']);
Route::get('produk/add',[ProdukController::class, 'ViewAddProduk']);
Route::POST('produk/add',[ProdukController::class, 'CreateProduk']);

Route::delete('/produk/delete/{kode_produk}', [ProdukController::class, 'DeleteProduk']);
Route::get('/produk/edit/{kode_produk}', [ProdukController::class, 'ViewEditProduk']);
Route::put('/produk/edit/{kode_produk}', [ProdukController::class, 'ViewEditProduk']);

//});
//});

