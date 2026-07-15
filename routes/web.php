<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CouponController;

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'register_store'])->name('register_store');
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'login_store'])->name('login_store');
});

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth', 'check.status', 'nocache'])->group(function () {
    Route::get('/crud', [UserController::class, 'index'])->name('crud');
    Route::get('/crud/view', [UserController::class, 'view'])->name('view');
    Route::get('/crud/insert', [UserController::class, 'index'])->name('insert.form');
    Route::get('/crud/insert/states/{country_id}', [UserController::class, 'getStates']);
    Route::get('/crud/insert/cities/{state_id}', [UserController::class, 'getCities']);
    Route::post('/crud/insert', [UserController::class, 'insert_i'])->name('insert');
    Route::get('/crud/delete/{id}', [UserController::class, 'delete'])->name('delete');
    Route::get('/crud/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('/crud/update/{id}', [UserController::class, 'update'])->name('update');  
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    
    // Cart
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update_qty');
    Route::get('/cart/remove/{id}', [CartController::class, 'removeCart'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');

    // Buy Now
    Route::get('/buy-now/{slug}', [CartController::class, 'buyNow'])->name('buy.now');

    // Order Time OTP Verify
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

    // Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update-profile', [UserController::class, 'updateProfile'])->name('profile.updateProfile');
    Route::get('/profile/security', [UserController::class, 'profile_security'])->name('profile.security');
    Route::post('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/move-to-cart', [WishlistController::class, 'moveToCart'])->name('cart.move');

    // Coupon
    Route::post('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon');
    Route::post('/remove-coupon', [CouponController::class, 'removeCoupon'])->name('remove.coupon');

}); 

    // About
    Route::get('/about', [UserController::class, 'about'])->name('about'); 
    
    // Contact
    Route::get('/contact', [UserController::class, 'contact'])->name('contact'); 

    // Product
    Route::get('/products', [ProductController::class, 'products'])->name('products');
    Route::get('/products/{slug}', [ProductController::class, 'productDetail'])->name('product.detail');



// Forget Password

Route::get('/forgot-password', [OtpController::class,'forgotPassword'])->name('forgot.password');
Route::post('/send-otp', [OtpController::class,'sendOtp'])->name('send.otp');
Route::get('/verify-otp', [OtpController::class,'verifyOtpForm'])->name('verify.otp');
Route::post('/verify-otp', [OtpController::class,'verifyOtp']);
Route::get('/reset-password', [OtpController::class,'resetPasswordForm'])->name('reset.password');
Route::post('/reset-password', [OtpController::class,'resetPassword']);


// Admin Routes
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'admin_dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'Admin_users']);
    Route::get('/admin/users/delete/{id}', [AdminController::class, 'user_delete'])->name('user_delete');
    Route::get('/admin/users/restore/{id}', [AdminController::class,'restoreUser'])->name('restore_user');
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
    Route::get('/admin/category/restore/{id}', [AdminController::class, 'restoreCategory'])->name('restore_category');
    Route::get('/admin/category/edit_category/{id}', [AdminController::class, 'edit_category'])->name('edit_category');
    Route::post('/admin/category/update/{id}', [AdminController::class, 'Update_category'])->name('update_category');
    Route::get('/admin/category/status/{id}', [AdminController::class, 'changeStatus_category'])->name('category_status');

    // Product
    Route::get('/admin/product', [AdminController::class, 'Admin_product']);
    Route::get('/admin/product/add_product', [AdminController::class, 'Add_product']);
    Route::post('/admin/product/store', [AdminController::class, 'Store_product'])->name('store_product');
    Route::get('/admin/product/delete/{id}', [AdminController::class, 'Delete_product'])->name('delete_product');
    Route::get('/admin/product/restore/{id}', [AdminController::class, 'restoreProduct'])->name('restore_product');
    Route::get('/admin/product/edit_product/{id}', [AdminController::class, 'edit_product'])->name('edit_product');
    Route::post('/admin/product/update/{id}', [AdminController::class, 'Update_product'])->name('update_product');
    Route::get('/admin/product/status/{id}', [AdminController::class, 'changeStatus_product'])->name('product_status');

    // Order
    Route::get('/admin/orders', [AdminController::class, 'Admin_orders'])->name('admin.orders');
    Route::get('/admin/order/view/{id}', [AdminController::class, 'Admin_order_view'])->name('admin.order.view');
    Route::post('/admin/order/status/{id}', [AdminController::class, 'Admin_order_status'])->name('admin.order.status');

    // Payment
    Route::get('/admin/payments', [AdminController::class, 'Admin_payments'])->name('admin.payments');
    Route::get('/admin/payment/view/{id}', [AdminController::class, 'Admin_payment_view'])->name('admin.payment.view');
    Route::post('/admin/payment/status/{id}', [AdminController::class, 'Admin_payment_status'])->name('admin.payment.status');

    // Profile
    Route::get('/admin/profile', [AdminController::class, 'admin_profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'profileUpdate'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [AdminController::class, 'passwordUpdate'])->name('admin.password.update');

    // Maintenance Mode
    Route::get('/admin/maintenance', [AdminController::class, 'maintenance'])->name('maintenance');

    // Coupon
    Route::get('/admin/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/admin/coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('/admin/coupons/store', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('/admin/coupons/edit/{id}', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/admin/coupons/update/{id}', [CouponController::class, 'update'])->name('coupons.update');
    Route::get('/admin/coupons/status/{id}', [CouponController::class, 'changeStatus'])->name('coupons.status');
    Route::delete('/admin/coupons/delete/{id}', [CouponController::class, 'destroy'])->name('coupons.delete');
    Route::get('/admin/coupons/generate-code',[CouponController::class, 'generateCode'])->name('coupons.generateCode');
    Route::get('/admin/coupons/view/{id}', [CouponController::class, 'show'])->name('coupons.view');

});