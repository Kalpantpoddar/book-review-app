<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () { return view('welcome'); });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('book/{id}', [HomeController::class, 'details'])->name('book.details');
Route::post('/save-book-review', [HomeController::class, 'saveReview'])->name('book.saveReview');


// Route::get('account/profile', [AccountController::class, 'profile'])->name('account.profile');

Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('register', [AccountController::class, 'register'])->name('account.register');
        Route::post('register', [AccountController::class, 'processRegister'])->name('account.processRegister');
        Route::get('login', [AccountController::class, 'login'])->name('account.login');
        Route::post('login', [AccountController::class, 'authenticate'])->name('account.authenticate');
    });
    Route::group(['middleware' => 'auth'], function(){
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::post('update_profile', [AccountController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get('logout', [AccountController::class, 'logout'])->name('account.logout');

        Route::group(['middleware' => 'check-admin'], function(){
            Route::get('books', [BookController::class, 'index'])->name('books.index');
            Route::get('books/create', [BookController::class, 'create'])->name('books.create');
            Route::post('books', [BookController::class, 'store'])->name('books.store');
            Route::get('books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
            Route::post('books/edit/{id}', [BookController::class, 'update'])->name('books.update');
            Route::delete('books', [BookController::class, 'destroy'])->name('books.destroy');

            Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
            Route::get('review/edit/{id}', [ReviewController::class, 'edit'])->name('review.edit');
            Route::post('review/edit/{id}', [ReviewController::class, 'update'])->name('review.update');
            Route::delete('destroy-review', [ReviewController::class, 'deleteReview'])->name('review.destroy');
        });

        

        Route::get('my-reviews', [AccountController::class, 'myReviews'])->name('reviews.myReviews');
        Route::get('my-reviews/{id}', [AccountController::class, 'editReview'])->name('reviews.editMyReview');
        Route::post('my-reviews/{id}', [AccountController::class, 'updateReview'])->name('reviews.updateMyReview');
        Route::delete('delete-my-reviews', [AccountController::class, 'destroyReview'])->name('reviews.destroyMyReview');
    });
});
