<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DevPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/products', [SiteController::class, 'products'])->name('products');
Route::get('/industries', [SiteController::class, 'industries'])->name('industries');
Route::get('/insights', [SiteController::class, 'insights'])->name('insights');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');

Route::get('/dev-portal', [DevPortalController::class, 'index'])->name('dev.portal');
Route::post('/dev-portal/checkout', [DevPortalController::class, 'checkout'])->name('dev.checkout');
Route::get('/dev-portal/complete/{code}', [DevPortalController::class, 'complete'])->name('dev.order.complete');
Route::post('/api/license/verify', [DevPortalController::class, 'verify'])->name('dev.license.verify');

Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('robots');

Route::get('/lake-zone-control', [AdminController::class, 'login'])->name('admin.login');
Route::post('/lake-zone-control', [AdminController::class, 'authenticate'])->name('admin.authenticate');

Route::middleware('admin.auth')->prefix('lake-zone-control')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('/company', [AdminController::class, 'updateCompany'])->name('company.update');
    Route::put('/seo', [AdminController::class, 'updateSeo'])->name('seo.update');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    Route::post('/industries', [AdminController::class, 'storeIndustry'])->name('industries.store');
    Route::put('/industries/{industry}', [AdminController::class, 'updateIndustry'])->name('industries.update');
    Route::delete('/industries/{industry}', [AdminController::class, 'destroyIndustry'])->name('industries.destroy');
    Route::post('/insights', [AdminController::class, 'storeInsight'])->name('insights.store');
    Route::put('/insights/{insight}', [AdminController::class, 'updateInsight'])->name('insights.update');
    Route::delete('/insights/{insight}', [AdminController::class, 'destroyInsight'])->name('insights.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
