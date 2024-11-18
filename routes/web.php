<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Check if the user is authenticated
    if (Auth::check()) {
        // If the user is logged in, redirect to categories page
        return redirect()->route('categories');
    }

    // If the user is not logged in, show the login page
    return view('auth.login'); // This is the default login page
});

Route::get('/dashboard', function () {
    return redirect()->route('categories');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route to load the categories page (index page)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');

    // Route to fetch the categories (AJAX call)
    Route::get('/categories-data', [CategoryController::class, 'fetchCategories']);

    // Route to start quiz for a selected category
    Route::get('/quiz/{categoryId}', [QuizController::class, 'show'])->name('quiz.show');  // Show quiz page with questions

    // Route to handle quiz submission and show result
    Route::post('/quiz/result', [QuizController::class, 'showResult'])->name('quiz.result'); // Submit answers and show results
    Route::get('/quiz/{categoryName}', [QuizController::class, 'startQuiz'])->name('quiz.start');
});

require __DIR__ . '/auth.php';
