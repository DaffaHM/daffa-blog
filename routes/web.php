<?php

use App\Http\Controllers\Back\BlogController;
use App\Http\Controllers\Back\UserController;
use App\Http\Controllers\Front\BlogController as FrontBlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontBlogController::class, 'index']);
Route::get('/berita/{slug}', [FrontBlogController::class, 'show']);




Route::get('/dashboard', function () {
    return view('back.dashboard');
})->middleware(['auth', 'verified', 'blocked'])->name('dashboard');

Route::middleware(['auth', 'verified', 'blocked'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //route blog

    Route::resource('blogs', BlogController::class)->names([
        'index' => 'blog.index',
        'create' => 'blog.create',
        'store' => 'blog.store',
        'edit' => 'blog.edit',
        'update' => 'blog.update',
        'destroy' => 'blog.destroy',
    ]);

    Route::get('/blogs/{blog}/delete', [BlogController::class, 'delete'])->name('blog.delete');

    // Route  USER 
    Route::resource('user', UserController::class)->names([

        'index' => 'user.index',
        'create' => 'user.create',
        'store' => 'user.store',
        'edit' => 'user.edit',
        'update' => 'user.update',
        'delete' => 'user.delete',
        'destroy' => 'user.destroy',

    ]);
    //halaman delete user

    route::get('/user/{user}/delete', [UserController::class, 'delete'])->name('user.delete');

    //blokir user 
    route::get('/user/{user}/toggle_block', [UserController::class, 'toggleBlock'])->name('user.toggle-block')->middleware('role_or_permission:admin-user');
});



require __DIR__ . '/auth.php';
