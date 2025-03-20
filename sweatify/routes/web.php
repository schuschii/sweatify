<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Exercise Views
Route::get('/exercises', function () {
    return view('exercises');
})->middleware(['auth', 'verified'])->name('exercises');

Route::get('/exercises/id/{id}', function () {
    return view('exercise.show');
})->middleware(['auth', 'verified'])->name('exercise.show');

Route::get('/exercises/name/{name}', function () {
    return view('exercise');
})->middleware(['auth', 'verified'])->name('exercises.name');


Route::get('/exercises/bodyPartList', function () {
    return view('exercise.bodyPartList');
})->middleware(['auth', 'verified'])->name('exercises.bodyPartList');

Route::get('/exercises/body-part/{bodyPart}', function () {
    return view('exercise.bodypart');
})->middleware(['auth', 'verified'])->name('exercise.bodypart');



Route::get('/exercises/equipmentList', function () {
    return view('exercise.equipmentList');
})->middleware(['auth', 'verified'])->name('exercises.equipmentList');

Route::get('/exercises/equipment/{equipment}', function () {
    return view('exercise.equipment');
})->middleware(['auth', 'verified'])->name('exercises.equipment');



Route::get('/exercises/targetList', function () {
    return view('exercise.targetList');
})->middleware(['auth', 'verified'])->name('exercises.targetList');

Route::get('/exercises/target/{target}', function () {
    return view('exercise.target');
})->middleware(['auth', 'verified'])->name('exercises.target');

// Workout Views
Route::get('/workouts/id/{id}', function () {
    return view('workout.show');
})->middleware(['auth', 'verified'])->name('workout.show');

require __DIR__.'/auth.php';
