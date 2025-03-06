<?php

use App\Http\Controllers\Exercise\ExerciseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Exercises
Route::get('/exercises', [ExerciseController::class, 'index']);
Route::get('/exercises/{id}', [ExerciseController::class, 'show']);
Route::get('/exercises/body-part/{bodyPart}', [ExerciseController::class, 'filterByBodyPart']);
