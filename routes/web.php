<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductAtributeController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController as UserPostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

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
// Route::get('/single-blog', function () {
//     return view('client.single-blog');
// })->name('single-blog');
Route::get('/', [ProductController::class, 'indexMain'])->name('index');
// Route::get('/single-product', function () {
//     return view('client.single-product');
// })->name('single-product');
// Route::get('/single-product', [ProductController::class, 'show'])->name('single-product');

// Route::get('/shop', function () {
//     return view('client.shop');
// })->name('shop');
// Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::resource('product', ProductController::class);
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
Route::resource('post', UserPostController::class);
Route::get('/404', function () {
    return view('client.404');
})->name('404');


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::resource('/user', AdminUserController::class);
    Route::get('/index_delete_user', [AdminUserController::class, 'indexDelete'])->name('user.indexDelUser');
    Route::get('/index', function () {
        return view('admin.layouts.index');
    })->name('index');
    Route::get('/form', function () {
        return view('admin.form.index');
    })->name('form');
    Route::get('/table', function () {
        return view('admin.table.index');
    })->name('table');
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
    Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    Route::resource('product', AdminProductController::class);
    //route Category
    Route::resource('category', CategoryController::class);
    Route::get('/admin/category/search', [CategoryController::class, 'search'])->name('category.search');
    //End route Category
    Route::resource('product.product_attribute', ProductAtributeController::class);
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


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::get('/brands/{id}', [BrandController::class, 'show'])->name('brands.show');
});
