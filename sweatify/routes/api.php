<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
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

//Workouts
    Route::get('/workouts', [WorkoutController::class, 'index']);
    Route::get('/workouts/id/{id}', [WorkoutController::class, 'show']);
    Route::delete('/workouts/delete/{id}', [WorkoutController::class, 'destroy']);

    Route::get('/workouts/history/{userId}', [WorkoutHistoryController::class, 'show']);

    Route::post('/workouts/create', [WorkoutController::class, 'store']);
    Route::get('/workouts/types', [WorkoutController::class, 'types']);

    Route::put('/workouts/update/{workout}', [WorkoutController::class, 'update']);
});
