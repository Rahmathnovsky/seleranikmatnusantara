<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\PromoController;
use App\Http\Controllers\Public\BrandController;
use App\Http\Controllers\Public\CareerController;
use App\Http\Controllers\Public\SitemapController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CmsController;
use App\Http\Controllers\Dashboard\BlogManageController;
use App\Http\Controllers\Dashboard\PromoManageController;
use App\Http\Controllers\Dashboard\BrandManageController;
use App\Http\Controllers\Dashboard\CareerManageController;
use App\Http\Controllers\Dashboard\UserManageController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::post('/language/{locale}', [HomeController::class, 'setLocale'])->name('language.set');
Route::post('/theme/{theme}', [HomeController::class, 'setTheme'])->name('theme.set');

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
    Route::post('/{id}/comment', [BlogController::class, 'storeComment'])->name('comment.store');
    Route::post('/{id}/like', [BlogController::class, 'like'])->name('like');
});

// Promo
Route::prefix('promo')->name('promo.')->group(function () {
    Route::get('/', [PromoController::class, 'index'])->name('index');
    Route::get('/{slug}', [PromoController::class, 'show'])->name('show');
    Route::post('/{id}/claim', [PromoController::class, 'claim'])->middleware('auth')->name('claim');
    Route::get('/my-vouchers', [PromoController::class, 'myVouchers'])->middleware('auth')->name('my-vouchers');
    Route::post('/verify-code', [PromoController::class, 'verifyCode'])->name('verify-code');
});

// Brands
Route::prefix('brands')->name('brands.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::get('/{slug}', [BrandController::class, 'show'])->name('show');
});

// Career
Route::prefix('career')->name('career.')->group(function () {
    Route::get('/', [CareerController::class, 'index'])->name('index');
    Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
    Route::post('/{id}/apply', [CareerController::class, 'apply'])->name('apply');
    Route::get('/{id}/apply/success', [CareerController::class, 'applySuccess'])->name('apply.success');
});

/*
|--------------------------------------------------------------------------
| Dashboard / Back-office Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'backoffice'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'backoffice'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // CMS Homepage
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/', [CmsController::class, 'index'])->name('index');
        Route::post('/', [CmsController::class, 'update'])->name('update');
        Route::post('/upload-image', [CmsController::class, 'uploadImage'])->name('upload-image');
    });

    // Blog Management
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogManageController::class, 'index'])->name('index');
        Route::get('/create', [BlogManageController::class, 'create'])->name('create');
        Route::post('/', [BlogManageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BlogManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BlogManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [BlogManageController::class, 'destroy'])->name('destroy');
        // Comments
        Route::get('/comments', [BlogManageController::class, 'comments'])->name('comments');
        Route::post('/comments/{id}/approve', [BlogManageController::class, 'approveComment'])->name('comments.approve');
        Route::post('/comments/{id}/reply', [BlogManageController::class, 'replyComment'])->name('comments.reply');
        Route::delete('/comments/{id}', [BlogManageController::class, 'destroyComment'])->name('comments.destroy');
        // Categories
        Route::get('/categories', [BlogManageController::class, 'categories'])->name('categories');
        Route::post('/categories', [BlogManageController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{id}', [BlogManageController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{id}', [BlogManageController::class, 'destroyCategory'])->name('categories.destroy');
    });

    // Promo Management
    Route::prefix('promo')->name('promo.')->group(function () {
        Route::get('/', [PromoManageController::class, 'index'])->name('index');
        Route::get('/create', [PromoManageController::class, 'create'])->name('create');
        Route::post('/', [PromoManageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PromoManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PromoManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [PromoManageController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/claims', [PromoManageController::class, 'claims'])->name('claims');
        Route::post('/claims/{id}/mark-used', [PromoManageController::class, 'markUsed'])->name('claims.mark-used');
        Route::post('/verify', [PromoManageController::class, 'verifyCode'])->name('verify');
    });

    // Brand Management
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', [BrandManageController::class, 'index'])->name('index');
        Route::get('/create', [BrandManageController::class, 'create'])->name('create');
        Route::post('/', [BrandManageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BrandManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BrandManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [BrandManageController::class, 'destroy'])->name('destroy');
        // Outlets
        Route::get('/{brandId}/outlets', [BrandManageController::class, 'outlets'])->name('outlets');
        Route::post('/{brandId}/outlets', [BrandManageController::class, 'storeOutlet'])->name('outlets.store');
        Route::put('/{brandId}/outlets/{id}', [BrandManageController::class, 'updateOutlet'])->name('outlets.update');
        Route::delete('/{brandId}/outlets/{id}', [BrandManageController::class, 'destroyOutlet'])->name('outlets.destroy');
        // Regions
        Route::get('/regions', [BrandManageController::class, 'regions'])->name('regions');
        Route::post('/regions', [BrandManageController::class, 'storeRegion'])->name('regions.store');
        Route::put('/regions/{id}', [BrandManageController::class, 'updateRegion'])->name('regions.update');
        Route::delete('/regions/{id}', [BrandManageController::class, 'destroyRegion'])->name('regions.destroy');
    });

    // Career Management (admin + hr only)
    Route::middleware('backoffice:admin,hr')->prefix('career')->name('career.')->group(function () {
        Route::get('/', [CareerManageController::class, 'index'])->name('index');
        Route::get('/create', [CareerManageController::class, 'create'])->name('create');
        Route::post('/', [CareerManageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CareerManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CareerManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [CareerManageController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/applications', [CareerManageController::class, 'applications'])->name('applications');
        Route::get('/applications/{appId}', [CareerManageController::class, 'showApplication'])->name('applications.show');
        Route::put('/applications/{appId}/status', [CareerManageController::class, 'updateApplicationStatus'])->name('applications.status');
    });

    // User Management (admin only)
    Route::middleware('backoffice:admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManageController::class, 'index'])->name('index');
        Route::get('/create', [UserManageController::class, 'create'])->name('create');
        Route::post('/', [UserManageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserManageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserManageController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserManageController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
