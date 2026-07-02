<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OtpController;

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

Route::middleware('guest')->group(function () {
    Route::get('/register', [FrontController::class, 'register'])->name('register');
    Route::post('/register', [FrontController::class, 'register_store'])->name('register_store');
    Route::get('/login', [FrontController::class, 'login'])->name('login');
    Route::post('/login', [FrontController::class, 'login_store'])->name('login_store');
});

    Route::get('/dashboard', [FrontController::class, 'dashboard'])->name('dashboard');
    Route::get('/crud', [FrontController::class, 'index'])->name('crud');
    Route::get('/crud/view', [FrontController::class, 'view'])->name('view');
    Route::get('/crud/insert', [FrontController::class, 'index'])->name('insert.form');
    Route::get('/crud/insert/states/{country_id}', [FrontController::class, 'getStates']);
    Route::get('/crud/insert/cities/{state_id}', [FrontController::class, 'getCities']);
    Route::post('/crud/insert', [FrontController::class, 'insert_i'])->name('insert');
    Route::get('/crud/delete/{id}', [FrontController::class, 'delete'])->name('delete');
    Route::get('/crud/edit/{id}', [FrontController::class, 'edit'])->name('edit');
    Route::post('/crud/update/{id}', [FrontController::class, 'update'])->name('update');
    Route::get('/about', [FrontController::class, 'about'])->name('about');    
    Route::post('/logout', [FrontController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [FrontController::class, 'profile'])->name('profile');
    Route::post('/profile/update-profile', [FrontController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::get('/profile/security', [FrontController::class, 'profile_security'])->name('profile.security');
    Route::post('/profile/update-password', [FrontController::class, 'updatePassword'])->name('profile.updatePassword');
    
    // Product
    Route::get('/products', [ProductController::class, 'products'])->name('products');
    Route::get('/products/{slug}', [ProductController::class, 'productDetail'])->name('product.detail');

    
Route::middleware('auth')->group(function () {
    // Cart
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Buy Now
    Route::get('/buy-now/{slug}', [CartController::class, 'buyNow'])->name('buy.now');

    // Order Time OTP Verify
    Route::get('/order/otp', [CartController::class, 'orderOtpForm'])->name('order.otp.form');
    Route::post('/order/otp', [CartController::class, 'verifyOrderOtp'])->name('order.otp.verify');

    // Place Order
    Route::post('/place-order', [CartController::class, 'placeOrder'])->name('place.order');

    // My Order
    Route::get('/my-orders', [CartController::class, 'myOrders'])->name('my.orders');

    // Order Cancel
    Route::post('/my-orders/cancel/{id}', [CartController::class, 'cancelOrder'])->name('order.cancel');

    // Rozorpay
    Route::post('/razorpay/success', [CartController::class, 'razorpaySuccess'])->name('razorpay.success');

    // Invoice
    Route::get('/invoice/{id}', [CartController::class, 'invoice'])->name('invoice');

});


// Forget Password

Route::get('/forgot-password', [OtpController::class,'forgotPassword'])->name('forgot.password');
Route::post('/send-otp', [OtpController::class,'sendOtp'])->name('send.otp');
Route::get('/verify-otp', [OtpController::class,'verifyOtpForm'])->name('verify.otp');
Route::post('/verify-otp', [OtpController::class,'verifyOtp']);
Route::get('/reset-password', [OtpController::class,'resetPasswordForm'])->name('reset.password');
Route::post('/reset-password', [OtpController::class,'resetPassword']);

Route::get('/admin', [AdminController::class, 'admin_login']);
Route::post('/admin', [AdminController::class, 'admin_login_store'])->name('admin_login_store');

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'Admin_users']);
    Route::get('/admin/users/delete/{id}', [AdminController::class, 'user_delete'])->name('user_delete');
    Route::get('/admin/data', [AdminController::class, 'Admin_data']);
    Route::get('/admin/data/delete/{id}', [AdminController::class, 'data_delete'])->name('admin_data_delete');
    Route::get('/admin/users/status/{id}', [AdminController::class, 'userStatus'])->name('user.status');
    Route::get('/admin/user/status/{id}', [AdminController::class, 'changeStatus'])->name('user.change_status');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin_logout');

    // Category 
    Route::get('/admin/category', [AdminController::class, 'Admin_category']);
    Route::get('/admin/category/add_category', [AdminController::class, 'Add_category']);
    Route::post('/admin/category/add_category', [AdminController::class, 'Store_category'])->name('store_category');
    Route::get('/admin/category/delete/{id}', [AdminController::class, 'Delete_category'])->name('delete_category');
    Route::get('/admin/category/edit_category/{id}', [AdminController::class, 'edit_category'])->name('edit_category');
    Route::post('/admin/category/update/{id}', [AdminController::class, 'Update_category'])->name('update_category');
    Route::get('/admin/category/status/{id}', [AdminController::class, 'changeStatus_category'])->name('category_status');

    // Product
    Route::get('/admin/product', [AdminController::class, 'Admin_product']);
    Route::get('/admin/product/add_product', [AdminController::class, 'Add_product']);
    Route::post('/admin/product/store', [AdminController::class, 'Store_product'])->name('store_product');
    Route::get('/admin/product/delete/{id}', [AdminController::class, 'Delete_product'])->name('delete_product');
    Route::get('/admin/product/edit_product/{id}', [AdminController::class, 'edit_product'])->name('edit_product');
    Route::post('/admin/product/update/{id}', [AdminController::class, 'Update_product'])->name('update_product');

    // Order
    Route::get('/admin/orders', [AdminController::class, 'Admin_orders'])->name('admin.orders');
    Route::get('/admin/order/view/{id}', [AdminController::class, 'Admin_order_view'])->name('admin.order.view');
    Route::post('/admin/order/status/{id}', [AdminController::class, 'Admin_order_status'])->name('admin.order.status');

    // Payment
    Route::get('/admin/payments', [AdminController::class, 'Admin_payments'])->name('admin.payments');
    Route::get('/admin/payment/view/{id}', [AdminController::class, 'Admin_payment_view'])->name('admin.payment.view');
    Route::post('/admin/payment/status/{id}', [AdminController::class, 'Admin_payment_status'])->name('admin.payment.status');

});
