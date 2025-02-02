<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showFormRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::get('reset-password', [AuthController::class, 'showFormReset'])->name('reset-password');
Route::post('reset-password', [AuthController::class, 'resetPassword']);

// Route::middleware(['auth'])

//     ->group(function () {});

Route::get('/', function () {
    return view('client.index');
})->name('index');
Route::get('/single-product', function () {
    return view('client.single-product');
})->name('single-product');
Route::get('/single-blog', function () {
    return view('client.single-blog');
})->name('single-blog');
Route::get('/shop', function () {
    return view('client.shop');
})->name('shop');
Route::get('/wishlist', function () {
    return view('client.wishlist');
})->name('wishlist');
// Route::get('/my-account', function () {
//     return view('client.my-account');
// })->name('my-account');
Route::get(
    'my-account',
    [UserController::class, 'show']
)->name('my-account');
// Route::get('my-account/{user}',    [UserController::class, 'edit'])->name('my-account.edit');
// Route::put('my-account/{user}',    [UserController::class, 'update'])->name('my-account.update');
// Route::get('/login-register', function () {
//     return view('client.login-register');
// })->name('login-register');
Route::get('/contact', function () {
    return view('client.contact');
})->name('contact');
Route::get('/checkout', function () {
    return view('client.checkout');
})->name('checkout');
Route::get('/cart', function () {
    return view('client.cart');
})->name('cart');
Route::get('/blog', function () {
    return view('client.blog');
})->name('blog');
Route::get('/404', function () {
    return view('client.404');
})->name('404');


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/index', function () {
        return view('admins.layout.index');
    })->name('index');
    Route::get('/form', function () {
        return view('admins.form.index');
    })->name('form');
    Route::get('/table', function () {
        return view('admins.table.index');
    })->name('table');
});

Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
    Route::get('/index', function () {
        return view('staffs.layout.index');
    })->name('index');
    Route::get('/form', function () {
        return view('staffs.form.index');
    })->name('form');
    Route::get('/table', function () {
        return view('staffs.table.index');
    })->name('table');
});
