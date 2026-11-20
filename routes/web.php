<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\VendorController as AdminVendor;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Shop\CartController as ShopCart;
use App\Http\Controllers\Shop\OrderController as ShopOrder;
use App\Http\Controllers\Shop\ProductController as ShopProduct;
use App\Http\Controllers\Shop\StoreController as ShopStore;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboard;
use App\Http\Controllers\Vendor\ProductController as VendorProduct;
use App\Http\Controllers\Vendor\SettingsController as VendorSettings;
use App\Http\Controllers\Vendor\OrderController as VendorOrder;
use App\Http\Controllers\Vendor\RegistrationController as VendorRegistration;
use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\Shop\HomeController::class)->name('home');

Route::prefix('shop')->name('shop.')->group(function () {
    Route::redirect('/', '/shop/products', 301);
    Route::get('/products', [ShopProduct::class, 'index'])->name('products.index');
    Route::view('/about', 'shop.about')->name('about');
    Route::get('/contact', [\App\Http\Controllers\Shop\ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [\App\Http\Controllers\Shop\ContactController::class, 'store'])
        ->middleware('throttle:contact')
        ->name('contact.store');
    Route::get('/products/{product}', [ShopProduct::class, 'show'])->name('products.show');

    Route::get('/products/{product}/reviews', [\App\Http\Controllers\Shop\ReviewController::class, 'index'])->name('reviews.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/wishlist', [\App\Http\Controllers\Shop\WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}/toggle', [\App\Http\Controllers\Shop\WishlistController::class, 'toggle'])
            ->middleware('throttle:cart')
            ->name('wishlist.toggle');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/products/{product}/reviews', [\App\Http\Controllers\Shop\ReviewController::class, 'store'])
            ->middleware('throttle:cart')
            ->name('reviews.store');
        Route::delete('/products/{product}/reviews/{review}', [\App\Http\Controllers\Shop\ReviewController::class, 'destroy'])
            ->name('reviews.destroy');
    });
    Route::get('/stores/{vendor:slug}', [ShopStore::class, 'show'])->name('stores.show');
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [ShopCart::class, 'index'])->name('index');
        Route::post('/add/{product}', [ShopCart::class, 'add'])->middleware('throttle:cart')->name('add');
        Route::patch('/update/{product}', [ShopCart::class, 'update'])->middleware('throttle:cart')->name('update');
        Route::delete('/remove/{product}', [ShopCart::class, 'remove'])->name('remove');
        Route::delete('/clear', [ShopCart::class, 'clear'])->name('clear');
        Route::post('/coupon', [ShopCart::class, 'applyCoupon'])
            ->middleware(['auth', 'throttle:cart'])
            ->name('coupon.apply');
        Route::delete('/coupon', [ShopCart::class, 'removeCoupon'])->name('coupon.remove');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/checkout', [ShopOrder::class, 'checkout'])->name('checkout.index');
        Route::post('/checkout', [ShopOrder::class, 'store'])->middleware('throttle:checkout')->name('checkout.store');
        Route::get('/orders', [ShopOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}/review', [ShopOrder::class, 'review'])->name('orders.review');
        Route::post('/orders/{order}/accept-modification', [ShopOrder::class, 'acceptModification'])->name('orders.accept');
        Route::post('/orders/{order}/reject-modification', [ShopOrder::class, 'rejectModification'])->name('orders.reject');
        Route::get('/orders/{order}', [ShopOrder::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Shop\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/orders/{order}/invoice/pdf', [\App\Http\Controllers\Shop\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    });
});

Route::post('/language/{locale}', function (string $locale) {
    $supported = config('app.supported_locales', ['ar', 'en']);
    abort_unless(in_array($locale, $supported, true), 404);

    session()->put('locale', $locale);
    if (auth()->check()) {
        auth()->user()->update(['locale' => $locale]);
    }

    return back();
})->name('language.switch');

Route::get('dashboard', function () {
    $user = auth()->user();

    return match (true) {
        $user->hasRole('super_admin') => redirect()->route('admin.dashboard'),
        $user->hasRole('vendor')      => redirect()->route('vendor.dashboard'),
        $user->hasRole('customer')    => redirect()->route('customer.dashboard'),
        default                       => redirect()->route('profile.edit'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('/vendors', [AdminVendor::class, 'index'])->name('vendors.index');
        Route::post('/vendors/{vendor}/approve',    [AdminVendor::class, 'approve'])->name('vendors.approve');
        Route::post('/vendors/{vendor}/reject',     [AdminVendor::class, 'reject'])->name('vendors.reject');
        Route::post('/vendors/{vendor}/suspend',    [AdminVendor::class, 'suspend'])->name('vendors.suspend');
        Route::post('/vendors/{vendor}/reactivate', [AdminVendor::class, 'reactivate'])->name('vendors.reactivate');
        Route::resource('categories', AdminCategory::class)->except(['show']);

        Route::get('/messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('messages.index');

        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['show']);
        Route::get('/earnings', [\App\Http\Controllers\Admin\EarningsController::class, 'index'])->name('earnings.index');
        Route::get('/commissions', [\App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commissions.index');
        Route::put('/commissions/{vendor}', [\App\Http\Controllers\Admin\CommissionController::class, 'update'])->name('commissions.update');
        Route::put('/commissions-bulk', [\App\Http\Controllers\Admin\CommissionController::class, 'bulk'])->name('commissions.bulk');
        Route::post('/earnings/{earning}/settle', [\App\Http\Controllers\Admin\EarningsController::class, 'settle'])->name('earnings.settle');
        Route::post('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');

        Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/toggle', [\App\Http\Controllers\Admin\ReviewController::class, 'toggle'])->name('reviews.toggle');
        Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/replied', [\App\Http\Controllers\Admin\ContactMessageController::class, 'markReplied'])->name('messages.replied');
        Route::delete('/messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('messages.destroy');

        Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrder::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [AdminOrder::class, 'cancel'])->name('orders.cancel');
    });

Route::middleware(['auth', 'verified', 'role:vendor'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        Route::get('/dashboard', [VendorDashboard::class, 'index'])->name('dashboard');

        Route::resource('products', VendorProduct::class)->except(['show'])->middlewareFor(['store', 'update', 'destroy'], 'throttle:vendor-writes');

        Route::get('/settings', [VendorSettings::class, 'edit'])->name('settings.edit');
        Route::get('/reviews', [\App\Http\Controllers\Vendor\ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/reply', [\App\Http\Controllers\Vendor\ReviewController::class, 'reply'])->name('reviews.reply');
        Route::delete('/reviews/{review}/reply', [\App\Http\Controllers\Vendor\ReviewController::class, 'deleteReply'])->name('reviews.reply.delete');
        Route::put('/settings', [VendorSettings::class, 'update'])->name('settings.update');

        Route::get('/orders', [VendorOrder::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [VendorOrder::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/confirm', [VendorOrder::class, 'confirm'])->name('orders.confirm');
        Route::post('/orders/{order}/ship',    [VendorOrder::class, 'ship'])->name('orders.ship');
        Route::post('/orders/{order}/deliver', [VendorOrder::class, 'deliver'])->name('orders.deliver');
        Route::post('/orders/{order}/cancel',  [VendorOrder::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/{order}/modify',  [VendorOrder::class, 'showModifyForm'])->name('orders.modify.form');
        Route::post('/orders/{order}/modify', [VendorOrder::class, 'proposeModification'])->name('orders.modify');
    });

Route::middleware(['auth', 'verified'])
    ->prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        Route::get('/register', [VendorRegistration::class, 'create'])->name('register');
        Route::post('/register', [VendorRegistration::class, 'store'])->name('register.store');
    });

Route::middleware(['auth', 'verified', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    });

// Notifications (any authenticated user)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationsController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationsController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationsController::class, 'destroy'])->name('notifications.destroy');
});

require __DIR__.'/settings.php';

