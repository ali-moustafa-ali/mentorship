<?php

use App\Http\Controllers\TopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopicController::class, 'index'])->name('topics.index');
Route::get('/search', [TopicController::class, 'search'])->name('search');
Route::get('/graph', [TopicController::class, 'graph'])->name('topics.graph');
Route::get('/review', [TopicController::class, 'review'])->name('topics.review');
Route::get('/api/topics', [TopicController::class, 'apiTopics'])->name('api.topics');
Route::post('/upload-image', [TopicController::class, 'uploadImage'])->name('upload.image');

Route::resource('topics', TopicController::class)->except('index');

Route::post('/topics/{topic}/toggle-pin', [TopicController::class, 'togglePin'])->name('topics.togglePin');
Route::get('/topics/{topic}/versions', [TopicController::class, 'versions'])->name('topics.versions');
Route::post('/topics/{topic}/versions/{version}/restore', [TopicController::class, 'restoreVersion'])->name('topics.restoreVersion');
Route::get('/topics/{topic}/export/{format}', [TopicController::class, 'export'])->name('topics.export');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('index');

    // Domains
    Route::get('/domains', [App\Http\Controllers\AdminController::class, 'domains'])->name('domains.index');
    Route::get('/domains/create', [App\Http\Controllers\AdminController::class, 'createDomain'])->name('domains.create');
    Route::post('/domains', [App\Http\Controllers\AdminController::class, 'storeDomain'])->name('domains.store');
    Route::get('/domains/{domain}/edit', [App\Http\Controllers\AdminController::class, 'editDomain'])->name('domains.edit');
    Route::put('/domains/{domain}', [App\Http\Controllers\AdminController::class, 'updateDomain'])->name('domains.update');
    Route::delete('/domains/{domain}', [App\Http\Controllers\AdminController::class, 'destroyDomain'])->name('domains.destroy');

    // Tags
    Route::get('/tags', [App\Http\Controllers\AdminController::class, 'tags'])->name('tags.index');
    Route::get('/tags/{tag}/edit', [App\Http\Controllers\AdminController::class, 'editTag'])->name('tags.edit');
    Route::put('/tags/{tag}', [App\Http\Controllers\AdminController::class, 'updateTag'])->name('tags.update');
    Route::delete('/tags/{tag}', [App\Http\Controllers\AdminController::class, 'destroyTag'])->name('tags.destroy');

    // Categories
    Route::get('/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories/rename', [App\Http\Controllers\AdminController::class, 'renameCategory'])->name('categories.rename');
    Route::post('/categories/destroy', [App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('categories.destroy');
});
