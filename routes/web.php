<?php

use App\Http\Controllers\AddressUserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\SkusQuantityController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController as UserPostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RefundController as AdminRefundController;
use App\Http\Controllers\Admin\SkusController;
use App\Http\Controllers\BrandController as ControllersBrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\CancelledOrderController;

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



// thêm middleware auth vào các route admin
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Public admin routes (không cần đăng nhập)
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.submit');
    
    // Protected admin routes (cần đăng nhập và phải là admin/staff)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        
        // User management
        Route::resource('/user', AdminUserController::class);
        Route::get('/index_delete_user', [AdminUserController::class, 'indexDelete'])->name('user.indexDelUser');
        
        // Voucher management
        Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
        Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
        Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
        Route::get('/vouchers/{voucher}/edit', [VoucherController::class, 'edit'])->name('vouchers.edit');
        Route::put('/vouchers/{voucher}', [VoucherController::class, 'update'])->name('vouchers.update');
        Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
        Route::post('/vouchers/bulk-delete', [VoucherController::class, 'bulkDelete'])->name('vouchers.bulk_delete');
        
        // Product management
        Route::resource('product', AdminProductController::class);
        Route::put('product/{product}/change_status', [AdminProductController::class, 'changeStatus'])->name('product.change_status');
        
        // Category management
        Route::get('/category/search', [CategoryController::class, 'search'])->name('category.search');
        Route::resource('category', CategoryController::class);
        
        // Product attribute
        Route::resource('product_attribute', ProductAttributeController::class);
        
        // Post management
        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
        
        // Brand management
        Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
        Route::get('/brands/{id}', [BrandController::class, 'show'])->name('brands.show');
        
        // SKUs management
        Route::resource('product.skus', SkusController::class);
        Route::put('products/{product}/skus/{sku}/change_status', [SkusController::class, 'changeStatus'])->name('skus.change_status');
        
        // Order management
        Route::put('orders/update-multiple-status', [AdminOrderController::class, 'updateMultipleStatus'])->name('orders.update_multiple_status');
        Route::resource('orders', AdminOrderController::class);
        Route::put('orders/{order}/refund', [AdminOrderController::class, 'updateRefund']);
        // Route::post('/orders/{id}/confirm-received', [OrderController::class, 'confirmReceived'])->name('orders.confirmReceived');
        // Route::post('/orders/{id}/handle-not-received', [OrderController::class, 'handleNotReceived'])->name('orders.handleNotReceived');

        Route::resource('skus', SkusQuantityController::class);
        Route::get('skus_comfirm', [SkusQuantityController::class, 'indexConfirm'])->name('skus_confirm');
        Route::post('comfirm', [SkusQuantityController::class, 'confirm'])->name('confirm');
        Route::get('skus_history', [SkusQuantityController::class, 'indexHistory'])->name('skus_history');
        Route::post('history', [SkusQuantityController::class, 'confirm'])->name('history');
        Route::put('update-inventory-entry', [SkusQuantityController::class, 'updateInventoryEntry'])->name('update_inventory_entry');
        
        // Refund management
        Route::resource('refunds', AdminRefundController::class);
        // Thêm route để xử lý yêu cầu hoàn tiền
        Route::post('refunds/{refund_id}/process', [AdminRefundController::class, 'process'])->name('refunds.process');

        
        // Order PDF
        Route::get('orders/{id}/download_pdf', [AdminOrderController::class, 'downloadPdf'])->name('orders.download_pdf');
        Route::post('orders/download-multiple_pdf', [AdminOrderController::class, 'downloadMultiplePdf'])->name('orders.download_multiple_pdf');
    
        // Form & table views
        Route::get('/form', function () {
            return view('admin.form.index');
        })->name('form');
        
        Route::get('/table', function () {
            return view('admin.table.index');
        })->name('table');

        Route::get('orderdispute', [\App\Http\Controllers\Admin\OrderDisputeController::class, 'index'])->name('orderdispute.index');
        Route::post('orderdispute/process/{id}', [\App\Http\Controllers\Admin\OrderDisputeController::class, 'process'])->name('orderdispute.process');

        // Quản lý đơn hàng bị hủy
        Route::get('/cancelled-orders', [CancelledOrderController::class, 'index'])->name('cancelled-orders.index');
        Route::get('/cancelled-orders/{id}', [CancelledOrderController::class, 'show'])->name('cancelled-orders.show');
        Route::post('/cancelled-orders/{id}/process', [CancelledOrderController::class, 'process'])->name('cancelled-orders.process');
    });
});

Route::group(['prefix' => 'staff', 'as' => 'staff.'], function () {
    Route::get('/index', function () {
        return view('staff.layouts.index');
    })->name('index');
    Route::get('/form', function () {
        return view('staff.form.index');
    })->name('form');
    Route::get('/table', function () {
        return view('staff.table.index');
    })->name('table');
});

Route::middleware('auth')->group(function () {
    Route::post('/cart/add_to_cart/{id}', [CartController::class, 'addToCart'])->name('add.cart');
    Route::get('/cart', [CartController::class, 'index'])->name('show.cart');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity']);
    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('applyVoucher');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('remove.cart');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('clear.cart');

    // Thêm route mới để cập nhật hàng loạt địa chỉ
    Route::post('/address/update', [AddressUserController::class, 'batchUpdate'])->name('address.update');

    Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');

    Route::get('/payment', [PaymentController::class, 'index'])->name('indexPayment');


    Route::post('order/create', [OrderController::class, 'placeOrder'])->name('payOrder');
    Route::resource('order', OrderController::class)->only(['update']);

    Route::resource('brands', ControllersBrandController::class);
    Route::resource('products', ProductController::class);
    // Route::get('/locations/{type}/{id?}', [PaymentController::class, 'getLocations']);
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('index_wishlist');
    Route::post('/wishlist/add_to_wishlist/{id}', [WishlistController::class, 'store'])->name('add_wishlist');
    Route::get('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('delete_wishlist');
    Route::post('/lock-account', [OrderController::class, 'lockAccount'])->name('lock.account');
    Route::post('/create-payment-attempt', [OrderController::class, 'createPaymentAttempt'])
        ->name('create.payment.attempt');
    Route::get('/check-stock', [OrderController::class, 'checkStock'])
    ->name('check_stock');
    Route::get('/order/cleanup-expired', [OrderController::class, 'cleanupExpiredPaymentAttempts'])
    ->name('order.cleanup_expired')
    ->middleware('admin');

    // Review routes
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::post('/voucher/apply', [PaymentController::class, 'apply'])->name('voucher.apply');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');
Route::get('/payment/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('payment.paypal.success');
Route::get('/payment/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('payment.paypal.cancel');


Route::prefix('address-user')->group(function () {
    Route::post('/add', [AddressUserController::class, 'addAddress'])->name('address-user.add');
    Route::post('/edit/{id}', [AddressUserController::class, 'getAddressById'])->name('address-user.edit');
    Route::put('/update/{id}', [AddressUserController::class, 'updateAddress'])->name('address-user.update');
    Route::get('/list', [AddressUserController::class, 'renderAddressList']);
    Route::delete('/delete/{id}', [AddressUserController::class, 'deleteAddress'])->name('address-user.delete');
    Route::post('/default/{id}', [AddressUserController::class, 'defaultAddress'])->name('address-user.default');
});
// routes/web.php
Route::post('/apply-voucher', [PaymentController::class, 'applyVoucher']);



Route::get('/success', function () {
    return view('client.success');
})->name('order_success');



