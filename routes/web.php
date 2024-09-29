<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('review.index');
});
Route::get('/reviews', [ReviewController::class, 'index'])->name('review.index');
Route::middleware('auth')->group(function () {
    Route::post('/reviews', [ReviewController::class, 'newReview'])->name('review.create');
    Route::get('/reviews/create', [ReviewController::class, 'reviewCreate'])->name('review.make');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'reviewEdit'])->name('review.edit');
    Route::post('/reviews/{id}', [ReviewController::class, 'reviewUpdate'])->name('review.update');
    Route::delete('/reviews/{id}/delete', [ReviewController::class, 'destroyReview'])->name('review.destroy');
    Route::post('/comments', [CommentController::class, 'newComment'])->name('comment.create');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/reviews/{id}', [ReviewController::class, 'reviewSingle'])->name('review.single');


Route::get('/dashboard', function () {
    return redirect()->route('review.index');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__.'/auth.php';
