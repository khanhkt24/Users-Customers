<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!toggleActive
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('users')
    ->as('users.')
    ->group(function () {
        Route::get("/",                                         [UserController::class, 'index'])->name('index');
        Route::get("/create",                                   [UserController::class, 'create'])->name('create');
        Route::post("/store",                                   [UserController::class, 'store'])->name('store');
        Route::delete("/destroy/{user}",                        [UserController::class, 'destroy'])->name('destroy');
        Route::get("/edit/{user}",                              [UserController::class, 'edit'])->name('edit');
        Route::put("/update/{user}",                            [UserController::class, 'update'])->name('update');
        Route::get("/detail/{user}",                            [UserController::class, 'detail'])->name('detail');
        Route::get("/restore/{id}",                             [UserController::class, 'restore'])->name('restore');
        Route::delete("/forceDelete/{id}",                      [UserController::class, 'forceDelete'])->name('forceDelete');
        Route::get('/search',                                   [UserController::class, 'search'])->name('search');
        Route::get('/filter',                                   [UserController::class, 'filter'])->name('filter');
        Route::post('/filter',                                  [UserController::class, 'filter'])->name('filter');
        Route::post('/update-status/{id}',                      [UserController::class, 'updateStatus'])->name('updateStatus');
        Route::get("/recycle",                                  [UserController::class, 'recycle'])->name('recycle');

    });

