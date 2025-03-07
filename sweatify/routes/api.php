<?php

use App\Http\Controllers\ExerciseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Exercises
Route::get('/exercises', [ExerciseController::class, 'index']);
Route::get('/exercises/id/{id}', [ExerciseController::class, 'show']);
Route::get('/exercises/name/{name}', [ExerciseController::class, 'getByName']);

Route::get('/exercises/bodyPartList', [ExerciseController::class, 'getBodyPartList']);
Route::get('/exercises/body-part/{bodyPart}', [ExerciseController::class, 'filterByBodyPart']);

Route::get('/exercises/equipmentList', [ExerciseController::class, 'getEquipmentList']);
Route::get('/exercises/equipment/{equipment}', [ExerciseController::class, 'filterByEquipment']);

Route::get('/exercises/targetList', [ExerciseController::class, 'getTargetList']);
Route::get('/exercises/target/{target}', [ExerciseController::class, 'filterByTarget']);

// TODO: Make use of Sanctum to authenticate api endpoints.
//Route::get('/exercises', [ExerciseController::class, 'index'])->middleware('auth:sanctum');

